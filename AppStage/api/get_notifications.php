<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Non autorisé']);
    exit();
}

// Connexion à la base de données
require_once $_SERVER['DOCUMENT_ROOT'] . '/GestionDesStagesProject/AppStage/includes/db_connect.php';

try {
    // Récupérer les notifications non lues pour l'utilisateur
    $stmt = $pdo->prepare("
        SELECT a.Id_Action, t.libelle AS action, a.date_realisation, 
               DATE_ADD(
                   CASE 
                       WHEN t.ReferenceDelai = 'date_debut' THEN s.date_debut
                       WHEN t.ReferenceDelai = 'date_fin' THEN s.date_fin
                   END, INTERVAL t.delaiEnJours DAY
               ) AS date_echeance
        FROM action a 
        INNER JOIN typeaction t ON a.Id_TypeAction = t.Id_TypeAction 
        INNER JOIN stage s ON a.Id_Stage = s.Id_Stage
        WHERE a.Id_Etudiant = :id_etudiant 
          AND a.est_notifie = 0
          AND a.lienDocument IS NULL
    ");
    $stmt->bindParam(':id_etudiant', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Marquer les notifications comme lues après les avoir récupérées
    if (!empty($notifications)) {
        $updateStmt = $pdo->prepare("
            UPDATE action 
            SET est_notifie = 1 
            WHERE Id_Etudiant = :id_etudiant AND est_notifie = 0 AND lienDocument IS NULL
        ");
        $updateStmt->bindParam(':id_etudiant', $_SESSION['user_id'], PDO::PARAM_INT);
        $updateStmt->execute();
    }

    echo json_encode($notifications);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur : ' . $e->getMessage()]);
}
?>
