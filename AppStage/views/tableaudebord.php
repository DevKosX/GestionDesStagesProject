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
            include 'tdb/tdb_etudiant.php';
            break;
        case 'enseignant':
            include 'tdb/tdb_enseignant.php';
            break;
        case 'administrateur':
            include 'tdb/tdb_admin.php';
            break;
        default:
            echo "Erreur : rôle inconnu. Veuillez contacter un administrateur.";
            break;
    }
?>