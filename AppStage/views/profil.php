<?php
session_start();
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion si non connecté
    header("Location: connexion.php");
    exit();
}

// Récupérer les informations de l'utilisateur depuis la session
$userName = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f9;
            color: #333;
            line-height: 1.6;
        }

        header {
            background-color: #00274d;
            color: #fff;
            padding: 1rem 0;
        }

        header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        header h1 {
            font-size: 1.8rem;
        }

        nav {
            display: flex;
            gap: 15px;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        nav a:hover {
            color: #ffcc00;
        }

        main {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 20px;
        }

        .profile-details, .profile-actions {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .profile-details h2, .profile-actions h2 {
            margin-bottom: 15px;
            color: #00274d;
        }

        .profile-details ul {
            list-style: none;
        }

        .profile-details li {
            margin-bottom: 10px;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            color: #fff;
            background-color: #00274d;
            text-decoration: none;
            border-radius: 3px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #ffcc00;
        }

        .btn-danger {
            background-color: #e74c3c;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
<header>
    <div class="container">
        <h1>Mon Profil</h1>
        <nav>
            <a href="../views/accueilConnect.php">Page d'accueil</a>
            <a href="../views/tableaudebord.php">Tableau de bord</a>
            <a href="../views/gestiondesstages.php">Gestion de stage</a>
        </nav>
    </div>
</header>
<main>
    <section class="profile-details">
        <h2>Informations personnelles</h2>
        <ul>
            <li><strong>Nom :</strong> <?php echo htmlspecialchars($userName); ?></li>
        </ul>
    </section>
    <section class="profile-actions">
        <h2>Actions</h2>
        <a href="views/profil.php" class="btn">Modifier mes informations</a>
        <a href="views/logout.php" class="btn btn-danger">Se déconnecter</a>
    </section>
</main>
</body>
</html>
