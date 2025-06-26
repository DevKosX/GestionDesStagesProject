<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Notification.php';

class NotificationController extends Controller {
    
    /**
     * Marquer une section comme vue (appelé via AJAX)
     */
    public function marquerVue() {
        $this->requireAuth();
        
        // Vérifier que c'est une requête POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée']);
            return;
        }
        
        // Récupérer les données JSON
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['section'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Section manquante']);
            return;
        }
        
        $section = $input['section'];
        $user_id = $this->user->Id;
        
        // Sections valides
        $sections_valides = ['messagerie', 'suivi-stages', 'documents', 'calendrier', 'dashboard'];
        
        if (!in_array($section, $sections_valides)) {
            http_response_code(400);
            echo json_encode(['error' => 'Section invalide']);
            return;
        }
        
        // Marquer la section comme vue
        $success = Notification::marquerSectionVue($user_id, $section);
        
        if ($success) {
            // Retourner les nouvelles statistiques
            $nouvelles_stats = Notification::getStatsNotifications($user_id, $this->role);
            
            echo json_encode([
                'success' => true,
                'message' => 'Section marquée comme vue',
                'stats' => $nouvelles_stats
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la mise à jour']);
        }
    }
    
    /**
     * Obtenir les statistiques de notification actuelles
     */
    public function getStats() {
        $this->requireAuth();
        
        $user_id = $this->user->Id;
        $stats = Notification::getStatsNotifications($user_id, $this->role);
        
        echo json_encode(['stats' => $stats]);
    }
}
?> 