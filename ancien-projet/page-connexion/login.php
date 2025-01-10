<?php
// Démarrer une session pour stocker les informations utilisateur
session_start();

// Connexion à la base de données
$host = "localhost";
$dbname = "nom_de_la_base";
$username = "nom_utilisateur";
$password = "mot_de_passe";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Vérifier si les données du formulaire sont envoyées
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Requête pour vérifier l'utilisateur
    $sql = "SELECT * FROM utilisateurs WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["mot_de_passe"])) {
        // Connexion réussie
        $_SESSION["utilisateur_id"] = $user["id"];
        $_SESSION["email"] = $user["email"];
        header("Location: dashboard.php"); // Redirection après connexion
        exit;
    } else {
        // Identifiants incorrects
        echo "Email ou mot de passe incorrect.";
    }
}
?>