<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// Vérifiez si l'utilisateur est connecté et est un étudiant
if ($_SESSION['user_role'] !== 'etudiant' && $_SESSION['user_role'] !== 'administrateur') {
    echo json_encode(['error' => 'Accès non autorisé.']);
    exit();
}

// Connexion à la base de données
require_once '../includes/db_connect.php';

try {
     // Déterminer l'ID de l'étudiant à utiliser
     if ($_SESSION['user_role'] === 'administrateur') {
        if (!isset($_GET['Id_Etudiant'])) {
            echo json_encode(['error' => 'ID étudiant manquant.']);
            exit();
        }
        $id_etudiant = $_GET['Id_Etudiant'];
    } else {
        $id_etudiant = $_SESSION['user_id'];
    }

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
        WHERE Stage.Id_Etudiant = :id_etudiant
    ");
    $stmt->bindParam(':id_etudiant', $id_etudiant, PDO::PARAM_INT);
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