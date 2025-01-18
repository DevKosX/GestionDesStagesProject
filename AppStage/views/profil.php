<?php
session_start();

// Gérer la déconnexion directement dans profil.php
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: connexion.php");
    exit();
}

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
        body {
            font-family :'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            margin: 0;
            line-height: 1.6;
            background-color: #f4f4f4;
        }

        header {
            background-color: #152d65;
            color: #fff;
            padding: 1rem 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        nav li {
            margin-right: 1.5rem;
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
            <ul>
                <li><a href="accueilConnect.php">Page d'accueil</a></li>
                <li><a href="tableaudebord.php">Tableau de bord</a></li>
                <li><a href="gestiondesstages.php">Gestion de stage</a></li>
            </ul>
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
        <a href="profil.php" class="btn">Modifier mes informations</a>
        <a href="?action=logout" class="btn btn-danger">Se déconnecter</a>
    </section>
</main>
</body>
</html>