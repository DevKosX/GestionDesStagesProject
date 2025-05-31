<?php
require_once __DIR__ . '/../core/View.php';

class DashboardController {
    public function index() {
        if (!isset($_SESSION['user'], $_SESSION['role'])) {
            header('Location: login');
            exit;
        }
        $role = $_SESSION['role'];
        $user = $_SESSION['user'];
        if ($role === 'admin') {
            View::render('admin/dashboard', ['user' => $user]);
        } elseif ($role === 'tuteur') {
            View::render('tuteur/dashboard', ['user' => $user]);
        } elseif ($role === 'tuteur_entreprise') {
            View::render('tuteur_entreprise/dashboard', ['user' => $user]);
        } else {
            View::render('eleve/dashboard', ['user' => $user]);
        }
    }
} 