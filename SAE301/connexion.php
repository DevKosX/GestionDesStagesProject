<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="connexion-src/style.css">
    <title>Connexion</title>
</head>
<body>
    <div class="container">
        <div class="form-header">
            <img src="images/profile-icon.png" alt="Profil" class="profile-img">
            <h1>Connexion</h1>
        </div>
        <form action="login.php" method="POST">
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" required>
            <br>
            <label for="password">Mot de passe :</label>
            <input type="password" name="password" id="password" required>
            <br>
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>