<?php

namespace app\controllers;

use app\models\UserModel;
use Flight;

class UserController
{
    private $userModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new UserModel(Flight::db());
    }

    public function showAuthPage()
    {
        // Récupération de tous les messages pour la page unique
        $data = [
            'login_error' => $_SESSION['login_error'] ?? null,
            'register_error' => $_SESSION['register_error'] ?? null,
            'success' => $_SESSION['success'] ?? null
        ];

        // Nettoyage des messages de session
        unset(
            $_SESSION['login_error'],
            $_SESSION['register_error'],
            $_SESSION['success']
        );

        Flight::render('user/login', $data);
    }

    public function login()
    {
        $email = Flight::request()->data->email;
        $password = Flight::request()->data->password;

        $user = $this->userModel->getUserConnecte($email, $password);

        if ($user) {
            $_SESSION['user'] = $user;
            if ($user['is_admin']) {
                Flight::redirect("/admin");
            } else {
                Flight::redirect("/accueil");
            }
        } else {
            $_SESSION['login_error'] = 'Email ou mot de passe incorrect';
            Flight::redirect('/');
        }
    }

    public function register()
    {
        $email = Flight::request()->data->email;
        $nom = Flight::request()->data->nom;
        $password = Flight::request()->data->password;
        $confirm_password = Flight::request()->data->confirmPassword;
        $telephone = Flight::request()->data->telephone;

        // Validation
        $errors = [];
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email invalide";
        }
        if (empty($nom)) {
            $errors[] = "Le nom est requis";
        }
        if (empty($telephone)) {
            $errors[] = "Le numéro de téléphone est requis";
        }
        if (strlen($password) < 8) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
        }
        if ($password !== $confirm_password) {
            $errors[] = "Les mots de passe ne correspondent pas";
        }
        if ($this->userModel->emailExists($email)) {
            $errors[] = "Cette adresse email est déjà utilisée";
        }

        if (!empty($errors)) {
            $_SESSION['register_error'] = $errors; // Maintenant on passe le tableau directement
            Flight::redirect('/');
            return;
        }

        $id = $this->userModel->create($email, $nom, $password, $telephone);
        if ($id) {
            $_SESSION['success'] = "Compte créé avec succès. Veuillez vous connecter.";
            Flight::redirect('/');
        } else {
            $_SESSION['register_error'] = ['Erreur lors de la création du compte'];
            Flight::redirect('/');
        }
    }

    public function showLoginForm()
    {
        Flight::render('user/login', [
            'error' => $_SESSION['login_error'] ?? null,
            'success' => $_SESSION['success'] ?? null
        ]);
        unset($_SESSION['login_error'], $_SESSION['success']);
    }

    public function showRegisterForm()
    {
        Flight::render('user/register', [
            'error' => $_SESSION['register_error'] ?? null
        ]);
        unset($_SESSION['register_error']);
    }

    public function logout()
    {
        session_destroy();
        Flight::redirect('/');
    }
}