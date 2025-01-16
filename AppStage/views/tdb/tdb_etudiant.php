<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// connecté ou pas 
if ($_SESSION['user_role'] !== 'etudiant') {
   header("Location: tableaudebord.php");
  exit();
}

// connexion bd
// require_once '../includes/db_connect.php';

// try {
    // LES info 
  //  $stmt = $pdo->prepare("SELECT * FROM Stage WHERE Id_Etudiant = :id_etudiant");
  //  $stmt->bindParam(':id_etudiant', $_SESSION['user_id'], PDO::PARAM_INT);
  //  $stmt->execute();
  //  $stage = $stmt->fetch(PDO::FETCH_ASSOC);
//} catch (PDOException $e) {
//    die("Erreur : " . $e->getMessage());
//}
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
                    <li><a href="../views/gestiondesstages.php">Gestion des stages</a></li>
                </ul>
            </nav>
            <!-- Logo de profil -->
            <div class="profile">
                <img src="../public/images/profile-icon.png" alt="Profil" id="profile-logo">
                <!-- Menu déroulant -->
                <div class="profile-menu" id="profile-menu">
                    <a href="../views/profil.php">Voir le profil</a>
                    <a href="../views/connexion.php">Se connecter</a>
                    <a href="../../logout.php" id="logout-btn">Se déconnecter</a>
                </div>
            </div>
        </div>
    </header>
        <!-- Contenu principal -->
        <main class="main-content">
                <h1>Hi <?= htmlspecialchars($_SESSION['user_name']) ?> ! Welcome Back</h1>
    </div>
    <script src="../public/js/tdb_etudiant.js"></script>
</body>
</html>
