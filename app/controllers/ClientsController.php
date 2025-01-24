<?php

namespace app\controllers;

use app\models\AdminModel;
use Flight;

class ClientsController {
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
    
        $search = isset($_GET['search']) ? $_GET['search'] : null;
        $clients = $this->adminModel->listClients($search);
    
        ob_start();
        Flight::render('admin/clients/index', [
            'clients' => $clients,
            'search' => $search
        ]);
        $content = ob_get_clean();
        
        Flight::render('layouts/layout', [
            'content' => $content,
            'currentPage' => 'clients'
        ]);
    }

    public function detail($id) {
        if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
            Flight::redirect('/');
            return;
        }

        $client = $this->adminModel->getClient($id);
        if (!$client) {
            Flight::redirect('/admin/clients');
            return;
        }

        $reservations = $this->adminModel->getClientReservations($id);

        ob_start();
        Flight::render('admin/clients/detail', [
            'client' => $client,
            'reservations' => $reservations
        ]);
        $content = ob_get_clean();

        Flight::render('layouts/layout', [
            'content' => $content,
            'currentPage' => 'clients'
        ]);
    }

    public function toggleStatus() {
        if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
            Flight::json(['error' => 'Non autorisé'], 403);
            return;
        }

        $id = Flight::request()->data->id;
        $active = Flight::request()->data->active === 'true';

        try {
            $this->adminModel->toggleClientStatus($id, $active);
            Flight::json(['success' => true]);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }
}