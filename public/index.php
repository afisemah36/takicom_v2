<?php

/**
 * Point d'entrée de l'application
 * Takicom V2 - Front Controller
 */

// Démarrage de la session
session_start();

// Définition des constantes
define('ROOT', dirname(__DIR__));
define('APP', ROOT . '/app');
define('CORE', ROOT . '/core');
define('CONFIG', ROOT . '/config');
define('PUBLIC_PATH', ROOT . '/public');

// Chargement automatique des classes
spl_autoload_register(function ($class) {
    $paths = [
        CORE . '/' . $class . '.php',
        APP . '/Controllers/' . $class . '.php',
        APP . '/Models/' . $class . '.php',
        APP . '/Middlewares/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Chargement des helpers
require_once ROOT . '/helpers/helpers.php';

// Chargement de la configuration
$config = require_once CONFIG . '/app.php';
$dbConfig = require_once CONFIG . '/database.php';

// Initialisation de la base de données
Database::init($dbConfig);

// Création de l'objet Request
$request = new Request();

// Création du routeur
$router = new Router($request);

// Chargement des routes
require_once CONFIG . '/routes.php';
// Exécution du routeur
$router->resolve();
