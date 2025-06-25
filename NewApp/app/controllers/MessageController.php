<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Message.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/View.php';

class MessageController extends Controller {
    public function index() {
        $this->requireAuth();
        // Rediriger vers le dashboard avec l'onglet messagerie ouvert
        View::redirect('dashboard#messagerie');
    }

    public function envoyer() {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email_destinataire'] ?? '';
            $contenu = $_POST['contenu'] ?? '';
            $expediteur_id = $this->user->Id;
            $destinataire_id = Message::trouverDestinataireParEmail($email);
            if ($destinataire_id && $contenu) {
                Message::envoyer($expediteur_id, $destinataire_id, $contenu);
                $this->redirectWithMessage('dashboard#messagerie', 'success', 'Message envoyé avec succès !');
            } else {
                $this->redirectWithMessage('dashboard#messagerie', 'error', 'Destinataire ou contenu invalide.');
            }
        }
        // Si pas de POST, rediriger vers le dashboard
        View::redirect('dashboard#messagerie');
    }

    public function repondre() {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $destinataire_id = $_POST['destinataire_id'] ?? null;
            $contenu = $_POST['contenu'] ?? '';
            $expediteur_id = $this->user->Id;
            if ($destinataire_id && $contenu) {
                Message::envoyer($expediteur_id, $destinataire_id, $contenu);
                $this->redirectWithMessage('dashboard#messagerie', 'success', 'Réponse envoyée avec succès !');
            } else {
                $this->redirectWithMessage('dashboard#messagerie', 'error', 'Destinataire ou contenu invalide.');
            }
        }
        View::redirect('dashboard#messagerie');
    }
} 