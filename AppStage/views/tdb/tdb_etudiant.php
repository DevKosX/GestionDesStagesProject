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
require_once '../includes/db_connect.php';

try {
    // Récupérer les informations du stage de l'étudiant
    $stmt = $pdo->prepare("SELECT * FROM Stage WHERE Id_Etudiant = :id_etudiant");
    $stmt->bindParam(':id_etudiant', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $stage = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Étudiant</title>
    <link rel="stylesheet" href="../public/css/tdb_etudiant.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Barre latérale -->
        <aside class="sidebar">
            <h2>Gestion des stages</h2>
            <ul>
                <li><a href="#" id="mon-stage-btn">Votre stage</a></li>
                <li><a href="#" id="deposer-rapport-btn">Déposer le rapport</a></li>
            </ul>
        </aside>

        <!-- Contenu principal -->
        <main class="main-content">
            <header class="header">
                <h1>Hi <?= htmlspecialchars($_SESSION['user_name']) ?> ! Welcome Back</h1>
            </header>

            <!-- Conteneur dynamique -->
            <div id="content-area">
                <p>Bienvenue sur votre tableau de bord.</p>
            </div>
        </main>
    </div>
    <script src="../public/js/tdb_etudiant.js"></script>
</body>
</html>
