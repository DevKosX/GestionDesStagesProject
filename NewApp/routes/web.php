<?php
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/MessageController.php';
require_once __DIR__ . '/../app/controllers/DocumentController.php';

// Détecte le chemin réel de la requête, sans le chemin de base ni /index.php
$uri = $_SERVER['REQUEST_URI'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);

// Extraire seulement le chemin, sans les paramètres GET
$uriParts = parse_url($uri);
$path = preg_replace('#^' . preg_quote($scriptName) . '(?:/index\.php)?#', '', $uriParts['path']);

if ($path === '/login') {
    (new AuthController())->login();
} elseif ($path === '/logout') {
    (new AuthController())->logout();
} elseif ($path === '/dashboard' || $path === '' || $path === '/') {
    (new DashboardController())->index();
} elseif ($path === '/messages') {
    (new MessageController())->index();
} elseif ($path === '/messages/envoyer') {
    (new MessageController())->envoyer();
} elseif ($path === '/messages/repondre') {
    (new MessageController())->repondre();
} elseif ($path === '/documents') {
    (new DocumentController())->index();
} elseif ($path === '/documents/upload') {
    (new DocumentController())->upload();
} elseif ($path === '/documents/download') {
    (new DocumentController())->download();
} else {
    echo 'Page non trouvée';
} 