<?php
require_once __DIR__ . '/../../models/User.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h2>Bienvenue sur votre tableau de bord</h2>
    <p>Bonjour, <?php echo isset(
        $user->email
    ) ? htmlspecialchars($user->email) : ''; ?> !</p>
    <a href="/GestionDesStagesProject/NewApp/public/index.php/login">Se déconnecter</a>
    <a href="/GestionDesStagesProject/NewApp/public/index.php/messages">Messagerie</a>
</body>
</html> 