<?php
session_start();

// Initialisation des messages
$message = "";
$messageClass = "";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

// Vérifier que la requête est une méthode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Vérifier les champs
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $message = "Tous les champs sont obligatoires.";
        $messageClass = "error";
    } elseif ($newPassword !== $confirmPassword) {
        $message = "Le nouveau mot de passe et sa confirmation ne correspondent pas.";
        $messageClass = "error";
    } else {
        // Vérifier les critères du mot de passe
        $passwordPattern = "/^(?=.*[A-Z])(?=.*[!@#$%^&*(),.?\":{}|<>])(?=.*[a-z])(?=.*\d).{8,}$/";
        if (!preg_match($passwordPattern, $newPassword)) {
            $message = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, un caractère spécial et un chiffre.";
            $messageClass = "error";
        } else {
            // Connexion à la base de données
            $conn = new mysqli("localhost", "root", "", "stage"); // Remplacez par vos identifiants

            if ($conn->connect_error) {
                die("Erreur de connexion : " . $conn->connect_error);
            }

            $userId = $_SESSION['user_id'];

            // Vérifier le mot de passe actuel
            $sql = "SELECT mot_de_passe FROM utilisateur WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                // Vérification du mot de passe actuel
                if (password_verify($currentPassword, $user['mot_de_passe'])) {
                    // Hachage du nouveau mot de passe
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    // Mise à jour du mot de passe
                    $updateSql = "UPDATE utilisateur SET mot_de_passe = ? WHERE id = ?";
                    $updateStmt = $conn->prepare($updateSql);
                    $updateStmt->bind_param("si", $hashedPassword, $userId);

                    if ($updateStmt->execute()) {
                        // Message de succès et redirection
                        $_SESSION['message'] = "Votre mot de passe a été mis à jour avec succès.";
                        $_SESSION['messageClass'] = "success";
                        header("Location: accueilConnect.php"); // Redirection vers l'accueil
                        exit();
                    } else {
                        $message = "Erreur lors de la mise à jour.";
                        $messageClass = "error";
                    }

                    $updateStmt->close();
                } else {
                    $message = "Le mot de passe actuel est incorrect.";
                    $messageClass = "error";
                }
            } else {
                $message = "Utilisateur non trouvé.";
                $messageClass = "error";
            }

            $stmt->close();
            $conn->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le mot de passe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
            color: #152d65;
        }

        form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form button {
            padding: 10px 15px;
            background-color: #152d65;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #1b42a8;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Modifier mon mot de passe</h2>
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $messageClass; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form action="update_password.php" method="POST">
            <label for="current-password">Mot de passe actuel :</label>
            <input type="password" id="current-password" name="current_password" required>
            <label for="new-password">Nouveau mot de passe :</label>
            <input type="password" id="new-password" name="new_password" required>
            <label for="confirm-password">Confirmer le nouveau mot de passe :</label>
            <input type="password" id="confirm-password" name="confirm_password" required>
            <button type="submit">Mettre à jour</button>
        </form>
    </div>
</body>
</html>
