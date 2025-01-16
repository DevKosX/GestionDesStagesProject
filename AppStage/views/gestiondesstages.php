<?php
    session_start();
    // Vérifiez si l'utilisateur est connecté
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
        header("Location: connexion.php");
        exit();
    }

    // Récupérez le rôle de l'utilisateur
    $role = $_SESSION['user_role'];

    // Afficher une vue en fonction du rôle
    switch ($role) {
        case 'etudiant':
            include 'gds/gds_etudiant.php';
            break;
        case 'enseignant':
            include 'gds/gds_enseignant.php';
            break;
        case 'admin':
            include 'gds/gds_admin.php';
            break;
        default:
            echo "Erreur : rôle inconnu. Veuillez contacter un administrateur.";
            break;
    }
?>