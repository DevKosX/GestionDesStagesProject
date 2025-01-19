<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté et est un étudiant
if ($_SESSION['user_role'] !== 'etudiant') {
    header("Location: connexion.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Étudiant</title>
       <link rel="stylesheet" href="../public/css/tdb_etudiant.css">
       <link rel="stylesheet" href="../public/css/accueil.css">
       
         
</head>

<body>
    <header>
        <div class="container">
            <nav>
                <ul>
                     <li><a href="../views/accueilConnect.php">Accueil</a></li>
                    <li><a href="../views/tableaudebord.php">Tableau de bord</a></li>
                    <li><a href="../views/gestiondestages.php" class="active">Gestion des stages</a></li>
                </ul>
            </nav>
            <!-- Logo de profil -->
            <div class="profile">
                <img src="../public/images/profile-icon.png" alt="Profil" id="profile-logo">
                <!-- Menu déroulant -->
                <div class="profile-menu" id="profile-menu">
                    <a href="../views/profil.php">Voir le profil</a>
                     <a href="../logout.php" id="logout-btn">Se déconnecter</a>
                </div>
            </div>
        </div>
    </header>
        <!-- Contenu principal -->
        <main class="main-content">
            
                 <h1>Gestion des Stages</h1>
                 <div id="content-area"></div>
                 <img src="../public/images/image.png" alt="image de téléphone" style="position: absolute; top: 120px; right: 20px; width: 30%; height: auto;">
             </header>
        </main>
    <script src="../public/js/gds_etudiant.js"></script>
</body>
</html>