<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// Vérifiez si l'utilisateur est connecté et est un enseignant
if ($_SESSION['user_role'] !== 'enseignant') {
    echo json_encode(['error' => 'Accès non autorisé.']);
    exit();
}

// Connexion à la base de données
require_once '../includes/db_connect.php';

try {
    // Récupérer les informations du stage de l'étudiant
    $stmt = $pdo->prepare("
        SELECT Stage.mission, Stage.date_debut, Stage.date_fin, Entreprise.adresse, Entreprise.ville,
         Entreprise.tel AS telEntreprise,
         Utilisateur.nom AS NomTuteurEntreprise,
        Utilisateur.telephone AS TelTuteurEntreprise,
         Utilisateur.email AS EmailTuteurEntreprise,
         utilisateur2.nom AS nomTuteurPedagogique,
         utilisateur2.prenom AS prenomTuteurPedagogique,
          utilisateur2.email AS EmailTuteurPedagogique,
          Stage.date_soutenance,Stage.salle_Soutenance,
           Etudiant.Id_Etudiant,
         Utilisateur3.nom AS nomEtudiant,
        Utilisateur3.prenom AS prenomEtudiant
        FROM Stage
          JOIN Tuteur_Entreprise ON Stage.Id_TuteurEntreprise = Tuteur_Entreprise.Id_TuteurEntreprise
        JOIN Entreprise ON Tuteur_Entreprise.Id_Entreprise = Entreprise.Id_Entreprise
         JOIN Utilisateur ON Tuteur_Entreprise.Id_TuteurEntreprise = Utilisateur.Id
        JOIN Utilisateur AS utilisateur2 ON Stage.Id_Enseignant = utilisateur2.Id
          JOIN Etudiant ON Stage.Id_Etudiant = Etudiant.Id_Etudiant
         JOIN Utilisateur AS utilisateur3 ON Etudiant.Id_Etudiant = utilisateur3.Id
        WHERE  Stage.Id_Enseignant = :id_enseignant
    ");
       $stmt->bindParam(':id_enseignant', $_SESSION['user_id'], PDO::PARAM_INT);
     $stmt->execute();
    $stages = $stmt->fetchAll(PDO::FETCH_ASSOC);


      foreach ($stages as &$stage) {
            $stmt2 = $pdo->prepare("SELECT * FROM Action
            JOIN TypeAction ON Action.Id_TypeAction = TypeAction.Id_TypeAction
            WHERE Action.Id_Etudiant = :id_etudiant AND Id_Stage = :id_stage ");
            $stmt2->bindParam(':id_etudiant', $stage['Id_Etudiant'], PDO::PARAM_INT);
             $stmt2->bindParam(':id_stage', $stage['Id_Stage'], PDO::PARAM_INT);
             $stmt2->execute();
            $actions = $stmt2->fetchAll(PDO::FETCH_ASSOC);
           $stage['actions'] = $actions;
        }

    if ($stages) {
       
        echo json_encode($stages);
    } else {
        echo json_encode(['error' => 'Aucune information de stage trouvée.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur interne : ' . $e->getMessage()]);
}
?>