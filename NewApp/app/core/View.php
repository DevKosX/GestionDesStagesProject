<?php
class View {
    /**
     * Rend une vue avec les données fournies
     */
    public static function render($view, $data = []) {
        // Inclure les helpers d'URL
        require_once __DIR__ . '/../helpers/url_helper.php';
        
        // Vérifier que la vue existe
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new Exception("Vue non trouvée : " . $view);
        }
        
        // Extraire les données en variables locales
        extract($data, EXTR_SKIP); // EXTR_SKIP évite d'écraser les variables existantes
        
        // Démarrer la capture de sortie
        ob_start();
        
        try {
            // Inclure la vue
            require $viewPath;
            
            // Récupérer le contenu et nettoyer le buffer
            $content = ob_get_clean();
            echo $content;
            
        } catch (Exception $e) {
            // Nettoyer le buffer en cas d'erreur
            ob_end_clean();
            throw $e;
        }
    }
    
    /**
     * Rend une vue partielle et retourne le contenu
     */
    public static function partial($view, $data = []) {
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            return "<!-- Vue partielle non trouvée : " . htmlspecialchars($view) . " -->";
        }
        
        extract($data, EXTR_SKIP);
        
        ob_start();
        require $viewPath;
        return ob_get_clean();
    }
    
    /**
     * Échappe les données HTML pour éviter les attaques XSS
     */
    public static function escape($data) {
        if (is_array($data)) {
            return array_map([self::class, 'escape'], $data);
        }
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Redirige vers une URL
     */
    public static function redirect($url, $statusCode = 302) {
        // Inclure les helpers d'URL
        require_once __DIR__ . '/../helpers/url_helper.php';
        
        // Si l'URL ne commence pas par http:// ou https://, utiliser le helper redirect
        if (!preg_match('#^https?://#', $url)) {
            redirect($url);
        } else {
            header("Location: " . $url, true, $statusCode);
            exit;
        }
    }
    
    /**
     * Définit un message flash pour la session suivante
     */
    public static function setFlash($type, $message) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Utiliser les noms de variables que les vues attendent
        $_SESSION[$type . '_message'] = $message;
    }
    
    /**
     * Récupère et supprime un message flash
     */
    public static function getFlash($type) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $key = $type . '_message';
        if (isset($_SESSION[$key])) {
            $message = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $message;
        }
        return null;
    }
} 