<?php
require_once __DIR__ . '/View.php';

class Controller {
    protected $user;
    protected $role;
    
    public function __construct() {
        // Démarrer la session si elle n'est pas déjà démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Récupérer les informations utilisateur depuis la session
        $this->user = $_SESSION['user'] ?? null;
        $this->role = $_SESSION['role'] ?? null;
    }
    
    /**
     * Vérifie si l'utilisateur est connecté
     */
    protected function requireAuth() {
        if (!$this->user || !$this->role) {
            View::redirect('/GestionDesStagesProject/NewApp/public/index.php/auth/connexion');
        }
    }
    
    /**
     * Vérifie si l'utilisateur a un rôle spécifique
     */
    protected function requireRole($requiredRole) {
        $this->requireAuth();
        
        if ($this->role !== $requiredRole) {
            View::setFlash('error', 'Accès non autorisé');
            View::redirect('/GestionDesStagesProject/NewApp/public/index.php/dashboard');
        }
    }
    
    /**
     * Vérifie si l'utilisateur a l'un des rôles spécifiés
     */
    protected function requireOneOfRoles($allowedRoles) {
        $this->requireAuth();
        
        if (!in_array($this->role, $allowedRoles)) {
            View::setFlash('error', 'Accès non autorisé');
            View::redirect('/GestionDesStagesProject/NewApp/public/index.php/dashboard');
        }
    }
    
    /**
     * Rend une vue avec les données de base
     */
    protected function render($view, $data = []) {
        // Ajouter les données utilisateur de base
        $baseData = [
            'user' => $this->user,
            'role' => $this->role,
        ];
        
        // Fusionner avec les données spécifiques
        $allData = array_merge($baseData, $data);
        
        View::render($view, $allData);
    }
    
    /**
     * Valide les données POST
     */
    protected function validatePost($requiredFields) {
        $errors = [];
        
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                $errors[] = "Le champ '$field' est requis";
            }
        }
        
        return $errors;
    }
    
    /**
     * Nettoie les données d'entrée
     */
    protected function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Redirige avec un message flash
     */
    protected function redirectWithMessage($url, $type, $message) {
        View::setFlash($type, $message);
        View::redirect($url);
    }
    
    /**
     * Retourne une réponse JSON
     */
    protected function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
?> 