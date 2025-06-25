<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Message.php';
require_once __DIR__ . '/../models/Document.php';
require_once __DIR__ . '/../models/Evenement.php';
require_once __DIR__ . '/../models/Action.php';

class DashboardController extends Controller {
    public function index() {
        // Vérifier que l'utilisateur est connecté
        $this->requireAuth();
        
        $user_id = $this->user->Id;
        
        // Récupérer les données pour le dashboard
        $messages_recents = array_slice(Message::messagesRecus($user_id), 0, 5);
        $documents_recents = array_slice(Document::getDocumentsRecus($user_id), 0, 5);
        $evenements_prochains = Evenement::getEvenementsProchains($user_id, $this->role, 10);
        $tous_les_users = Document::getAllUsers();
        
        // Statistiques
        $total_messages_recus = count(Message::messagesRecus($user_id));
        $total_messages_envoyes = count(Message::messagesEnvoyes($user_id));
        $total_documents_recus = count(Document::getDocumentsRecus($user_id));
        $total_documents_envoyes = count(Document::getDocumentsEnvoyes($user_id));
        
        $data = [
            'messages_recents' => $messages_recents,
            'documents_recents' => $documents_recents,
            'evenements_prochains' => $evenements_prochains,
            'tous_les_users' => $tous_les_users,
            'stats' => [
                'messages_recus' => $total_messages_recus,
                'messages_envoyes' => $total_messages_envoyes,
                'documents_recus' => $total_documents_recus,
                'documents_envoyes' => $total_documents_envoyes
            ]
        ];
        
        // Ajouter les données spécifiques aux stages selon le rôle
        switch($this->role) {
            case 'eleve':
                $data['mes_stages'] = $this->getStudentStages($user_id);
                $data['stats']['stages_actifs'] = $this->getActiveStagesCount($user_id);
                break;
                
            case 'enseignant':
            case 'tuteur':
                $data['stages_supervises'] = $this->getTutorStages($user_id);
                break;
                
            case 'tuteur_entreprise':
                $data['stages_entreprise'] = $this->getEntrepriseStages($user_id);
                break;
                
            case 'admin':
                $data['tous_stages'] = $this->getAllStages();
                break;
        }
        
        // Utiliser la méthode render de la classe parent
        $this->render('common/dashboard', $data);
    }
    
    /**
     * Récupère les stages d'un étudiant avec informations complètes
     */
    private function getStudentStages($user_id) {
        try {
            $pdo = $this->getDatabase();
            $stmt = $pdo->prepare("
                SELECT s.*, 
                       -- Informations entreprise
                       e.adresse as entreprise_adresse,
                       e.code_postal as entreprise_cp,
                       e.ville as entreprise_ville,
                       e.tel as entreprise_tel,
                       e.indicationVisite as entreprise_visite,
                       
                       -- Tuteur pédagogique (enseignant)
                       u_tuteur.nom as tuteur_nom, 
                       u_tuteur.prenom as tuteur_prenom,
                       u_tuteur.email as tuteur_email,
                       u_tuteur.telephone as tuteur_tel,
                       ens.Bureau as tuteur_bureau,
                       
                       -- Tuteur entreprise
                       u_tuteur_ent.nom as tuteur_ent_nom,
                       u_tuteur_ent.prenom as tuteur_ent_prenom,
                       u_tuteur_ent.email as tuteur_ent_email,
                       u_tuteur_ent.telephone as tuteur_ent_tel,
                       
                       -- Informations académiques
                       an.libelle as annee_libelle,
                       dep.Libelle as departement,
                       
                       -- Statut calculé
                       CASE 
                           WHEN s.date_fin < CURDATE() THEN 'termine'
                           WHEN s.date_debut <= CURDATE() AND s.date_fin >= CURDATE() THEN 'en_cours'
                           WHEN s.date_debut > CURDATE() THEN 'planifie'
                           ELSE 'en_cours'
                       END as statut
                       
                FROM stage s 
                LEFT JOIN tuteur_entreprise te ON s.Id_TuteurEntreprise = te.Id_TuteurEntreprise
                LEFT JOIN entreprise e ON te.Id_Entreprise = e.Id_Entreprise
                LEFT JOIN utilisateur u_tuteur ON s.Id_Enseignant = u_tuteur.Id
                LEFT JOIN enseignant ens ON s.Id_Enseignant = ens.Id_Enseignant
                LEFT JOIN utilisateur u_tuteur_ent ON te.Id_TuteurEntreprise = u_tuteur_ent.Id
                LEFT JOIN annee an ON s.Id_Annee = an.Id_Annee
                LEFT JOIN departement dep ON s.Id_Departement = dep.Id_Departement
                WHERE s.Id_Etudiant = ?
                ORDER BY s.date_debut DESC
            ");
            $stmt->execute([$user_id]);
            $stages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $stages;
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Compte les stages actifs d'un étudiant
     */
    private function getActiveStagesCount($user_id) {
        try {
            $pdo = $this->getDatabase();
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as total 
                FROM stage 
                WHERE Id_Etudiant = ? 
                AND date_debut <= CURDATE() 
                AND date_fin >= CURDATE()
            ");
            $stmt->execute([$user_id]);
            return $stmt->fetch()['total'];
        } catch (Exception $e) {
            return 0;
        }
    }
    
        /**
     * Récupère les stages supervisés par un tuteur/enseignant
     */
    private function getTutorStages($user_id) {
        try {
            $pdo = $this->getDatabase();
            $stmt = $pdo->prepare("
                SELECT s.*, 
                       -- Informations entreprise
                       e.Id_Entreprise,
                       e.adresse as entreprise_adresse,
                       e.code_postal as entreprise_cp,
                       e.ville as entreprise_ville,
                       e.tel as entreprise_tel,
                       e.indicationVisite as entreprise_visite,
                       
                       -- Informations étudiant
                       u_etudiant.nom as etudiant_nom, 
                       u_etudiant.prenom as etudiant_prenom,
                       u_etudiant.email as etudiant_email,
                       u_etudiant.telephone as etudiant_tel,
                       
                       -- Tuteur entreprise
                       u_tuteur_ent.nom as tuteur_ent_nom,
                       u_tuteur_ent.prenom as tuteur_ent_prenom,
                       u_tuteur_ent.email as tuteur_ent_email,
                       u_tuteur_ent.telephone as tuteur_ent_tel,
                       
                       -- Informations académiques
                       an.libelle as annee_libelle,
                       dep.Libelle as departement,
                       
                       -- Statut
                       CASE 
                           WHEN s.date_fin < CURDATE() THEN 'termine'
                           WHEN s.date_debut <= CURDATE() AND s.date_fin >= CURDATE() THEN 'en_cours'
                           WHEN s.date_debut > CURDATE() THEN 'planifie'
                           ELSE 'en_cours'
                       END as statut
                       
                FROM stage s
                LEFT JOIN utilisateur u_etudiant ON s.Id_Etudiant = u_etudiant.Id
                LEFT JOIN utilisateur u_tuteur_ent ON s.Id_TuteurEntreprise = u_tuteur_ent.Id
                LEFT JOIN tuteur_entreprise te ON s.Id_TuteurEntreprise = te.Id_TuteurEntreprise
                LEFT JOIN entreprise e ON te.Id_Entreprise = e.Id_Entreprise
                LEFT JOIN annee an ON s.Id_Annee = an.Id_Annee
                LEFT JOIN departement dep ON s.Id_Departement = dep.Id_Departement
                WHERE s.Id_Enseignant = ?
                ORDER BY s.date_debut DESC
            ");
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Récupère les stages dans l'entreprise d'un tuteur d'entreprise
     */
    private function getEntrepriseStages($user_id) {
        try {
            $pdo = $this->getDatabase();
            $stmt = $pdo->prepare("
                SELECT s.*, 
                       -- Informations entreprise
                       e.Id_Entreprise,
                       e.adresse as entreprise_adresse,
                       e.code_postal as entreprise_cp,
                       e.ville as entreprise_ville,
                       e.tel as entreprise_tel,
                       e.indicationVisite as entreprise_visite,
                       
                       -- Informations étudiant
                       u_etudiant.nom as etudiant_nom, 
                       u_etudiant.prenom as etudiant_prenom,
                       u_etudiant.email as etudiant_email,
                       u_etudiant.telephone as etudiant_tel,
                       
                       -- Tuteur pédagogique
                       u_tuteur.nom as tuteur_nom, 
                       u_tuteur.prenom as tuteur_prenom,
                       u_tuteur.email as tuteur_email,
                       u_tuteur.telephone as tuteur_tel,
                       ens.Bureau as tuteur_bureau,
                       
                       -- Informations académiques
                       an.libelle as annee_libelle,
                       dep.Libelle as departement,
                       
                       -- Statut
                       CASE 
                           WHEN s.date_fin < CURDATE() THEN 'termine'
                           WHEN s.date_debut <= CURDATE() AND s.date_fin >= CURDATE() THEN 'en_cours'
                           WHEN s.date_debut > CURDATE() THEN 'planifie'
                           ELSE 'en_cours'
                       END as statut
                       
                FROM stage s
                LEFT JOIN utilisateur u_etudiant ON s.Id_Etudiant = u_etudiant.Id
                LEFT JOIN utilisateur u_tuteur ON s.Id_Enseignant = u_tuteur.Id
                LEFT JOIN enseignant ens ON s.Id_Enseignant = ens.Id_Enseignant
                LEFT JOIN tuteur_entreprise te ON s.Id_TuteurEntreprise = te.Id_TuteurEntreprise
                LEFT JOIN entreprise e ON te.Id_Entreprise = e.Id_Entreprise
                LEFT JOIN annee an ON s.Id_Annee = an.Id_Annee
                LEFT JOIN departement dep ON s.Id_Departement = dep.Id_Departement
                WHERE s.Id_TuteurEntreprise = ?
                ORDER BY s.date_debut DESC
            ");
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Récupère tous les stages (pour l'admin)
     */
    private function getAllStages() {
        try {
            $pdo = $this->getDatabase();
            $stmt = $pdo->query("
                SELECT s.*, 
                       -- Informations entreprise
                       e.Id_Entreprise,
                       e.adresse as entreprise_adresse,
                       e.code_postal as entreprise_cp,
                       e.ville as entreprise_ville,
                       e.tel as entreprise_tel,
                       e.indicationVisite as entreprise_visite,
                       
                       -- Informations étudiant
                       u_etudiant.nom as etudiant_nom, 
                       u_etudiant.prenom as etudiant_prenom,
                       u_etudiant.email as etudiant_email,
                       u_etudiant.telephone as etudiant_tel,
                       
                       -- Tuteur pédagogique
                       u_tuteur.nom as tuteur_nom, 
                       u_tuteur.prenom as tuteur_prenom,
                       u_tuteur.email as tuteur_email,
                       u_tuteur.telephone as tuteur_tel,
                       ens.Bureau as tuteur_bureau,
                       
                       -- Tuteur entreprise
                       u_tuteur_ent.nom as tuteur_ent_nom,
                       u_tuteur_ent.prenom as tuteur_ent_prenom,
                       u_tuteur_ent.email as tuteur_ent_email,
                       u_tuteur_ent.telephone as tuteur_ent_tel,
                       
                       -- Informations académiques
                       an.libelle as annee_libelle,
                       dep.Libelle as departement,
                       
                       -- Statut
                       CASE 
                           WHEN s.date_fin < CURDATE() THEN 'termine'
                           WHEN s.date_debut <= CURDATE() AND s.date_fin >= CURDATE() THEN 'en_cours'
                           WHEN s.date_debut > CURDATE() THEN 'planifie'
                           ELSE 'en_cours'
                       END as statut
                       
                FROM stage s
                LEFT JOIN utilisateur u_etudiant ON s.Id_Etudiant = u_etudiant.Id
                LEFT JOIN utilisateur u_tuteur ON s.Id_Enseignant = u_tuteur.Id
                LEFT JOIN enseignant ens ON s.Id_Enseignant = ens.Id_Enseignant
                LEFT JOIN utilisateur u_tuteur_ent ON s.Id_TuteurEntreprise = u_tuteur_ent.Id
                LEFT JOIN tuteur_entreprise te ON s.Id_TuteurEntreprise = te.Id_TuteurEntreprise
                LEFT JOIN entreprise e ON te.Id_Entreprise = e.Id_Entreprise
                LEFT JOIN annee an ON s.Id_Annee = an.Id_Annee
                LEFT JOIN departement dep ON s.Id_Departement = dep.Id_Departement
                ORDER BY s.date_debut DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    


    /**
     * Obtient la connexion à la base de données
     */
    private function getDatabase() {
        require_once __DIR__ . '/../../config/database.php';
        global $pdo;
        return $pdo;
    }
} 