<?php

namespace app\models;

use PDO;
use Exception;

class AdminModel
{
    private $db;

    public function __construct(PDO $database)
    {
        $this->db = $database;
    }

    // Liste toutes les habitations
    // Dans AdminModel.php
    public function listHabitations()
    {
        try {
            $stmt = $this->db->query("
            SELECT h.*, t.nom as type_nom,
            (SELECT url_photo FROM photos WHERE habitation_id = h.id LIMIT 1) as photo_principale
            FROM habitations h 
            JOIN types_habitation t ON h.type_id = t.id
        ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des habitations: " . $e->getMessage());
        }
    }

    // Récupère les types d'habitation
    public function getTypes()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM types_habitation");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des types: " . $e->getMessage());
        }
    }

    // Ajoute une habitation
    public function ajouterHabitation($data)
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO habitations (type_id, nb_chambres, loyer_jour, quartier, description)
                VALUES (:type_id, :nb_chambres, :loyer_jour, :quartier, :description)
            ");

            $stmt->execute([
                'type_id' => $data['type_id'],
                'nb_chambres' => $data['nb_chambres'],
                'loyer_jour' => $data['loyer_jour'],
                'quartier' => $data['quartier'],
                'description' => $data['description']
            ]);

            return $this->db->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de l'ajout de l'habitation: " . $e->getMessage());
        }
    }

    // Ajoute une photo
    public function ajouterPhoto($habitation_id, $url_photo)
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO photos (habitation_id, url_photo)
                VALUES (:habitation_id, :url_photo)
            ");
            return $stmt->execute([
                'habitation_id' => $habitation_id,
                'url_photo' => $url_photo
            ]);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de l'ajout de la photo: " . $e->getMessage());
        }
    }

    public function supprimerPhoto($photo_id) {
        try {
            // D'abord récupérer l'URL de la photo pour pouvoir supprimer le fichier
            $stmt = $this->db->prepare("SELECT url_photo FROM photos WHERE id = :id");
            $stmt->execute(['id' => $photo_id]);
            $photo = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($photo && file_exists($photo['url_photo'])) {
                unlink($photo['url_photo']); // Supprimer le fichier physique
            }
            
            // Ensuite supprimer l'entrée de la base de données
            $stmt = $this->db->prepare("DELETE FROM photos WHERE id = :id");
            return $stmt->execute(['id' => $photo_id]);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la suppression de la photo: " . $e->getMessage());
        }
    }
    
    public function remplacerPhotos($habitation_id, $nouvelles_photos) {
        try {
            // Supprimer toutes les anciennes photos
            $anciennes_photos = $this->getPhotos($habitation_id);
            foreach ($anciennes_photos as $photo) {
                $this->supprimerPhoto($photo['id']);
            }
            
            // Ajouter les nouvelles photos
            foreach ($nouvelles_photos as $photo) {
                $this->ajouterPhoto($habitation_id, $photo);
            }
        } catch (Exception $e) {
            throw new Exception("Erreur lors du remplacement des photos: " . $e->getMessage());
        }
    }

    // Modifie une habitation
    public function modifierHabitation($id, $data)
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE habitations 
                SET type_id = :type_id,
                    nb_chambres = :nb_chambres,
                    loyer_jour = :loyer_jour,
                    quartier = :quartier,
                    description = :description
                WHERE id = :id
            ");

            return $stmt->execute([
                'id' => $id,
                'type_id' => $data['type_id'],
                'nb_chambres' => $data['nb_chambres'],
                'loyer_jour' => $data['loyer_jour'],
                'quartier' => $data['quartier'],
                'description' => $data['description']
            ]);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la modification de l'habitation: " . $e->getMessage());
        }
    }

    // Supprime une habitation
    public function supprimerHabitation($id)
    {
        try {
            // Supprimer d'abord les photos associées
            $stmt = $this->db->prepare("DELETE FROM photos WHERE habitation_id = :id");
            $stmt->execute(['id' => $id]);

            // Puis supprimer l'habitation
            $stmt = $this->db->prepare("DELETE FROM habitations WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la suppression de l'habitation: " . $e->getMessage());
        }
    }

    // Récupère les photos d'une habitation
    public function getPhotos($habitation_id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM photos WHERE habitation_id = :id");
            $stmt->execute(['id' => $habitation_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des photos: " . $e->getMessage());
        }
    }

    // Récupère une habitation spécifique avec ses photos
    public function getHabitation($id)
    {
        try {
            // Récupérer l'habitation
            $stmt = $this->db->prepare("
            SELECT h.*, t.nom as type_nom 
            FROM habitations h 
            JOIN types_habitation t ON h.type_id = t.id
            WHERE h.id = :id
        ");
            $stmt->execute(['id' => $id]);
            $habitation = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($habitation) {
                // Récupérer les photos
                $photos = $this->getPhotos($id);
                $habitation['photos'] = $photos;
            }

            return $habitation;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération de l'habitation: " . $e->getMessage());
        }
    }

    // Récupère la liste des types avec le nombre d'habitations associées
    public function getTypesWithCount()
    {
        try {
            $stmt = $this->db->query("
            SELECT t.*, COUNT(h.id) as nb_habitations 
            FROM types_habitation t 
            LEFT JOIN habitations h ON t.id = h.type_id 
            GROUP BY t.id
        ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des types: " . $e->getMessage());
        }
    }

    public function ajouterType($nom)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO types_habitation (nom) VALUES (:nom)");
            return $stmt->execute(['nom' => $nom]);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de l'ajout du type: " . $e->getMessage());
        }
    }

    public function modifierType($id, $nom)
    {
        try {
            $stmt = $this->db->prepare("UPDATE types_habitation SET nom = :nom WHERE id = :id");
            return $stmt->execute(['id' => $id, 'nom' => $nom]);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la modification du type: " . $e->getMessage());
        }
    }

    public function supprimerType($id)
    {
        try {
            // Vérifier si le type est utilisé
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM habitations WHERE type_id = :id");
            $stmt->execute(['id' => $id]);
            if ($stmt->fetchColumn() > 0) {
                throw new Exception("Ce type ne peut pas être supprimé car il est utilisé par des habitations");
            }

            // Si non utilisé, supprimer
            $stmt = $this->db->prepare("DELETE FROM types_habitation WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function listReservations($filters = [])
    {
        try {
            $sql = "
            SELECT r.*, 
                   h.description as habitation_description,
                   u.nom as client_nom,
                   u.email as client_email,
                   u.telephone as client_telephone
            FROM reservations r
            JOIN habitations h ON r.habitation_id = h.id
            JOIN users u ON r.user_id = u.id
            WHERE 1=1
        ";

            $params = [];

            // Application des filtres
            if (!empty($filters['habitation_id'])) {
                $sql .= " AND r.habitation_id = :habitation_id";
                $params['habitation_id'] = $filters['habitation_id'];
            }

            if (!empty($filters['user_id'])) {
                $sql .= " AND r.user_id = :user_id";
                $params['user_id'] = $filters['user_id'];
            }

            if (!empty($filters['date_debut'])) {
                $sql .= " AND r.date_arrivee >= :date_debut";
                $params['date_debut'] = $filters['date_debut'];
            }

            if (!empty($filters['date_fin'])) {
                $sql .= " AND r.date_depart <= :date_fin";
                $params['date_fin'] = $filters['date_fin'];
            }

            $sql .= " ORDER BY r.date_arrivee DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des réservations: " . $e->getMessage());
        }
    }

    public function getReservation($id)
    {
        try {
            $stmt = $this->db->prepare("
            SELECT r.*, 
                   h.description as habitation_description,
                   u.nom as client_nom,
                   u.email as client_email,
                   u.telephone as client_telephone
            FROM reservations r
            JOIN habitations h ON r.habitation_id = h.id
            JOIN users u ON r.user_id = u.id
            WHERE r.id = :id
        ");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération de la réservation: " . $e->getMessage());
        }
    }

    public function checkDisponibilite($habitation_id, $date_arrivee, $date_depart)
    {
        try {
            $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM reservations
            WHERE habitation_id = :habitation_id
            AND (
                (date_arrivee <= :date_arrivee AND date_depart >= :date_arrivee)
                OR 
                (date_arrivee <= :date_depart AND date_depart >= :date_depart)
                OR
                (date_arrivee >= :date_arrivee AND date_depart <= :date_depart)
            )
        ");

            $stmt->execute([
                'habitation_id' => $habitation_id,
                'date_arrivee' => $date_arrivee,
                'date_depart' => $date_depart
            ]);

            return $stmt->fetch(PDO::FETCH_ASSOC)['count'] == 0;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la vérification de disponibilité: " . $e->getMessage());
        }
    }

    public function annulerReservation($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM reservations WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de l'annulation de la réservation: " . $e->getMessage());
        }
    }

    // --- GESTION DES CLIENTS ---

    public function listClients($search = null)
    {
        try {
            $sql = "
            SELECT u.*, 
                   COUNT(DISTINCT r.id) as total_reservations,
                   MAX(r.date_arrivee) as derniere_reservation
            FROM users u
            LEFT JOIN reservations r ON u.id = r.user_id
            WHERE u.is_admin = FALSE
        ";

            $params = [];

            if ($search) {
                $sql .= " AND (u.nom LIKE :search OR u.email LIKE :search OR u.telephone LIKE :search)";
                $params['search'] = "%$search%";
            }

            $sql .= " GROUP BY u.id ORDER BY u.nom";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des clients: " . $e->getMessage());
        }
    }

    public function getClient($id)
    {
        try {
            $stmt = $this->db->prepare("
            SELECT u.*,
                   COUNT(DISTINCT r.id) as total_reservations,
                   MAX(r.date_arrivee) as derniere_reservation
            FROM users u
            LEFT JOIN reservations r ON u.id = r.user_id
            WHERE u.id = :id AND u.is_admin = FALSE
            GROUP BY u.id
        ");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération du client: " . $e->getMessage());
        }
    }

    public function getClientReservations($user_id)
    {
        try {
            $stmt = $this->db->prepare("
            SELECT r.*, 
                   h.description as habitation_description,
                   h.loyer_jour,
                   DATEDIFF(r.date_depart, r.date_arrivee) as duree_sejour,
                   (DATEDIFF(r.date_depart, r.date_arrivee) * h.loyer_jour) as cout_total
            FROM reservations r
            JOIN habitations h ON r.habitation_id = h.id
            WHERE r.user_id = :user_id
            ORDER BY r.date_arrivee DESC
        ");
            $stmt->execute(['user_id' => $user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des réservations du client: " . $e->getMessage());
        }
    }

    public function toggleClientStatus($user_id, $active = true)
    {
        try {
            $stmt = $this->db->prepare("
            UPDATE users 
            SET active = :active 
            WHERE id = :id AND is_admin = FALSE
        ");
            return $stmt->execute([
                'id' => $user_id,
                'active' => $active
            ]);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la modification du statut du client: " . $e->getMessage());
        }
    }
}
