CREATE DATABASE IF NOT EXISTS bibliotheque
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE bibliotheque;


-- =========================================
-- TABLE DES CATÉGORIES
-- =========================================

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE
);


-- =========================================
-- TABLE DES LIVRES
-- =========================================

CREATE TABLE livres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    auteur VARCHAR(255) NOT NULL,
    categorie_id INT NOT NULL,
    annee INT NOT NULL,
    image VARCHAR(255) NOT NULL,

    CONSTRAINT fk_livres_categories
        FOREIGN KEY (categorie_id)
        REFERENCES categories(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);


-- =========================================
-- CATÉGORIES
-- =========================================

INSERT INTO categories (nom) VALUES
('Roman'),
('Science-fiction'),
('Jeunesse'),
('Bande dessinée'),
('Documentaire'),
('Poésie');


-- =========================================
-- LIVRES
-- =========================================

INSERT INTO livres (titre, auteur, categorie_id, annee, image) VALUES
('Les Ombres du Fleuve', 'Camille Vasseur', 1, 2019, 'uploads/livre1.jpg'),
('Le Silence des Collines', 'Julien Marchand', 1, 2021, 'uploads/livre2.jpg'),
('Nébuleuse Écarlate', 'Sofia Kranz', 2, 2020, 'uploads/livre3.jpg'),
('Les Cités de Verre', 'Thomas Reyer', 2, 2022, 'uploads/livre4.jpg'),
('L''Île aux Lucioles', 'Nadia Ferrand', 3, 2018, 'uploads/livre5.jpg'),
('Le Royaume de Poche', 'Hugo Lenoir', 3, 2023, 'uploads/livre6.jpg'),
('Les Gardiens de la Brume', 'Élise Roussel', 4, 2021, 'uploads/livre7.jpg'),
('Chroniques du Vieux Port', 'Marc Aubin', 4, 2017, 'uploads/livre8.jpg');