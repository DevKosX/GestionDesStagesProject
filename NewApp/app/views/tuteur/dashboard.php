<?php
require_once __DIR__ . '/../../models/User.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Tuteur</title>
</head>
<body>
    <h2>Bienvenue sur le tableau de bord Tuteur</h2>
    <p>Bonjour, <?php echo htmlspecialchars(isset(
        $user->email
    ) ? $user->email : ''); ?> !</p>
    <a href="/GestionDesStagesProject/NewApp/public/index.php/login">Se déconnecter</a>
    <a href="/GestionDesStagesProject/NewApp/public/index.php/messages">Messagerie</a>
</body>
</html> 