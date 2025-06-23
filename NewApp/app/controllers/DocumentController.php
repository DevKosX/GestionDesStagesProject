<?php
require_once __DIR__ . '/../models/Document.php';
require_once __DIR__ . '/../core/Controller.php';

class DocumentController extends Controller {
    public function index() {
        $this->requireAuth();        
        $user_id = $this->user->Id;
        $documents_envoyes = Document::getDocumentsEnvoyes($user_id);
        $documents_recus = Document::getDocumentsRecus($user_id);
        $users = Document::getAllUsers();
        
        $this->render('documents/index', [
            'documents_envoyes' => $documents_envoyes,
            'documents_recus' => $documents_recus,
            'users' => $users
        ]);
    }
    
    public function upload() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $expediteur_id = $this->user->Id;
            $destinataire_id = $_POST['destinataire_id'] ?? null;
            $titre = $_POST['titre'] ?? '';
            
            if (!isset($_FILES['document']) || $_FILES['document']['error'] !== UPLOAD_ERR_OK) {
                $this->redirectWithMessage('dashboard#documents', 'error', 'Aucun fichier sélectionné ou erreur lors de l\'upload');
            }
            
            if (!$destinataire_id || !$titre) {
                $this->redirectWithMessage('dashboard#documents', 'error', 'Veuillez remplir tous les champs');
            }
            
            $result = Document::upload($expediteur_id, $destinataire_id, $titre, $_FILES['document']);
            
            if ($result['success']) {
                $this->redirectWithMessage('dashboard#documents', 'success', $result['message']);
            } else {
                $this->redirectWithMessage('dashboard#documents', 'error', $result['error']);
            }
        }
        
        // Si pas de POST, rediriger vers le dashboard
        header('Location: dashboard#documents');
        exit;
    }
    
    public function download() {
        $this->requireAuth();
        
        $document_id = $_GET['id'] ?? null;
        $user_id = $this->user->Id;
        
        if (!$document_id) {
            $this->redirectWithMessage('dashboard#documents', 'error', 'Document non spécifié');
        }
        
        $result = Document::downloadDocument($document_id, $user_id);
        
        if (!$result['success']) {
            $this->redirectWithMessage('dashboard#documents', 'error', $result['error']);
        }
        
        // Forcer le téléchargement
        header('Content-Type: ' . $result['type']);
        header('Content-Disposition: attachment; filename="' . $result['filename'] . '"');
        header('Content-Length: ' . filesize($result['filepath']));
        
        readfile($result['filepath']);
        exit;
    }
}
?> 