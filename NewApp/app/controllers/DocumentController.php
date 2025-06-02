<?php
require_once __DIR__ . '/../models/Document.php';
require_once __DIR__ . '/../core/View.php';

class DocumentController {
    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: /GestionDesStagesProject/NewApp/public/index.php/login');
            exit;
        }
        
        $user_id = $_SESSION['user']->Id;
        $documents_envoyes = Document::getDocumentsEnvoyes($user_id);
        $documents_recus = Document::getDocumentsRecus($user_id);
        $users = Document::getAllUsers();
        
        View::render('documents/index', [
            'documents_envoyes' => $documents_envoyes,
            'documents_recus' => $documents_recus,
            'users' => $users
        ]);
    }
    
    public function upload() {
        if (!isset($_SESSION['user'])) {
            header('Location: /GestionDesStagesProject/NewApp/public/index.php/login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $expediteur_id = $_SESSION['user']->Id;
            $destinataire_id = $_POST['destinataire_id'] ?? null;
            $titre = $_POST['titre'] ?? '';
            
            if (!isset($_FILES['document']) || $_FILES['document']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['error_message'] = 'Aucun fichier sélectionné ou erreur lors de l\'upload';
                header('Location: /GestionDesStagesProject/NewApp/public/index.php/dashboard#documents');
                exit;
            }
            
            if (!$destinataire_id || !$titre) {
                $_SESSION['error_message'] = 'Veuillez remplir tous les champs';
                header('Location: /GestionDesStagesProject/NewApp/public/index.php/dashboard#documents');
                exit;
            }
            
            $result = Document::upload($expediteur_id, $destinataire_id, $titre, $_FILES['document']);
            
            if ($result['success']) {
                $_SESSION['success_message'] = $result['message'];
            } else {
                $_SESSION['error_message'] = $result['error'];
            }
            
            header('Location: /GestionDesStagesProject/NewApp/public/index.php/dashboard#documents');
            exit;
        }
        
        // Si pas de POST, rediriger vers le dashboard
        header('Location: /GestionDesStagesProject/NewApp/public/index.php/dashboard#documents');
        exit;
    }
    
    public function download() {
        if (!isset($_SESSION['user'])) {
            header('Location: /GestionDesStagesProject/NewApp/public/index.php/login');
            exit;
        }
        
        $document_id = $_GET['id'] ?? null;
        $user_id = $_SESSION['user']->Id;
        
        if (!$document_id) {
            $_SESSION['error_message'] = 'Document non spécifié';
            header('Location: /GestionDesStagesProject/NewApp/public/index.php/dashboard#documents');
            exit;
        }
        
        $result = Document::downloadDocument($document_id, $user_id);
        
        if (!$result['success']) {
            $_SESSION['error_message'] = $result['error'];
            header('Location: /GestionDesStagesProject/NewApp/public/index.php/dashboard#documents');
            exit;
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