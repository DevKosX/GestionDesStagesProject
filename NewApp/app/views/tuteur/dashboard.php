<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Tuteur</title>
</head>
<body>
    <h2>Bienvenue sur le tableau de bord Tuteur</h2>
    <p>Bonjour, <?php echo htmlspecialchars($user->email); ?> !</p>
    <a href="/GestionDesStagesProject/NewApp/public/index.php/login">Se déconnecter</a>
</body>
</html> 