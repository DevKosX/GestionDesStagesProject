<?php

class Router {
    private $routes = [];
    private $basePath = '';
    
    public function __construct() {
        $this->detectBasePath();
    }
    
    /**
     * Détecte automatiquement le chemin de base de l'application
     * Fonctionne en local et en production
     */
    private function detectBasePath() {
        // Récupérer le chemin du script actuel
        $scriptPath = $_SERVER['SCRIPT_NAME'];
        
        // Le script est dans public/index.php, donc remonter au dossier parent
        $appDir = dirname(dirname($scriptPath));
        
        // Si on est à la racine, pas de préfixe
        if ($appDir === '/' || $appDir === '\\') {
            $this->basePath = '';
        } else {
            $this->basePath = $appDir;
        }
    }
    
    /**
     * Ajoute une route
     */
    public function addRoute($path, $controller, $method = 'index') {
        $this->routes[$path] = [
            'controller' => $controller,
            'method' => $method
        ];
    }
    
    /**
     * Résout la route actuelle
     */
    public function resolve() {
        $requestUri = $_SERVER['REQUEST_URI'];
        
        // Enlever les paramètres GET
        $path = parse_url($requestUri, PHP_URL_PATH);
        
        // Enlever le chemin de base
        if ($this->basePath && strpos($path, $this->basePath) === 0) {
            $path = substr($path, strlen($this->basePath));
        }
        
        // Si on est dans /public/, enlever /public du chemin
        if (strpos($path, '/public') === 0) {
            $path = substr($path, 7); // 7 = longueur de "/public"
        }
        
        // Nettoyer le chemin
        $path = '/' . trim($path, '/');
        if ($path === '/') {
            $path = '';
        }
        
        // Chercher la route correspondante
        if (isset($this->routes[$path])) {
            $route = $this->routes[$path];
            $controllerName = $route['controller'];
            $methodName = $route['method'];
            
            // Instancier et appeler le contrôleur
            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller, $methodName)) {
                    return $controller->$methodName();
                }
            }
        }
        
        // Route non trouvée - redirection vers l'accueil
        http_response_code(404);
        header('Location: ' . $this->getBaseUrl());
        exit;
    }
    
    /**
     * Génère une URL absolue pour l'application
     */
    public function url($path = '') {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $cleanPath = ltrim($path, '/');
        
        return $protocol . '://' . $host . $this->basePath . '/' . $cleanPath;
    }
    
    /**
     * Génère une URL pour les assets
     */
    public function asset($path = '') {
        return $this->url('public/assets/' . ltrim($path, '/'));
    }
    
    /**
     * Getter pour le chemin de base
     */
    public function getBasePath() {
        return $this->basePath;
    }
    
    /**
     * Génère l'URL de base de l'application
     */
    public function getBaseUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        return $protocol . '://' . $host . $this->basePath;
    }
}
?> 