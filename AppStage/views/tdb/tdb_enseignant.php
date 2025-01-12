<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté et est un enseignant
if ($_SESSION['user_role'] !== 'enseignant') {
    header("Location: tableaudebord.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Enseignant</title>
</head>
<body>
    <h1>Bienvenue Enseignant, <?= htmlspecialchars($_SESSION['user_name']) ?></h1>
    <p>Voici votre tableau de bord enseignant.</p>
</body>
</html>
