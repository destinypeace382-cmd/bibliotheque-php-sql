# La Bibliothèque

Projet de bibliothèque en ligne réalisé en **PHP natif, MySQL, HTML, CSS et JavaScript**.

Le projet permet d'afficher dynamiquement un catalogue de livres provenant d'une base de données MySQL et fournit une interface d'administration permettant de gérer les livres et les catégories.

## Fonctionnalités

### Catalogue

- Affichage dynamique des livres depuis MySQL
- Recherche par titre ou auteur
- Filtrage par catégorie
- Tri par année croissante
- Tri par année décroissante
- Tri alphabétique par titre
- Compteur de résultats
- Système de favoris avec LocalStorage
- Affichage dynamique des images

### Administration des livres

- Liste des livres
- Ajout d'un livre
- Modification d'un livre
- Suppression d'un livre
- Confirmation avant suppression
- Upload d'images
- Pagination PHP
- Messages de confirmation après les opérations

### Administration des catégories

- Liste des catégories
- Ajout d'une catégorie
- Modification d'une catégorie
- Suppression d'une catégorie
- Protection contre la suppression d'une catégorie utilisée par un livre
- Messages de confirmation

## Sécurité

Le projet intègre plusieurs mesures de sécurité :

- Requêtes préparées avec PDO
- Validation des données côté serveur
- Échappement des données avec `htmlspecialchars()`
- Protection CSRF sur les formulaires d'administration
- Validation des identifiants
- Vérification des fichiers uploadés
- Limitation des images à 5 Mo
- Formats d'image autorisés : JPG, PNG et WEBP
- Suppression des données via requêtes POST

## Technologies utilisées

- HTML5
- CSS3
- JavaScript
- PHP natif
- MySQL
- PDO
- XAMPP
- Git
- GitHub

## Structure du projet

```text
bibliotheque/
│
├── admin/
│   ├── index.php
│   ├── livre-ajouter.php
│   ├── livre-modifier.php
│   ├── livre-supprimer.php
│   ├── categories.php
│   ├── categorie-ajouter.php
│   ├── categorie-modifier.php
│   └── categorie-supprimer.php
│
├── config/
│   └── db.php
│
├── includes/
│   └── csrf.php
│
├── uploads/
│
├── index.php
├── script.js
├── style.css
├── schema.sql
└── README.md
```

## Installation

### 1. Installer XAMPP

Installer XAMPP puis démarrer :

- Apache
- MySQL

### 2. Placer le projet

Placer le dossier du projet dans :

```text
C:\xampp\htdocs\bibliotheque
```

### 3. Créer la base de données

Ouvrir phpMyAdmin :

```text
http://localhost/phpmyadmin/
```

Importer le fichier :

```text
schema.sql
```

Ce fichier permet de créer la base de données ainsi que les tables :

- `categories`
- `livres`

Il contient également les 6 catégories initiales :

- Roman
- Science-fiction
- Jeunesse
- Bande dessinée
- Documentaire
- Poésie

ainsi que les 8 livres du catalogue initial.

### 4. Configurer la connexion

Le fichier de connexion se trouve dans :

```text
config/db.php
```

Configurer les informations de connexion MySQL si nécessaire.

Exemple avec la configuration XAMPP par défaut :

```php
$host = "localhost";
$dbname = "bibliotheque";
$username = "root";
$password = "";
```

### 5. Lancer le catalogue

Dans le navigateur :

```text
http://localhost/bibliotheque/
```

### 6. Accéder à l'administration

Dans le navigateur :

```text
http://localhost/bibliotheque/admin/
```

L'interface d'administration permet de gérer les livres et les catégories.

## Base de données

### Table `categories`

- `id` : clé primaire
- `nom` : nom de la catégorie

### Table `livres`

- `id` : clé primaire
- `titre` : titre du livre
- `auteur` : auteur du livre
- `categorie_id` : clé étrangère vers `categories`
- `annee` : année de publication
- `image` : chemin de l'image

La relation entre les tables est :

```text
categories
    |
    | 1
    |
    | N
    |
livres
```

Une catégorie peut donc être associée à plusieurs livres.

## CRUD

L'administration utilise les opérations CRUD :

- **Create** : ajouter un livre ou une catégorie
- **Read** : afficher les livres et les catégories
- **Update** : modifier un livre ou une catégorie
- **Delete** : supprimer un livre ou une catégorie

Toutes les requêtes utilisant des données provenant de l'utilisateur sont exécutées avec des requêtes préparées PDO.

## Upload des images

Les images envoyées depuis l'administration sont stockées dans :

```text
uploads/
```

Le chemin du fichier est ensuite enregistré dans la base de données.

Les formats acceptés sont :

- JPG / JPEG
- PNG
- WEBP

La taille maximale autorisée est de **5 Mo**.

## Bonus réalisés

Les fonctionnalités bonus suivantes ont également été ajoutées :

- Pagination PHP dans l'administration
- Protection CSRF des formulaires
- Messages flash après ajout, modification ou suppression

## Auteur

Projet réalisé dans le cadre d'un exercice pratique PHP / SQL sur la persistance des données et la gestion d'une bibliothèque en ligne.