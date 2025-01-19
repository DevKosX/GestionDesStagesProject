<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

// Récupérer le message de la session s'il existe
$message = "";
$messageClass = "";
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $messageClass = $_SESSION['messageClass'];
    unset($_SESSION['message']); // Supprimer le message après affichage
    unset($_SESSION['messageClass']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme de gestion des stages - Connecté</title>
    <link rel="stylesheet" href="../public/css/accueil.css">
    <style>
        /* Styles pour les messages */
        .message {
            margin: 20px auto;
            max-width: 800px;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            font-size: 1.2rem;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
<header>
    <div class="container">
        <nav>
            <ul>
                 <li><a href="accueilConnect.php" class="active">Accueil</a></li>
                <li><a href="tableaudebord.php">Tableau de bord</a></li>
                <li><a href="gestiondesstages.php">Gestion des stages</a></li>
            </ul>
        </nav>
        <!-- Logo de profil -->
        <div class="profile">
            <img src="../public/images/profile-icon.png" alt="Profil" id="profile-logo">
            <!-- Menu déroulant -->
            <div class="profile-menu" id="profile-menu">
                <a href="../views/profil.php">Voir mon profil</a>
                <a href="#" id="logout-btn">Se déconnecter</a>
            </div>
        </div>
    </div>
</header>

<main>
    <!-- Affichage du message si présent -->
    <?php if (!empty($message)): ?>
        <div class="message <?php echo htmlspecialchars($messageClass); ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <section class="banner">
        <img src="../public/images/image_campus.jpeg" alt="Campus Université Sorbonne Paris Nord">
        <div class="banner-content">
            <h1>Bienvenue sur la plateforme de gestion des stages du BUT</h1>
            <p>Conçue pour vous accompagner tout au long de vos expériences en entreprise.</p>
            <p>Suivez vos stages, déposez vos documents, échangez avec vos tuteurs et restez informé des échéances importantes, le tout depuis un espace centralisé et intuitif.</p>
        </div>
    </section>

    <section class="logo">
        <a href="https://www.univ-spn.fr/" target="_blank" class="logo-link">
            <img src="../public/images/uspn-logo.png" alt="Logo Université Sorbonne Paris Nord">
        </a>
    </section>
</main>
<script src="../public/js/script_2.js"></script>
</body>
</html>
