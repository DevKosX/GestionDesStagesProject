<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// connecté ou pas 
if ($_SESSION['user_role'] !== 'etudiant') {
    header("Location: connexion.php");
    exit();
}

// Connexion à la base de données
require_once '../includes/db_connect.php';

try {
    // Récupérer les informations du stage de l'étudiant
    $stmt = $pdo->prepare("SELECT * FROM Stage WHERE Id_Etudiant = :id_etudiant");
    $stmt->bindParam(':id_etudiant', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $stage = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Étudiant</title>
    <link rel="stylesheet" href="/GestionDesStagesProject/AppStage/public/css/tdb_etudiant.css">
    <link rel="stylesheet" href="/GestionDesStagesProject/AppStage/public/css/notif.css">
    <link rel="stylesheet" href="/GestionDesStagesProject/AppStage/public/css/accueil.css">

    
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <ul>
                    <li><a href="../views/accueilConnect.php">Accueil</a></li>
                    <li><a href="../views/tableaudebord.php" class="active">Tableau de bord</a></li>
                    <li><a href="../views/gestiondestages.php">Gestion des stages</a></li>
                </ul>
            </nav>
            <!-- Logo de profil -->
            <div class="profile">
                <img src="../public/images/profile-icon.png" alt="Profil" id="profile-logo">
                <!-- Menu déroulant -->
                <div class="profile-menu" id="profile-menu">
                    <a href="../views/profil.php">Voir le profil</a>
                    <a href="../views/connexion.php">Se connecter</a>
                    <a href="../views/logout.php" id="logout-btn">Se déconnecter</a>
                </div>
            </div>
        </div>
    </header>
        <!-- Contenu principal -->
        <main class="main-content">
            <header class="header">
                <h1>Hi <?= htmlspecialchars($_SESSION['user_name']) ?> ! Welcome Back</h1>
            </header>
    </div>
</main>

<script src="/GestionDesStagesProject/AppStage/public/js/tdb_etudiant.js"></script>
</body>
</html>
