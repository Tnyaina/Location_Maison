<?php

namespace app\controllers;

use app\models\AdminModel;
use Flight;

class AdminController {
    private $adminModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->adminModel = new AdminModel(Flight::db());
    }

    public function index() {
        if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
            Flight::redirect('/');
            return;
        }
    
        $habitations = $this->adminModel->listHabitations();
        $types = $this->adminModel->getTypes();
        
        // Le contenu de index.php sera injecté dans $content du layout
        Flight::render('admin/index', [
            'habitations' => $habitations,
            'types' => $types,
            'currentPage' => 'home'  // Pour mettre en surbrillance le lien actif
        ], 'content');
        
        // Rendre le layout avec le contenu
        Flight::render('layouts/layout', [
            'currentPage' => 'home'
        ]);
    }

    public function ajouterHabitation() {
        if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
            Flight::redirect('/');
            return;
        }

        $data = [
            'type_id' => Flight::request()->data->type_id,
            'nb_chambres' => Flight::request()->data->nb_chambres,
            'loyer_jour' => Flight::request()->data->loyer_jour,
            'quartier' => Flight::request()->data->quartier,
            'description' => Flight::request()->data->description
        ];

        try {
            $habitation_id = $this->adminModel->ajouterHabitation($data);

            // Gestion des photos
            if (isset($_FILES['photos'])) {
                $upload_dir = 'uploads/habitations/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['photos']['error'][$key] === UPLOAD_ERR_OK) {
                        $filename = uniqid() . '_' . $_FILES['photos']['name'][$key];
                        $filepath = $upload_dir . $filename;
                        
                        if (move_uploaded_file($tmp_name, $filepath)) {
                            $this->adminModel->ajouterPhoto($habitation_id, $filepath);
                        }
                    }
                }
            }

            Flight::redirect('/admin');
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            Flight::redirect('/admin');
        }
    }

    public function modifierHabitation() {
        if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
            Flight::redirect('/');
            return;
        }
    
        $id = Flight::request()->data->id;
        $data = [
            'type_id' => Flight::request()->data->type_id,
            'nb_chambres' => Flight::request()->data->nb_chambres,
            'loyer_jour' => Flight::request()->data->loyer_jour,
            'quartier' => Flight::request()->data->quartier,
            'description' => Flight::request()->data->description
        ];
    
        try {
            $this->adminModel->modifierHabitation($id, $data);
    
            // Gestion des photos
            if (isset($_FILES['photos']) && !empty($_FILES['photos']['name'][0])) {
                $upload_dir = 'uploads/habitations/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
    
                $nouvelles_photos = [];
                foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['photos']['error'][$key] === UPLOAD_ERR_OK) {
                        $filename = uniqid() . '_' . $_FILES['photos']['name'][$key];
                        $filepath = $upload_dir . $filename;
                        
                        if (move_uploaded_file($tmp_name, $filepath)) {
                            $nouvelles_photos[] = $filepath;
                        }
                    }
                }
    
                // Utiliser la méthode remplacerPhotos pour gérer le remplacement
                if (!empty($nouvelles_photos)) {
                    $this->adminModel->remplacerPhotos($id, $nouvelles_photos);
                }
            }
    
            Flight::redirect('/admin');
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            Flight::redirect('/admin');
        }
    }

    public function supprimerHabitation() {
        if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
            Flight::redirect('/');
            return;
        }

        $id = Flight::request()->data->id;
        
        try {
            $this->adminModel->supprimerHabitation($id);
            Flight::redirect('/admin');
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            Flight::redirect('/admin');
        }
    }

    public function getHabitation($id) {
        if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
            Flight::json(['error' => 'Non autorisé'], 403);
            return;
        }
    
        try {
            $habitation = $this->adminModel->getHabitation($id);
            if (!$habitation) {
                Flight::json(['error' => 'Habitation non trouvée'], 404);
                return;
            }
            Flight::json($habitation);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }

    public function detail($id) {
        if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
            Flight::redirect('/');
            return;
        }
    
        try {
            $habitation = $this->adminModel->getHabitation($id);
            if (!$habitation) {
                Flight::redirect('/admin');
                return;
            }
            
            // Rendre la vue detail.php dans le content
            Flight::render('admin/detail', [
                'habitation' => $habitation,
                'currentPage' => 'home'
            ], 'content');
            
            // Rendre le layout avec le content
            Flight::render('layouts/layout', [
                'currentPage' => 'home'
            ]);
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            Flight::redirect('/admin');
        }
    }

    public function listTypes() {
        if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
            Flight::redirect('/');
            return;
        }
    
        try {
            $types = $this->adminModel->getTypesWithCount();
            
            Flight::render('admin/types', [
                'types' => $types,
                'currentPage' => 'types'
            ], 'content');
            
            Flight::render('layouts/layout', [
                'currentPage' => 'types'
            ]);
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            Flight::redirect('/admin');
        }
    }
    
    public function ajouterType() {
        if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
            Flight::redirect('/');
            return;
        }
    
        $nom = Flight::request()->data->nom;
        
        try {
            $this->adminModel->ajouterType($nom);
            $_SESSION['success'] = "Type d'habitation ajouté avec succès";
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        Flight::redirect('/admin/types');
    }
    
    public function modifierType() {
        if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
            Flight::redirect('/');
            return;
        }
    
        $id = Flight::request()->data->id;
        $nom = Flight::request()->data->nom;
        
        try {
            $this->adminModel->modifierType($id, $nom);
            $_SESSION['success'] = "Type d'habitation modifié avec succès";
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        Flight::redirect('/admin/types');
    }
    
    public function supprimerType() {
        if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
            Flight::redirect('/');
            return;
        }
    
        $id = Flight::request()->data->id;
        
        try {
            $this->adminModel->supprimerType($id);
            $_SESSION['success'] = "Type d'habitation supprimé avec succès";
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        Flight::redirect('/admin/types');
    }

    // Dans AdminController.php
public function supprimerPhoto() {
    if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
        Flight::json(['error' => 'Non autorisé'], 403);
        return;
    }

    $photo_id = Flight::request()->data->photo_id;
    
    try {
        $this->adminModel->supprimerPhoto($photo_id);
        Flight::json(['success' => true]);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
}
}