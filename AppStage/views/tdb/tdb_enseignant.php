<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté et est un enseignant
if ($_SESSION['user_role'] !== 'enseignant') {
    header("Location: tableaudebord.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme de gestion des stages</title>
    <link rel="stylesheet" href="../public/css/accueil.css"> <!-- Chemin CSS corrigé -->
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
                    <a href="#" id="logout-btn">Se déconnecter</a>
                </div>
            </div>
        </div>
    </header>
    <h1>Bienvenue Enseignant, <?= htmlspecialchars($_SESSION['user_name']) ?></h1>
    <p>Voici votre tableau de bord enseignant.</p>
    <script> src="../public/js/script_2.js"></script>
</body>
</html>
