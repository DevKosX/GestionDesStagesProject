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
                    header('Location: dashboard');
                    exit;
                }
            } else {
                $error = "Identifiants incorrects";
            }
        }
        $this->render('auth/connexion', ['error' => $error]);
    }

    public function logout() {
        session_destroy();
        header('Location: login');
        exit;
    }
} 