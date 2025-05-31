<?php
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';

// Détecte le chemin réel de la requête, sans le chemin de base ni /index.php
$uri = $_SERVER['REQUEST_URI'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$path = preg_replace('#^' . preg_quote($scriptName) . '(?:/index\.php)?#', '', $uri);

if ($path === '/login') {
    (new AuthController())->login();
} elseif ($path === '/logout') {
    (new AuthController())->logout();
} elseif ($path === '/dashboard') {
    (new DashboardController())->index();
} else {
    echo 'Page non trouvée';
} 