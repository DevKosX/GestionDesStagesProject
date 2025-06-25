<?php
// Routes de l'application - Architecture MVC portable
// Compatible local et production

// Charger les contrôleurs
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/MessageController.php';
require_once __DIR__ . '/../app/controllers/DocumentController.php';

// Charger le router
require_once __DIR__ . '/../app/core/Router.php';

// Créer l'instance du router
$router = new Router();

// === ROUTES D'AUTHENTIFICATION ===
$router->addRoute('/login', 'AuthController', 'login');
$router->addRoute('/connexion', 'AuthController', 'login');
$router->addRoute('/logout', 'AuthController', 'logout');
$router->addRoute('/deconnexion', 'AuthController', 'logout');

// === ROUTES DASHBOARD ===
$router->addRoute('', 'DashboardController', 'index');        // Page d'accueil
$router->addRoute('/', 'DashboardController', 'index');       // Page d'accueil
$router->addRoute('/dashboard', 'DashboardController', 'index');
$router->addRoute('/tableau-de-bord', 'DashboardController', 'index');
$router->addRoute('/public', 'DashboardController', 'index');  // Accès via /public/

// === ROUTES MESSAGERIE ===
$router->addRoute('/messages', 'MessageController', 'index');
$router->addRoute('/messagerie', 'MessageController', 'index');
$router->addRoute('/messages/envoyer', 'MessageController', 'envoyer');
$router->addRoute('/messages/repondre', 'MessageController', 'repondre');

// === ROUTES DOCUMENTS ===
$router->addRoute('/documents', 'DocumentController', 'index');
$router->addRoute('/documents/upload', 'DocumentController', 'upload');
$router->addRoute('/documents/download', 'DocumentController', 'download');

// Rendre le router disponible globalement pour les helpers
global $router;

// Résoudre la route actuelle
$router->resolve();
?> 