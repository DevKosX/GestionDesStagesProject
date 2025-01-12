<?php
session_start();
require_once '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['Id'];
            $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];

            // Vérifiez le rôle en fonction des tables associées
            $role = null;

            // Vérifier s'il est étudiant
            $stmt = $pdo->prepare("SELECT 1 FROM Etudiant WHERE Id_Etudiant = :id");
            $stmt->bindParam(':id', $user['Id'], PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetch()) {
                $role = 'etudiant';
            }

            // Vérifier s'il est enseignant
            $stmt = $pdo->prepare("SELECT 1 FROM Enseignant WHERE Id_Enseignant = :id");
            $stmt->bindParam(':id', $user['Id'], PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetch()) {
                $role = 'enseignant';
            }

            // Vérifier s'il est administrateur
            $stmt = $pdo->prepare("SELECT 1 FROM Administrateur WHERE Id_Administrateur = :id");
            $stmt->bindParam(':id', $user['Id'], PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetch()) {
                $role = 'admin';
            }

            if ($role) {
                $_SESSION['user_role'] = $role;
                header("Location: tableaudebord.php");
                exit();
            } else {
                $error_message = "Impossible de déterminer votre rôle. Veuillez contacter un administrateur.";
            }
        } else {
            $error_message = "Email ou mot de passe incorrect.";
        }
    } catch (Exception $e) {
        $error_message = "Erreur interne : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../public/css/connexion.css">
</head>
<body>
    <div class="container">
        <div class="form-header">
            <img src="../public/images/profile-icon.png" alt="Profil" class="profile-img">
            <h1>Connexion</h1>
        </div>
        <!-- Affichage des messages d'erreur -->
        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <form action="connexion.php" method="POST">
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
