<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// Vérifiez si l'utilisateur est connecté et est un étudiant
if ($_SESSION['user_role'] !== 'etudiant') {
    echo json_encode(['error' => 'Accès non autorisé.']);
    exit();
}

// Connexion à la base de données
require_once '../includes/db_connect.php';

try {
    // Récupérer les informations du stage de l'étudiant
    $stmt = $pdo->prepare("
        SELECT Stage.mission, Stage.date_debut, Stage.date_fin, Entreprise.adresse, Entreprise.ville
        FROM Stage
        JOIN Tuteur_Entreprise ON Stage.Id_TuteurEntreprise = Tuteur_Entreprise.Id_TuteurEntreprise
        JOIN Entreprise ON Tuteur_Entreprise.Id_Entreprise = Entreprise.Id_Entreprise
        WHERE Stage.Id_Etudiant = :id_etudiant
    ");
    $stmt->bindParam(':id_etudiant', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $stage = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stage) {
        echo json_encode($stage);
    } else {
        echo json_encode(['error' => 'Aucune information de stage trouvée.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur interne : ' . $e->getMessage()]);
}
?>
