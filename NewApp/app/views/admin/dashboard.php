<?php
require_once __DIR__ . '/../../models/User.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
</head>
<body>
    <h2>Bienvenue sur le tableau de bord Administrateur</h2>
    <p>Bonjour, <?php echo htmlspecialchars(isset(
        $user->email
    ) ? $user->email : ''); ?> !</p>
    <a href="<?= url('logout') ?>">Se déconnecter</a>
<a href="<?= url('messages') ?>">Messagerie</a>
</body>
</html> 