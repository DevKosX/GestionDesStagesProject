<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté et est un enseignant
if ($_SESSION['user_role'] !== 'enseignant') {
    header("Location: connexion.php");
    exit();
}

$chemin_db =  '../../includes/db_connect.php';
if(file_exists($chemin_db)){
    require_once $chemin_db;
}
else {
    $chemin_db = '../includes/db_connect.php';
      if (file_exists($chemin_db)){
       require_once $chemin_db;
      } else{
          die("Le fichier de connexion n'a pas pu être trouvé.");
        }

}
try {
    // Récupérer les étudiants
    $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE Id IN (SELECT Id_Etudiant FROM Etudiant) ");
    $stmt->execute();
    $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les types d'actions
     $stmt = $pdo->prepare("SELECT * FROM TypeAction");
    $stmt->execute();
     $typeActions = $stmt->fetchAll(PDO::FETCH_ASSOC);

     
     if(isset($_GET['Id_Etudiant'])) {
        $stmt = $pdo->prepare("
            SELECT action.*, typeaction.libelle 
            FROM action 
            JOIN typeaction ON action.Id_TypeAction = typeaction.Id_TypeAction 
             WHERE Id_Etudiant = :id_etudiant 
            ORDER BY date_realisation DESC
        ");
            $stmt->bindParam(':id_etudiant', $_GET['Id_Etudiant'], PDO::PARAM_INT);
            $stmt->execute();
            $actions = $stmt->fetchAll(PDO::FETCH_ASSOC);
          }

    }
catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
 $idEtudiantSelectionne = isset($_GET['Id_Etudiant']) ? $_GET['Id_Etudiant'] : null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme de gestion des stages</title>
    <link rel="stylesheet" href="/GestionDesStagesProject/AppStage/public/css/tdb_enseignant.css">
    <link rel='stylesheet' href="/GestionDesStagesProject/AppStage/public/css/accueil.css">
    <!-- Chemin CSS corrigé -->
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <ul>
                   <li><a href="/GestionDesStagesProject/AppStage/views/accueilConnect.php">Accueil</a></li>
                    <li><a href="/GestionDesStagesProject/AppStage/views/tableaudebord.php" class="active">Tableau de bord</a></li>
                    <li><a href="/GestionDesStagesProject/AppStage/views/gestiondestages.php">Gestion des stages</a></li>
                </ul>
            </nav>
            <!-- Logo de profil -->
            <div class="profile">
                <img src="/GestionDesStagesProject/AppStage/public/images/profile-icon.png" alt="Profil" id="profile-logo">
                <!-- Menu déroulant -->
                <div class="profile-menu" id="profile-menu">
                    <a href="/GestionDesStagesProject/AppStage/views/profil.php">Voir le profil</a>
                    <a href="/GestionDesStagesProject/AppStage/views/logout.php" id="logout-btn">Se déconnecter</a>
                </div>
            </div>
        </div>
    </header>
    <div class="selection-container">
    <h2>Veuillez sélectionner un étudiant</h2>
    </div>
            <form  action="tdb/tdb_enseignant.php" method="get" style="display:flex; align-items: center; gap:10px;">
                   <label for="Id_Etudiant">Étudiant :</label>
                   <select name="Id_Etudiant" id="Id_Etudiant" required onchange="this.form.submit()">
                     <option value="" disabled selected>Sélectionner un étudiant</option>
                        <?php foreach ($etudiants as $etudiant): ?>
                            <option value="<?= htmlspecialchars($etudiant['Id']) ?>" <?= $idEtudiantSelectionne == $etudiant['Id'] ? 'selected' : '' ?>><?= htmlspecialchars($etudiant['prenom']) . ' ' . htmlspecialchars($etudiant['nom']) ?></option>
                         <?php endforeach; ?>
                    </select>
                 </form>
                 <?php if (isset($actions) && !empty($actions)) : ?>
              <div class="notifications-container">
              <h1>Les Actions de l'Etudiant</h2>
                    <ul>
                         <?php foreach ($actions as $action): ?>
                           <li>
                              <h3><?= htmlspecialchars($action['libelle']) ?></h3>
                                <p>Date de rendu : <?= htmlspecialchars($action['date_realisation']) ?></p>
                                 <?php if (!empty($action['lienDocument'])): ?>
                                  <a href="<?= htmlspecialchars($action['lienDocument']) ?>" download>Télécharger le fichier rendu</a>
                                  <?php else : ?>
                                     <p>Aucun fichier déposé.</p>
                                 <?php endif; ?>
                           </li>
                       <?php endforeach; ?>
                   </ul>
                 </div>
                <?php endif; ?>
    </div>
    <script src="../../public/js/script_2.js"></script>
</body>
</html>
