<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté et est un étudiant
if ($_SESSION['user_role'] !== 'etudiant') {
    header("Location: ../connexion.php");
    exit();
}

// Connexion à la base de données
require_once $_SERVER['DOCUMENT_ROOT'] . '/GestionDesStagesProject/AppStage/includes/db_connect.php';

$upload_message = null;

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Id_Action']) && isset($_FILES['lienDocument'])) {
    // Définir le répertoire d'upload
    $uploadDir = 'C:/xampp/htdocs/GestionDesStagesProject/AppStage/public/uploads/';
    $fileName = uniqid() . '-' . basename($_FILES['lienDocument']['name']);
    $uploadPath = $uploadDir . $fileName;

    // Déplacer le fichier dans le répertoire d'upload
    if (move_uploaded_file($_FILES['lienDocument']['tmp_name'], $uploadPath)) {
        // Chemin relatif pour la base de données
        $relativePath = '/GestionDesStagesProject/AppStage/public/uploads/' . $fileName;

        try {
            // Mettre à jour la colonne lienDocument dans la base de données
            $stmt = $pdo->prepare("UPDATE action SET lienDocument = :lienDocument WHERE Id_Action = :id_action");
            $stmt->bindParam(':lienDocument', $relativePath, PDO::PARAM_STR);
            $stmt->bindParam(':id_action', $_POST['Id_Action'], PDO::PARAM_INT);
            $stmt->execute();

            $upload_message = "Fichier envoyé avec succès et enregistré dans la base de données.";
        } catch (PDOException $e) {
            $upload_message = "Erreur lors de l'enregistrement en base de données : " . $e->getMessage();
        }
    } else {
        $upload_message = "Erreur lors du téléchargement du fichier.";
    }
}

try {
    // Récupérer les actions de l'étudiant
    $stmt = $pdo->prepare("
        SELECT action.*, typeaction.libelle 
        FROM action 
        JOIN typeaction ON action.Id_TypeAction = typeaction.Id_TypeAction  
        WHERE Id_Etudiant = :id_etudiant 
        ORDER BY date_realisation DESC
    ");
    $stmt->bindParam(':id_etudiant', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $actions = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="/GestionDesStagesProject/AppStage/public/css/tdb_etudiant.css">
    <link rel="stylesheet" href="/GestionDesStagesProject/AppStage/public/css/notif.css">
    <link rel="stylesheet" href="/GestionDesStagesProject/AppStage/public/css/accueil.css">
</head>
<body>
<header>
    <div class="container">
        <nav>
            <ul>
                <li><a href="/GestionDesStagesProject/AppStage/views/accueilConnect.php">Accueil</a></li>
                <li><a href="/GestionDesStagesProject/AppStage/views/tableaudebord.php" class="active">Tableau de bord</a></li>
                <li><a href="/GestionDesStagesProject/AppStage/views/gestiondestages.php">Gestion des stages</a></li>
            </ul>
        </nav>
        <div class="profile">
            <img src="/GestionDesStagesProject/AppStage/public/images/profile-icon.png" alt="Profil" id="profile-logo">
            <div class="profile-menu" id="profile-menu">
                <a href="/GestionDesStagesProject/AppStage/views/profil.php">Voir le profil</a>
                <a href="/GestionDesStagesProject/AppStage/views/connexion.php" id="logout-btn">Se déconnecter</a>
            </div>
        </div>
    </div>
</header>

<main class="main-content">
    <h1>Bienvenue, <?= htmlspecialchars($_SESSION['user_name']) ?> !</h1>

    <div id="content-area">
        <h2>Mes Actions</h2>
        <?php if (!empty($actions)) : ?>
            <ul>
                <?php foreach ($actions as $action): ?>
                    <li>
                        <h3><?= htmlspecialchars($action['libelle']) ?></h3>
                        <p>Date de rendu : <?= htmlspecialchars($action['date_realisation']) ?></p>
                        <p>Envoyé le : <?= !empty($action['lienDocument']) ? 'Fichier envoyé' : 'Non rendu' ?></p>
                        <form action="/GestionDesStagesProject/AppStage/views/tdb/tdb_etudiant.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="Id_Action" value="<?= htmlspecialchars($action['Id_Action']) ?>">
                            <label for="lienDocument">Joindre un fichier :</label>
                            <input type="file" name="lienDocument" id="lienDocument" required>
                            <button type="submit">Envoyer</button>
                        </form>
                        <?php if (!empty($action['lienDocument'])): ?>
                            <a href="<?= htmlspecialchars($action['lienDocument']) ?>" download>Télécharger le fichier envoyé</a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php if (!empty($upload_message)): ?>
                <p class="success-message" style="color: green;"><?= htmlspecialchars($upload_message) ?></p>
            <?php endif; ?>
        <?php else : ?>
            <p>Aucune action pour le moment.</p>
        <?php endif; ?>
    </div>
</main>

<script src="/GestionDesStagesProject/AppStage/public/js/script_2.js"></script>
</body>
</html>
