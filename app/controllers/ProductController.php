<?php

namespace app\controllers;

use app\models\PropertyModel;
use Flight;
use Exception;

class ProductController {
    private $propertyModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->propertyModel = new PropertyModel(Flight::db());
    }

    public function details($id) {
        if (!isset($_SESSION['user'])) {
            Flight::redirect('/');
            return;
        }
        try {
            $property = $this->propertyModel->getHabitation($id);
            if (!$property) {
                Flight::redirect('/accueil');
            }

            $propertyTypes = $this->propertyModel->getAllTypes();
            $typesCount = [];
            
            foreach ($propertyTypes as $type) {
                $count = $this->propertyModel->getPropertyCountByType($type['id']);
                $typesCount[$type['nom']] = [
                    'count' => $count,
                    'icon' => 'icon-villa.png'
                ];
            }

            $navItems = [
                'Accueil' => 'accueil'
            ];

            Flight::render('user/details-product', [
                'property' => $property
            ], 'content');

            Flight::render('layouts/index', [
                'navItems' => $navItems,
                'title' => $property['title'] ?? 'Détails de la propriété',
                'propertyTypes' => $typesCount
            ]);

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            Flight::redirect('/accueil');
        }
    }
}