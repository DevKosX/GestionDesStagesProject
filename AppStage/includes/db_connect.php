<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'stage'; // Remplace par le nom de ta base
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
