<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté et est un administrateur
if ($_SESSION['user_role'] !== 'administrateur') {
    header("Location: ../connexion.php");
    exit();
}

// Connexion à la base de données
require_once $_SERVER['DOCUMENT_ROOT'] . '/GestionDesStagesProject/AppStage/includes/db_connect.php';

$message = null;

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_annee = $_POST['Id_Annee'];
    $id_departement = $_POST['Id_Departement'];
    $num_semestre = $_POST['numSemestre'];
    $id_etudiant = $_POST['Id_Etudiant'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $mission = $_POST['mission'];
    $date_soutenance = $_POST['date_soutenance'];
    $salle_soutenance = $_POST['salle_Soutenance'];
    $id_enseignant = $_POST['Id_Enseignant'];
    $id_tuteur_entreprise = $_POST['Id_TuteurEntreprise'];

    try {
        // Préparer la requête SQL
        $stmt = $pdo->prepare("
            INSERT INTO stage 
            (Id_Annee, Id_Departement, numSemestre, Id_Etudiant, date_debut, date_fin, mission, date_soutenance, salle_Soutenance, Id_Enseignant, Id_TuteurEntreprise) 
            VALUES 
            (:id_annee, :id_departement, :num_semestre, :id_etudiant, :date_debut, :date_fin, :mission, :date_soutenance, :salle_soutenance, :id_enseignant, :id_tuteur_entreprise)
        ");

        // Lier les paramètres
        $stmt->bindParam(':id_annee', $id_annee, PDO::PARAM_INT);
        $stmt->bindParam(':id_departement', $id_departement, PDO::PARAM_INT);
        $stmt->bindParam(':num_semestre', $num_semestre, PDO::PARAM_INT);
        $stmt->bindParam(':id_etudiant', $id_etudiant, PDO::PARAM_INT);
        $stmt->bindParam(':date_debut', $date_debut, PDO::PARAM_STR);
        $stmt->bindParam(':date_fin', $date_fin, PDO::PARAM_STR);
        $stmt->bindParam(':mission', $mission, PDO::PARAM_STR);
        $stmt->bindParam(':date_soutenance', $date_soutenance, PDO::PARAM_STR);
        $stmt->bindParam(':salle_soutenance', $salle_soutenance, PDO::PARAM_STR);
        $stmt->bindParam(':id_enseignant', $id_enseignant, PDO::PARAM_INT);
        $stmt->bindParam(':id_tuteur_entreprise', $id_tuteur_entreprise, PDO::PARAM_INT);

        // Exécuter la requête
        $stmt->execute();
        $message = "Stage ajouté avec succès.";
    } catch (PDOException $e) {
        $message = "Erreur lors de l'ajout du stage : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Administrateur</title>
    <link rel="stylesheet" href="/GestionDesStagesProject/AppStage/public/css/tdb_admin.css">
    <link rel="stylesheet" href="/GestionDesStagesProject/AppStage/public/css/accueil.css">
</head>
<body>
<header>
    <div class="container">
        <nav>
            <ul>
                <li><a href="../accueilConnect.php">Accueil</a></li>
                <li><a href="../tableaudebord.php" class="active">Tableau de bord</a></li>
                <li><a href="../gestiondestages.php">Gestion des stages</a></li>
            </ul>
        </nav>
        <div class="profile">
            <img src="/GestionDesStagesProject/AppStage/public/images/profile-icon.png" alt="Profil" id="profile-logo">
            <div class="profile-menu" id="profile-menu">
                <a href="/GestionDesStagesProject/AppStage/views/profil.php">Voir le profil</a>
                <a href="/GestionDesStagesProject/AppStage/views/logout.php" id="logout-btn">Se déconnecter</a>
            </div>
        </div>
    </div>
</header>

<main>
    <h1>Bienvenue, <?= htmlspecialchars($_SESSION['user_name']) ?> !</h1>
    <h2>Ajouter un stage</h2>

    <?php if ($message): ?>
        <p style="color: green;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form action="/GestionDesStagesProject/AppStage/views/tdb/tdb_admin.php" method="post">
        <label for="Id_Annee">iD Année :</label>
        <input type="number" id="Id_Annee" name="Id_Annee" required>

        <label for="Id_Departement">ID Département :</label>
        <input type="number" id="Id_Departement" name="Id_Departement" required>

        <label for="numSemestre">Numéro du Semestre :</label>
        <input type="number" id="numSemestre" name="numSemestre" required>

        <label for="Id_Etudiant">ID Étudiant :</label>
        <input type="number" id="Id_Etudiant" name="Id_Etudiant" required>

        <label for="date_debut">Date de Début :</label>
        <input type="date" id="date_debut" name="date_debut" required>

        <label for="date_fin">Date de Fin :</label>
        <input type="date" id="date_fin" name="date_fin" required>

        <label for="mission">Mission :</label>
        <textarea id="mission" name="mission" required></textarea>

        <label for="date_soutenance">Date de Soutenance :</label>
        <input type="date" id="date_soutenance" name="date_soutenance" required>

        <label for="salle_Soutenance">Salle de Soutenance :</label>
        <input type="text" id="salle_Soutenance" name="salle_Soutenance" required>

        <label for="Id_Enseignant">ID Enseignant :</label>
        <input type="number" id="Id_Enseignant" name="Id_Enseignant" required>

        <label for="Id_TuteurEntreprise">ID Tuteur Entreprise :</label>
        <input type="number" id="Id_TuteurEntreprise" name="Id_TuteurEntreprise" required>

        <button type="submit">Ajouter le Stage</button>
    </form>
</main>

<script src="/GestionDesStagesProject/AppStage/public/js/script_2.js"></script>
</body>
</html>
