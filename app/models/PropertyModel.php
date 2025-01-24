<?php

namespace app\models;

use PDO;
use Exception;

class PropertyModel {
    private $db;

    public function __construct(PDO $database) {
        $this->db = $database;
    }

    public function getAllTypes() {
        try {
            $stmt = $this->db->query("SELECT * FROM types_habitation ORDER BY nom");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des types: " . $e->getMessage());
        }
    }

    public function searchProperties($filters = []) {
        try {
            $sql = "
                SELECT h.*, t.nom as type_nom,
                (SELECT url_photo FROM photos WHERE habitation_id = h.id LIMIT 1) as photo_principale
                FROM habitations h 
                JOIN types_habitation t ON h.type_id = t.id
                WHERE 1=1
            ";
            $params = [];

            if (!empty($filters['description'])) {
                $sql .= " AND h.description LIKE :description";
                $params['description'] = '%' . $filters['description'] . '%';
            }

            // Filtre par type
            if (!empty($filters['type_id'])) {
                $sql .= " AND h.type_id = :type_id";
                $params['type_id'] = $filters['type_id'];
            }

            // Filtre par prix
            if (!empty($filters['prix_min'])) {
                $sql .= " AND h.loyer_jour >= :prix_min";
                $params['prix_min'] = $filters['prix_min'];
            }
            if (!empty($filters['prix_max'])) {
                $sql .= " AND h.loyer_jour <= :prix_max";
                $params['prix_max'] = $filters['prix_max'];
            }

            // Filtre par nombre de chambres
            if (!empty($filters['nb_chambres'])) {
                if ($filters['nb_chambres'] === '3+') {
                    $sql .= " AND h.nb_chambres >= 3";
                } else {
                    $sql .= " AND h.nb_chambres = :nb_chambres";
                    $params['nb_chambres'] = $filters['nb_chambres'];
                }
            }

            $sql .= " ORDER BY h.loyer_jour ASC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la recherche des propriétés: " . $e->getMessage());
        }
    }

    public function getPropertyCountByType($typeId) {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM habitations WHERE type_id = :type_id");
            $stmt->execute(['type_id' => $typeId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'];
        } catch (Exception $e) {
            throw new Exception("Erreur lors du comptage des propriétés par type: " . $e->getMessage());
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
}