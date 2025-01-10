<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    // Rediriger vers la page de connexion si non connecté
    header("Location: login.php");
    exit();
}

// Récupérer les informations de l'utilisateur depuis la session
$user = $_SESSION['user']; // Exemple : ['nom' => 'John Doe', 'email' => 'john.doe@example.com']
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Mon Profil</h1>
            <a href="accueil.php">Retour à l'accueil</a>
        </div>
    </header>
    <main>
        <section class="profile-details">
            <h2>Informations personnelles</h2>
            <ul>
                <li><strong>Nom :</strong> <?php echo htmlspecialchars($user['nom']); ?></li>
                <li><strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?></li>
            </ul>
        </section>
        <section class="profile-actions">
            <h2>Actions</h2>
            <a href="profile.php" class="btn">Modifier mes informations</a>
            <a href="logout.php" class="btn btn-danger">Se déconnecter</a>
        </section>
    </main>
</body>
</html>
