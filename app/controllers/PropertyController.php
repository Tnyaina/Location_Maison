<?php

namespace app\controllers;

use app\models\PropertyModel;
use app\models\AdminModel;
use Flight;
use \Exception;

class PropertyController {
    private $propertyModel;
    private $adminModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    $this->adminModel = new AdminModel(Flight::db());
    $this->propertyModel = new PropertyModel(Flight::db());
    }

    public function index() {
        if (!isset($_SESSION['user'])) {
            Flight::redirect('/');
            return;
        }
        try {
            $filters = [
                'type_id' => Flight::request()->query->type_id,
                'prix_min' => Flight::request()->query->prix_min,
                'prix_max' => Flight::request()->query->prix_max,
                'nb_chambres' => Flight::request()->query->nb_chambres,
                'description' => Flight::request()->query->description
            ];
    
            $properties = $this->propertyModel->searchProperties($filters);
            $types = $this->propertyModel->getAllTypes();
            
            $navItems = [
                'Accueil' => 'accueil'
            ];
            
            $propertyTypes = [];
            $icons = [
                'Appartement' => 'icon-apartment.png',
                'Studio' => 'icon-villa.png',
                'Maison' => 'icon-house.png',
                'Office' => 'icon-housing.png'
            ];
            
            foreach ($types as $type) {
                $propertyTypes[$type['nom']] = [
                    'icon' => $icons[$type['nom']] ?? 'default-icon.png',
                    'count' => $this->propertyModel->getPropertyCountByType($type['id'])
                ];
            }
    
            // Rendre d'abord search-property
            Flight::render('user/search-property', [
                'properties' => $properties,
                'types' => $types,
                'filters' => $filters
            ], 'content');
    
            // Puis rendre le template principal avec le contenu
            Flight::render('layouts/index', [
                'navItems' => $navItems,
                'propertyTypes' => $propertyTypes,
                'currentPage' => 'accueil'
            ]);
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            Flight::redirect('/');
        }
    }

    public function createReservation() {
        if (!isset($_SESSION['user'])) {
            Flight::redirect('/');
            return;
        }
    
        $data = [
            'habitation_id' => $_POST['habitation_id'],
            'user_id' => $_SESSION['user']['id'],
            'date_arrivee' => $_POST['date_arrivee'],
            'date_depart' => $_POST['date_depart']
        ];
    
        try {
            // Verify availability again server-side
            if ($this->adminModel->checkDisponibilite(
                $data['habitation_id'], 
                $data['date_arrivee'], 
                $data['date_depart']
            )) {
                $stmt = Flight::db()->prepare(
                    "INSERT INTO reservations (habitation_id, user_id, date_arrivee, date_depart) 
                    VALUES (:habitation_id, :user_id, :date_arrivee, :date_depart)"
                );
                $stmt->execute($data);
                $_SESSION['success'] = "Réservation effectuée avec succès";
            } else {
                $_SESSION['error'] = "Cette période n'est pas disponible";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Erreur lors de la réservation: " . $e->getMessage();
        }
        
        Flight::redirect('/property/' . $data['habitation_id']);
    }
    
}