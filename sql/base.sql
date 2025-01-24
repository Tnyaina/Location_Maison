-- Création de la base de données
CREATE DATABASE IF NOT EXISTS location_saisonniere;
USE location_saisonniere;

-- Table des utilisateurs (clients et admin)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL,
    nom VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE
);

-- Table des types d'habitation
CREATE TABLE types_habitation (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL
);

-- Table des habitations
CREATE TABLE habitations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type_id INT NOT NULL,
    nb_chambres INT NOT NULL,
    loyer_jour DECIMAL(10,2) NOT NULL,
    quartier VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    FOREIGN KEY (type_id) REFERENCES types_habitation(id)
);

-- Table des photos
CREATE TABLE photos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    habitation_id INT NOT NULL,
    url_photo VARCHAR(255) NOT NULL,
    FOREIGN KEY (habitation_id) REFERENCES habitations(id)
);

-- Table des réservations
CREATE TABLE reservations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    habitation_id INT NOT NULL,
    user_id INT NOT NULL,
    date_arrivee DATE NOT NULL,
    date_depart DATE NOT NULL,
    FOREIGN KEY (habitation_id) REFERENCES habitations(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insertion des types d'habitation de base
INSERT INTO types_habitation (nom) VALUES
('Maison'),
('Studio'),
('Appartement');

-- Ajouter une colonne 'active' à la table users
ALTER TABLE users ADD COLUMN active BOOLEAN DEFAULT TRUE;

-- Ajout d'index pour améliorer les performances
ALTER TABLE reservations ADD INDEX idx_dates (date_arrivee, date_depart);
ALTER TABLE reservations ADD INDEX idx_habitation (habitation_id);
ALTER TABLE reservations ADD INDEX idx_user (user_id);