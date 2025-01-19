<?php
    session_start();
    // connecté ou pas
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
        header("Location: connexion.php");
        exit();
    }

    // on recupere le rôle de l'utilisateur pour lui faire choisir sa vue ensuite
    $role = $_SESSION['user_role'];

    // je compte faire afficher les vues avec le fameux switch ($role)
    switch ($role) {
        case 'etudiant':
            include 'gds/gds_etudiant.php';
            break;
        case 'enseignant':
            include 'gds/gds_enseignant.php';
            break;
        case 'administrateur':
            include 'gds/gds_admin.php';
            break;
        default:
            echo "Erreur : rôle inconnu. Veuillez contacter un administrateur.";
            break;
    }
?>