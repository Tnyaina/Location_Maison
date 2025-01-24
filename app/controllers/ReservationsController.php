<?php
namespace app\controllers;

use app\models\AdminModel;
use Flight;

class ReservationsController {
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
        }
    
        $filters = [
            'habitation_id' => $_GET['habitation_id'] ?? null,
            'date_debut' => $_GET['date_debut'] ?? null,
            'date_fin' => $_GET['date_fin'] ?? null
        ];
    
        $data = [
            'reservations' => $this->adminModel->listReservations($filters),
            'habitations' => $this->adminModel->listHabitations(),
            'filters' => $filters,
            'currentPage' => 'reservations'
        ];
    
        // Rend la vue spécifique et injecte dans la clé 'content'
        Flight::render('admin/reservations/index', $data, 'content');
    
        // Rend le layout principal
        Flight::render('layouts/layout', [
            'currentPage' => 'reservations'
        ]);
    }
    

    public function detail($id) {
        if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
            Flight::redirect('/');
        }
    
        $reservation = $this->adminModel->getReservation($id);
        if (!$reservation) {
            $_SESSION['error'] = "Réservation introuvable";
            Flight::redirect('/admin/reservations');
        }
    
        Flight::render('admin/reservations/detail', [
            'reservation' => $reservation,
            'currentPage' => 'reservations'
        ], 'content');
    
        Flight::render('layouts/layout', [
            'currentPage' => 'reservations'
        ]);
    }
    

    public function annuler() {
        if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
            Flight::json(['error' => 'Non autorisé'], 403);
            return;
        }

        $data = json_decode(Flight::request()->getBody(), true);
        $id = $data['id'] ?? null;

        if (!$id) {
            Flight::json(['error' => 'ID de réservation manquant'], 400);
            return;
        }

        try {
            $this->adminModel->annulerReservation($id);
            Flight::json(['success' => true]);
        } catch (\Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }

    public function checkDisponibilite() {
        if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
            Flight::json(['error' => 'Non autorisé'], 403);
            return;
        }

        $data = json_decode(Flight::request()->getBody(), true);
        
        try {
            $disponible = $this->adminModel->checkDisponibilite(
                $data['habitation_id'],
                $data['date_arrivee'],
                $data['date_depart']
            );
            Flight::json(['disponible' => $disponible]);
        } catch (\Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }
}