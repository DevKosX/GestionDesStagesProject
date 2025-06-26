<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/Controller.php';

class AuthController extends Controller {
    public function login() {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = User::findByEmail($_POST['email']);
            if ($user && password_verify($_POST['password'], $user->mot_de_passe)) {
                $role = User::getRole($user->Id);
                if (!$role) {
                    $error = "Impossible de déterminer votre rôle. Veuillez contacter un administrateur.";
                } else {
                    $_SESSION['user'] = $user;
                    $_SESSION['role'] = $role;
                    $this->redirectWithMessage('dashboard', 'success', 'Connexion réussie');
                }
            } else {
                $error = "Identifiants incorrects";
            }
        }
        $this->render('auth/connexion', ['error' => $error]);
    }

    public function logout() {
        session_destroy();
        // Utiliser la méthode View::redirect qui est plus fiable
        require_once __DIR__ . '/../core/View.php';
        View::redirect('login');
    }

    public function changePassword() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            View::redirect('login');
            return;
        }

        $success = '';
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            // Validation
            if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                $error = 'Tous les champs sont obligatoires.';
            } elseif (strlen($new_password) < 6) {
                $error = 'Le nouveau mot de passe doit contenir au moins 6 caractères.';
            } elseif ($new_password !== $confirm_password) {
                $error = 'La confirmation du mot de passe ne correspond pas.';
            } else {
                // Vérifier l'ancien mot de passe
                $user = User::findById($_SESSION['user']->Id);
                if (!$user || !password_verify($current_password, $user->mot_de_passe)) {
                    $error = 'Le mot de passe actuel est incorrect.';
                } else {
                    // Mettre à jour le mot de passe
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    if (User::updatePassword($user->Id, $hashed_password)) {
                        $success = 'Mot de passe modifié avec succès !';
                        // Optionnel : déconnecter l'utilisateur pour qu'il se reconnecte avec le nouveau mot de passe
                        // $this->logout();
                    } else {
                        $error = 'Erreur lors de la mise à jour du mot de passe.';
                    }
                }
            }
        }

        // Récupérer les données de base nécessaires pour la sidebar
        require_once __DIR__ . '/../models/Message.php';
        require_once __DIR__ . '/../models/Document.php';
        require_once __DIR__ . '/../models/Notification.php';
        
        $user_id = $_SESSION['user']->Id;
        $role = $_SESSION['role'];
        
        // Statistiques simplifiées pour la sidebar
        try {
            $stats_notifications = Notification::getStatsNotifications($user_id, $role);
            $stats = [
                'messages_recus' => $stats_notifications['messages_recus'] ?? 0,
                'documents_recus' => $stats_notifications['documents_recus'] ?? 0,
                'evenements_urgents' => $stats_notifications['evenements_urgents'] ?? 0,
                'stages_actifs' => $stats_notifications['stages_actifs'] ?? 0
            ];
        } catch (Exception $e) {
            // Fallback en cas d'erreur
            $stats = [
                'messages_recus' => 0,
                'documents_recus' => 0,
                'evenements_urgents' => 0,
                'stages_actifs' => 0
            ];
        }

        $this->render('auth/change_password', [
            'success' => $success,
            'error' => $error,
            'user' => $_SESSION['user'],
            'role' => $role,
            'stats' => $stats,
            'current_page' => 'change-password'
        ]);
    }
} 