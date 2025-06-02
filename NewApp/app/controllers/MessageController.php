<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Message.php';
require_once __DIR__ . '/../core/View.php';

class MessageController {
    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: /GestionDesStagesProject/NewApp/public/index.php/login');
            exit;
        }
        $user_id = $_SESSION['user']->Id;
        $messages_recus = Message::messagesRecus($user_id);
        $messages_envoyes = Message::messagesEnvoyes($user_id);
        View::render('messages/index', [
            'messages_recus' => $messages_recus,
            'messages_envoyes' => $messages_envoyes
        ]);
    }

    public function envoyer() {
        if (!isset($_SESSION['user'])) {
            header('Location: /GestionDesStagesProject/NewApp/public/index.php/login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email_destinataire'] ?? '';
            $contenu = $_POST['contenu'] ?? '';
            $expediteur_id = $_SESSION['user']->Id;
            $destinataire_id = Message::trouverDestinataireParEmail($email);
            if ($destinataire_id && $contenu) {
                Message::envoyer($expediteur_id, $destinataire_id, $contenu);
                $_SESSION['success_message'] = 'Message envoyé avec succès !';
                header('Location: /GestionDesStagesProject/NewApp/public/index.php/dashboard#messagerie');
                exit;
            } else {
                $_SESSION['error_message'] = 'Destinataire ou contenu invalide.';
                header('Location: /GestionDesStagesProject/NewApp/public/index.php/dashboard#messagerie');
                exit;
            }
        }
        // Si pas de POST, rediriger vers le dashboard
        header('Location: /GestionDesStagesProject/NewApp/public/index.php/dashboard#messagerie');
        exit;
    }

    public function repondre() {
        if (!isset($_SESSION['user'])) {
            header('Location: /GestionDesStagesProject/NewApp/public/index.php/login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $destinataire_id = $_POST['destinataire_id'] ?? null;
            $contenu = $_POST['contenu'] ?? '';
            $expediteur_id = $_SESSION['user']->Id;
            if ($destinataire_id && $contenu) {
                Message::envoyer($expediteur_id, $destinataire_id, $contenu);
                $_SESSION['success_message'] = 'Réponse envoyée avec succès !';
                header('Location: /GestionDesStagesProject/NewApp/public/index.php/dashboard#messagerie');
                exit;
            } else {
                $_SESSION['error_message'] = 'Destinataire ou contenu invalide.';
                header('Location: /GestionDesStagesProject/NewApp/public/index.php/dashboard#messagerie');
                exit;
            }
        }
        header('Location: /GestionDesStagesProject/NewApp/public/index.php/dashboard#messagerie');
        exit;
    }
} 