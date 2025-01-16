<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté et est un étudiant
if ($_SESSION['user_role'] !== 'etudiant') {
    header("Location: connexion.php");
    exit();
}

// Connexion à la base de données
//require_once '../../includes/db_connect.php';

//try {
    // Récupérer les informations du stage de l'étudiant
    //$stmt = $pdo->prepare("SELECT * FROM Stage WHERE Id_Etudiant = :id_etudiant");
    //$stmt->bindParam(':id_etudiant', $_SESSION['user_id'], PDO::PARAM_INT);
    //$stmt->execute();
    //$stage = $stmt->fetch(PDO::FETCH_ASSOC);
//} catch (PDOException $e) {
    //die("Erreur : " . $e->getMessage());
//}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme de gestion des stages</title>
    <link rel="stylesheet" href="../public/css/accueil.css">
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <ul>
                    <li><a href="../accueilConnect.php">Accueil</a></li>
                    <li><a href="../tableaudebord.php">Tableau de bord</a></li>
                    <li><a href="../gestiondestages.php" class="active">Gestion desstages</a></li>
                </ul>
            </nav>
            <!-- Logo de profil -->
            <div class="profile">
                <img src="../public/images/profile-icon.png" alt="Profil" id="profile-logo">
                <!-- Menu déroulant -->
                <div class="profile-menu" id="profile-menu">
                    <a href="../profil.php">Voir le profil</a>
                    <a href="../connexion.php">Se connecter</a>
                    <a href="../../../logout.php" id="logout-btn">Se déconnecter</a>
                </div>
            </div>
        </div>
    </header>
        <!-- Contenu principal -->
        <main class="main-content">
                <h1>Hi <?= htmlspecialchars($_SESSION['user_name']) ?> ! Welcome Back</h1>
    </div>
     <script src="../../public/js/script_2.js"></script>
</body>
</html>