<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Message.php';
require_once __DIR__ . '/../models/Document.php';
require_once __DIR__ . '/../models/Evenement.php';
require_once __DIR__ . '/../models/Action.php';

class DashboardController extends Controller {
    public function index() {
        // Vérifier que l'utilisateur est connecté
        $this->requireAuth();
        
        $user_id = $this->user->Id;
        
        // Récupérer les données pour le dashboard
        $messages_recents = array_slice(Message::messagesRecus($user_id), 0, 5);
        $documents_recents = array_slice(Document::getDocumentsRecus($user_id), 0, 5);
        $evenements_prochains = Evenement::getEvenementsProchains($user_id, $this->role, 10);
        $tous_les_users = Document::getAllUsers();
        
        // Statistiques
        $total_messages_recus = count(Message::messagesRecus($user_id));
        $total_messages_envoyes = count(Message::messagesEnvoyes($user_id));
        $total_documents_recus = count(Document::getDocumentsRecus($user_id));
        $total_documents_envoyes = count(Document::getDocumentsEnvoyes($user_id));
        
        $data = [
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
        
        // Utiliser la méthode render de la classe parent
        $this->render('common/dashboard', $data);
    }
} 