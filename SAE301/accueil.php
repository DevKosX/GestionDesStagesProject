<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme de gestion des stages</title>
    <link rel="stylesheet" href="accueil-src/style.css"> <!-- J'ai changer le chemin avec la nouvelle architecture-->
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <ul>
                    <li><a href="accueil.php" class="active">Accueil</a></li>
                    <li><a href="tableaudebord.php">Tableau de bord</a></li>
                    <li><a href="gestiondestages.php">Gestion des stages</a></li>
                </ul>
            </nav>
            <!-- Logo de profil -->
        <div class="profile">
            <img src="images/profile-icon.png" alt="Profil" id="profile-logo">
            <!-- Menu déroulant -->
            <div class="profile-menu" id="profile-menu">
                <a href="profile.php">Voir le profil</a>
                <a href="login.php">Se connecter</a>
                <a href="#" id="logout-btn">Se déconnecter</a>
            </div>
        </div>
        </div>
    </header>

    <main>
        <section class="banner">
            <img src="images/image_campus.jpeg" alt="Campus Université Sorbonne Paris Nord">
            <div class="banner-content">
                <h1>Bienvenue sur la plateforme de gestion des stages du BUT</h1>
                <p>Conçue pour vous accompagner tout au long de vos expériences en entreprise.</p>
                <p>Suivez vos stages, déposez vos documents, échangez avec vos tuteurs et restez informé des échéances importantes, le tout depuis un espace centralisé et intuitif.</p>
            </div>
        </section>

        <section class="logo">
            <a href="https://www.univ-spn.fr/" target="_blank" class="logo-link">
            <img src="images/uspn-logo.png" alt="Logo Université Sorbonne Paris Nord">
        </a>
        </section>
    </main>
    <script src="accueil-src/script.js"></script>
</body>
</html>
