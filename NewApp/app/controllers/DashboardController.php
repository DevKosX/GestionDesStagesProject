<?php
require_once __DIR__ . '/../core/View.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Message.php';
require_once __DIR__ . '/../models/Document.php';
require_once __DIR__ . '/../models/Evenement.php';

class DashboardController {
    public function index() {
        if (!isset($_SESSION['user'], $_SESSION['role'])) {
            header('Location: login');
            exit;
        }
        
        $role = $_SESSION['role'];
        $user = $_SESSION['user'];
        $user_id = $user->Id;
        
        // Récupérer les données pour le dashboard
        $messages_recents = array_slice(Message::messagesRecus($user_id), 0, 5);
        $documents_recents = array_slice(Document::getDocumentsRecus($user_id), 0, 5);
        $evenements_prochains = Evenement::getEvenementsProchains($user_id, $role, 5);
        $tous_les_users = Document::getAllUsers();
        
        // Statistiques
        $total_messages_recus = count(Message::messagesRecus($user_id));
        $total_messages_envoyes = count(Message::messagesEnvoyes($user_id));
        $total_documents_recus = count(Document::getDocumentsRecus($user_id));
        $total_documents_envoyes = count(Document::getDocumentsEnvoyes($user_id));
        
        $data = [
            'user' => $user,
            'role' => $role,
            'messages_recents' => $messages_recents,
            'documents_recents' => $documents_recents,
            'evenements_prochains' => $evenements_prochains,
            'tous_les_users' => $tous_les_users,
            'stats' => [
                'messages_recus' => $total_messages_recus,
                'messages_envoyes' => $total_messages_envoyes,
                'documents_recus' => $total_documents_recus,
                'documents_envoyes' => $total_documents_envoyes
            ]
        ];
        
        // Utiliser le même template pour tous les rôles
        View::render('common/dashboard', $data);
    }
} 