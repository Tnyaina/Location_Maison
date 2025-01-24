<?php

use app\controllers\UserController;
use app\controllers\AdminController;
use app\controllers\ReservationsController;
use app\controllers\ClientsController;
use app\controllers\PropertyController;
use app\controllers\ProductController;
use flight\Engine;
use flight\net\Router;
// use Flight;

// user
Flight::route('GET /', function() {
    $controller = new UserController();
    $controller->showAuthPage();
});

Flight::route('POST /login', function() {
    $controller = new UserController();
    $controller->login();
});

Flight::route('POST /inscription', function() {
    $controller = new UserController();
    $controller->register();
});

Flight::route('GET /logout', function() {
    $controller = new UserController();
    $controller->logout();
});

// admin 
Flight::route('GET /admin', function() {
    $controller = new AdminController();
    $controller->index();
});

// Routes admin supplémentaires
Flight::route('POST /admin/ajouter-habitation', function() {
    $controller = new AdminController();
    $controller->ajouterHabitation();
});

Flight::route('POST /admin/modifier-habitation', function() {
    $controller = new AdminController();
    $controller->modifierHabitation();
});

Flight::route('POST /admin/supprimer-habitation', function() {
    $controller = new AdminController();
    $controller->supprimerHabitation();
});

Flight::route('GET /admin/habitation/@id', function($id) {
    $controller = new AdminController();
    $controller->getHabitation($id);
});

Flight::route('GET /admin/detail/@id', function($id) {
    $controller = new AdminController();
    $controller->detail($id);
});

// Routes pour les types d'habitation
Flight::route('GET /admin/types', function() {
    $controller = new AdminController();
    $controller->listTypes();
});

Flight::route('POST /admin/types/ajouter', function() {
    $controller = new AdminController();
    $controller->ajouterType();
});

Flight::route('POST /admin/types/modifier', function() {
    $controller = new AdminController();
    $controller->modifierType();
});

Flight::route('POST /admin/types/supprimer', function() {
    $controller = new AdminController();
    $controller->supprimerType();
});

// Routes pour les réservations
Flight::route('GET /admin/reservations', function() {
    $controller = new ReservationsController();
    $controller->index();
});

Flight::route('GET /admin/reservations/@id', function($id) {
    $controller = new ReservationsController();
    $controller->detail($id);
});

Flight::route('POST /admin/reservations/annuler', function() {
    $controller = new ReservationsController();
    $controller->annuler();
});

Flight::route('POST /admin/reservations/check-disponibilite', function() {
    $controller = new ReservationsController();
    $controller->checkDisponibilite();
});

Flight::route('POST /reservation/create', function() {
    $controller = new PropertyController();
    $controller->createReservation();
});

// Routes pour les clients
Flight::route('GET /admin/clients', function() {
    $controller = new ClientsController();
    $controller->index();
});

Flight::route('GET /admin/clients/@id', function($id) {
    $controller = new ClientsController();
    $controller->detail($id);
});

Flight::route('POST /admin/clients/toggle-status', function() {
    $controller = new ClientsController();
    $controller->toggleStatus();
});

Flight::route('GET /accueil', function() {
    $controller = new PropertyController();
    $controller->index();
});

Flight::route('GET /property/@id', function($id) {
    $controller = new ProductController();
    $controller->details($id);
});