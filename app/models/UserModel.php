<?php

namespace app\models;

use PDO;
use Exception;

class UserModel
{
    private $db;

    public function __construct(PDO $database)
    {
        $this->db = $database;
    }

    public function create($email, $nom, $password, $telephone, $is_admin = false)
    {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $this->db->prepare(
                "INSERT INTO users (email, nom, password, telephone, is_admin) 
                 VALUES (:email, :nom, :password, :telephone, :is_admin)"
            );

            $stmt->execute([
                'email' => $email,
                'nom' => $nom,
                'password' => $hashed_password,
                'telephone' => $telephone,
                'is_admin' => $is_admin
            ]);

            return $this->db->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la création de l'utilisateur: " . $e->getMessage());
        }
    }

    public function getUserConnecte($email, $password)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                unset($user['password']);
                return $user;
            }
            return false;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la connexion: " . $e->getMessage());
        }
    }

    public function emailExists($email)
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la vérification de l'email: " . $e->getMessage());
        }
    }

    public function estAdmin($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT is_admin FROM users WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return (bool)$stmt->fetchColumn();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la vérification du statut admin: " . $e->getMessage());
        }
    }
}