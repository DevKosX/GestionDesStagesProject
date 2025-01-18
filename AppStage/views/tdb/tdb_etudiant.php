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
require_once '../includes/db_connect.php';
$upload_message = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Id_Action'])) {
    $id_action = $_POST['Id_Action'];
    if (isset($_FILES['lienDocument']) && $_FILES['lienDocument']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../public/uploads/';
        $fileName = uniqid() . '-' . $_FILES['lienDocument']['name'];
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['lienDocument']['tmp_name'], $uploadPath)) {
            try {
                $stmt = $pdo->prepare("UPDATE action SET lienDocument = :lienDocument WHERE Id_Action = :id_action");
                $stmt->bindParam(':lienDocument', $uploadPath, PDO::PARAM_STR);
                $stmt->bindParam(':id_action', $id_action, PDO::PARAM_INT);
                $stmt->execute();
                $upload_message = "Fichier envoyé avec succès";
                header("Location: tdb_etudiant.php");
                exit();
            } catch (PDOException $e) {
                die("Erreur : " . $e->getMessage());
            }
        } else {
            $upload_message = "Une erreur est survenue lors de l'envoi du fichier";
        }
    } else {
        $upload_message = "Aucun fichier envoyé";
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
                <li><a href="../accueilConnect.php">Accueil</a></li>
                <li><a href="../tableaudebord.php" class="active">Tableau de bord</a></li>
                <li><a href="../gestiondestages.php">Gestion des stages</a></li>
            </ul>
        </nav>
        <!-- Logo de profil -->
        <div class="profile">
            <img src="/GestionDesStagesProject/AppStage/public/images/profile-icon.png" alt="Profil" id="profile-logo">
            <!-- Menu déroulant -->
            <div class="profile-menu" id="profile-menu">
                <a href="/GestionDesStagesProject/AppStage/views/profil.php">Voir le profil</a>
                <a href="/GestionDesStagesProject/AppStage/views/connexion.php" id="logout-btn">Se déconnecter</a>
            </div>
        </div>
    </div>
</header>

<main class="main-content">
    <!-- Zone des notifications -->
    <div id="notifications" style="display: none; background: #f8d7da; padding: 10px; margin-bottom: 10px; border: 1px solid #f5c6cb; border-radius: 5px;">
        <strong>Notifications :</strong>
        <ul id="notification-list" style="margin: 0; padding: 0; list-style: none;"></ul>
    </div>

    <h1>Bienvenue, <?= htmlspecialchars($_SESSION['user_name']) ?> !</h1>
   
    <div id="content-area">
        <h2>Mes Actions</h2>
        <?php if (!empty($actions)) : ?>
            <ul>
                <?php foreach ($actions as $action): ?>
                    <li>
                        <h3><?= htmlspecialchars($action['libelle']) ?></h3>
                        <p>Date de rendu : <?= htmlspecialchars($action['date_realisation']) ?></p>
                        <p>Envoyé le : <?= isset($action['date_envoi']) ? htmlspecialchars($action['date_envoi']) : 'Non rendu' ?></p>
                        <form action="tdb_etudiant.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="Id_Action" value="<?= htmlspecialchars($action['Id_Action']) ?>">
                            <label for="lienDocument">Joindre un fichier :</label>
                            <input type="file" name="lienDocument" id="lienDocument">
                            <button type="submit">Envoyer</button>
                        </form>
                        <?php if (!empty($action['lienDocument'])): ?>
                            <a href="<?= htmlspecialchars($action['lienDocument']) ?>" download>Télécharger le fichier envoyé</a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php if (!empty($upload_message)): ?>
                <p class="success-message" style="color: green;"> <?= htmlspecialchars($upload_message) ?> </p>
            <?php endif; ?>
        <?php else : ?>
            <p>Aucune action pour le moment.</p>
        <?php endif; ?>
    </div>
</main>

<script src="/GestionDesStagesProject/AppStage/public/js/script_2.js"></script>

</body>
</html>
