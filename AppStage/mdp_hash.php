<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'stage'; // Remplace par le nom exact de ta base de données
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Mots de passe en clair pour chaque utilisateur
    $passwords = [
        1 => '123', // ID 1 : Mot de passe en clair
        2 => '123', // ID 2 : Mot de passe en clair
        3 => '123', // ID 3 : Mot de passe en clair
    ];

    foreach ($passwords as $id => $plainPassword) {
        // Hacher le mot de passe
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

        // Mettre à jour dans la base de données
        $sql = "UPDATE Utilisateur SET mot_de_passe = :hashedPassword WHERE Id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':hashedPassword', $hashedPassword);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo "Mot de passe mis à jour pour l'utilisateur ID : $id<br>";
    }

    echo "Tous les mots de passe ont été mis à jour.";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
