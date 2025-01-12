<?php
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id']) || !isset($_SESSION['user_role'])) {
    header("Location: connexion.php");
    exit();
}

// Récupérez le rôle de l'utilisateur
$role = $_SESSION['user_role'];

// Afficher une vue en fonction du rôle
switch ($role) {
    case 'etudiant':
        include '../views/tdb/tdb_etudiant.php';
        break;
    case 'enseignant':
        include '../views/tdb/tdb_enseignant.php';
        break;
    case 'admin':
        include '../views/tdb/tdb_admin.php';
        break;
    default:
        echo "Erreur : rôle inconnu. Veuillez contacter un administrateur.";
        break;
}
?>
