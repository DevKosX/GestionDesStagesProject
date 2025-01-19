<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté et est un administrateur
if ($_SESSION['user_role'] !== 'administrateur') {
    header("Location: connexion.php");
    exit();
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté et est un administrateur
if ($_SESSION['user_role'] !== 'administrateur') {
    header("Location: connexion.php");
    exit();
}

require_once '../includes/db_connect.php';

try {
    // Récupérer les étudiants
    $stmt = $pdo->prepare("SELECT Id, prenom, nom FROM Utilisateur WHERE Id IN (SELECT Id_Etudiant FROM Etudiant)");
    $stmt->execute();
    $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
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
                    <li><a href="../views/tableaudebord.php" >Tableau de bord</a></li>
                    <li><a href="../views/gestiondesstages.php" class="active">Gestion des stages</a></li>
                </ul>
            </nav>
            <!-- Logo de profil -->
            <div class="profile">
                <img src="../public/images/profile-icon.png" alt="Profil" id="profile-logo">
                <!-- Menu déroulant -->
                <div class="profile-menu" id="profile-menu">
                    <a href="../views/profil.php">Voir le profil</a>
                    <a href="../logoutphp" id="logout-btn">Se déconnecter</a>
                </div>
            </div>
        </div>
    </header>
    <main>
        <h1>Bienvenue Administrateur, <?= htmlspecialchars($_SESSION['user_name']) ?></h1>
        <p>Voici votre espace de gestion des stages.</p>
        <label for="student-select">Sélectionnez un élève :</label>
        <select id="student-select">
            <option value="">-- Sélectionnez un élève --</option>
            <?php foreach ($etudiants as $etudiant): ?>
                <option value="<?= htmlspecialchars($etudiant['Id']) ?>"><?= htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']) ?></option>
            <?php endforeach; ?>
        </select>
        <div id="content-area"></div>
    </main>
    <script src="../public/js/gds_admin.js"></script>
</body>
</html>
