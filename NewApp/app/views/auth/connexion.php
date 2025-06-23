
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Plateforme de stage</title>
    <link rel="stylesheet" href="<?= asset('css/connexion.css') ?>">
</head>
<body>
    <div class="login-bg">
        <div class="login-container">
            <div class="login-header">
                <img src="<?= asset('images/pp.avif') ?>" alt="Profil" class="profile-img">
                <h1>Connexion</h1>
            </div>
            <?php if (!empty($error)) echo "<p class='error-message'>".htmlspecialchars($error)."</p>"; ?>
            <form method="post">
                <label for="email">Email :</label>
                <input type="email" name="email" id="email" required>
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password" required>
                <button type="submit">Se connecter</button>
            </form>
        </div>
    </div>
</body>
</html> 