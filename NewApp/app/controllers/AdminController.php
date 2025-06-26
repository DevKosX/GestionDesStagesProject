<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Action.php';
require_once __DIR__ . '/../models/Enterprise.php';
require_once __DIR__ . '/../models/ActionType.php';
require_once __DIR__ . '/../models/Stage.php';
require_once __DIR__ . '/../models/Department.php';

class AdminController extends Controller {
    
    public function __construct() {
        parent::__construct();
        // Vérifier que l'utilisateur est admin sera fait dans chaque méthode
    }
    
    /**
     * Page principale d'administration
     */
    public function index() {
        $this->requireAdminAuth();
        
        $data = [
            'stats' => User::getStats()
        ];
        
        $this->renderAdmin('dashboard', $data);
    }
    
    /**
     * Helper méthodes pour simplifier le code et éviter les répétitions
     */
    
    /**
     * Vérification d'authentification admin (combine requireAuth + checkAdminRole)
     */
    private function requireAdminAuth() {
        $this->requireAuth();
        $this->checkAdminRole();
    }
    
    /**
     * Render spécifique pour les vues admin avec user automatiquement inclus
     */
    private function renderAdmin($view, $data = []) {
        $data['user'] = $this->getCurrentUser();
        $this->render('admin/' . $view, $data);
    }
    
    /**
     * Redirection avec message flash
     */
    private function redirectWithSuccess($url, $message_key) {
        header('Location: ' . url($url) . '?success=' . $message_key);
        exit;
    }
    
    /**
     * Gestion des utilisateurs
     */

    
    /**
     * Liste des utilisateurs par rôle
     */
    public function usersByRole() {
        $this->requireAdminAuth();
        
        $role = $_GET['role'] ?? '';
        $search = $_GET['search'] ?? '';
        
        $users = $this->filterUsersBySearch(User::getUsersByRole($role), $search);
        
        $data = [
            'users' => $users,
            'role' => $role,
            'role_display' => $this->getRoleDisplayName($role),
            'search' => $search
        ];
        
        $this->renderAdmin('users_by_role', $data);
    }
    
    /**
     * Filtrer les utilisateurs par terme de recherche
     */
    private function filterUsersBySearch($users, $search) {
        if (!$search) return $users;
        
        $searchTerm = strtolower($search);
        return array_filter($users, function($user) use ($searchTerm) {
            return strpos(strtolower($user['nom']), $searchTerm) !== false ||
                   strpos(strtolower($user['prenom']), $searchTerm) !== false ||
                   strpos(strtolower($user['email']), $searchTerm) !== false;
        });
    }
    
    /**
     * Obtenir le nom d'affichage d'un rôle
     */
    private function getRoleDisplayName($role) {
        $roleDisplayNames = [
            'etudiants' => 'Étudiants',
            'enseignants' => 'Enseignants',
            'tuteur_entreprise' => 'Tuteurs Entreprise',
            'administrateurs' => 'Administrateurs'
        ];
        return $roleDisplayNames[$role] ?? 'Utilisateurs';
    }
    
    /**
     * Créer un nouveau utilisateur
     */
    public function createUser() {
        $this->requireAuth();
        $this->checkAdminRole();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validation des données d'entrée
                $userData = [
                    'nom' => trim($_POST['nom']),
                    'prenom' => trim($_POST['prenom']),
                    'email' => trim($_POST['email']),
                    'telephone' => trim($_POST['telephone'] ?? ''),
                    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                    'role' => $_POST['role']
                ];
                
                // Validation
                if (empty($userData['nom']) || empty($userData['prenom']) || empty($userData['email'])) {
                    throw new Exception("Les champs nom, prénom et email sont obligatoires");
                }
                
                if (empty($_POST['password']) || strlen($_POST['password']) < 6) {
                    throw new Exception("Le mot de passe doit contenir au moins 6 caractères");
                }
                
                if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Email invalide");
                }
                
                if (empty($userData['role'])) {
                    throw new Exception("Vous devez sélectionner un rôle");
                }
                
                // Vérifier si l'email existe déjà
                if (User::emailExists($userData['email'])) {
                    throw new Exception("Cet email est déjà utilisé par un autre utilisateur");
                }
                
                // Validation spécifique selon le rôle
                if ($userData['role'] === 'tuteur_entreprise') {
                    if (empty($_POST['ville_entreprise']) || empty($_POST['adresse_entreprise'])) {
                        throw new Exception("Pour un tuteur entreprise, la ville et l'adresse de l'entreprise sont obligatoires");
                    }
                }
                
                // Log pour debug
                error_log("Création utilisateur - données: " . json_encode($userData));
                
                // Créer l'utilisateur principal
                $userId = User::create($userData);
                
                if (!$userId) {
                    throw new Exception("Erreur lors de la création de l'utilisateur principal");
                }
                
                // Log pour debug
                error_log("Utilisateur créé avec ID: " . $userId . ", création du rôle: " . $userData['role']);
                
                // Créer l'entrée dans la table de rôle appropriée
                User::createRoleEntry($userId, $userData['role'], $_POST);
                
                // Log pour debug
                error_log("Rôle créé avec succès pour l'utilisateur ID: " . $userId);
                
                // Affichage du formulaire avec message de succès
                $data = [
                    'success' => 'Utilisateur créé avec succès !',
                    'roles_available' => User::getAvailableRoles(),
                    'departements' => Department::getAll()
                ];
                
                $this->render('admin/create_user', $data);
                return;
                
            } catch (Exception $e) {
                // Log l'erreur pour debug
                error_log("Erreur création utilisateur: " . $e->getMessage());
                
                $data = [
                    'error' => $e->getMessage(),
                    'form_data' => $_POST,
                    'roles_available' => User::getAvailableRoles(),
                    'departements' => Department::getAll()
                ];
                
                $this->render('admin/create_user', $data);
                return;
            }
        }
        
        // Affichage du formulaire
        $data = [
            'roles_available' => User::getAvailableRoles(),
            'departements' => Department::getAll()
        ];
        
        $this->render('admin/create_user', $data);
    }
    
    /**
     * Gestion des types d'actions
     */
    public function actionTypes() {
        $this->requireAuth();
        $this->checkAdminRole();
        
        $data = [
            'action_types' => ActionType::getAll()
        ];
        
        $this->render('admin/action_types', $data);
    }
    
    /**
     * Créer un nouveau type d'action
     */
    public function createActionType() {
        $this->requireAuth();
        $this->checkAdminRole();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $actionTypeData = [
                    'libelle' => trim($_POST['libelle']),
                    'Executant' => $_POST['executant'],
        
                    'date_fin' => $_POST['date_fin'],
                    'requisDoc' => isset($_POST['requis_doc']) ? 1 : 0
                ];
                
                // Validation
                if (empty($actionTypeData['libelle'])) {
                    throw new Exception("Le libellé est obligatoire");
                }
                
                if (empty($actionTypeData['date_fin'])) {
                    throw new Exception("La date de fin est obligatoire");
                }
                
                // Vérifier que la date de fin est dans le futur
                if (strtotime($actionTypeData['date_fin']) < time()) {
                    throw new Exception("La date de fin doit être dans le futur");
                }
                
                // Insérer le type d'action et récupérer son ID
                $typeActionId = ActionType::create($actionTypeData);
                
                // Assigner automatiquement aux utilisateurs du rôle exécutant
                $nbAssignations = ActionType::assignToRole($typeActionId, $actionTypeData['Executant']);
                
                // Affichage du formulaire avec message de succès
                $data = [
                    'success' => "Type d'action créé avec succès et assigné à {$nbAssignations} utilisateur(s) !",
                    'executants_available' => ActionType::getExecutantsAvailable()
                ];
                
                $this->render('admin/create_action_type', $data);
                return;
                
            } catch (Exception $e) {
                $data = [
                    'error' => $e->getMessage(),
                    'form_data' => $_POST,
                    'executants_available' => ActionType::getExecutantsAvailable()
                ];
                
                $this->render('admin/create_action_type', $data);
                return;
            }
        }
        
        $data = [
            'executants_available' => ActionType::getExecutantsAvailable()
        ];
        
        $this->render('admin/create_action_type', $data);
    }
    
    // Méthode assignActions supprimée - assignation automatique lors de la création
    
    // =================== MÉTHODES PRIVÉES ===================
    
    /**
     * Vérifier que l'utilisateur a le rôle admin
     */
    private function checkAdminRole() {
        if ($this->role !== 'admin') {
            $this->redirectWithMessage('dashboard', 'error', 'Accès non autorisé');
        }
    }
    

    


    
    /**
     * Vérifier si un email existe déjà
     */
    private function emailExists($email) {
        try {
            $pdo = $this->getDatabase();
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Insérer un nouvel utilisateur
     */
    private function insertUser($userData) {
        $pdo = $this->getDatabase();
        
        $stmt = $pdo->prepare("
            INSERT INTO utilisateur (nom, prenom, email, telephone, mot_de_passe) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $userData['nom'],
            $userData['prenom'],
            $userData['email'],
            $userData['telephone'],
            $userData['password']
        ]);
        
        return $pdo->lastInsertId();
    }
    
    /**
     * Créer l'entrée dans la table de rôle appropriée
     */
    private function createRoleEntry($userId, $role, $postData) {
        $pdo = $this->getDatabase();
        
        switch ($role) {
            case 'etudiant':
                $stmt = $pdo->prepare("
                    INSERT INTO etudiant (Id_Etudiant) 
                    VALUES (?)
                ");
                $stmt->execute([$userId]);
                break;
                
            case 'enseignant':
                $stmt = $pdo->prepare("
                    INSERT INTO enseignant (Id_Enseignant, Bureau) 
                    VALUES (?, ?)
                ");
                $stmt->execute([
                    $userId,
                    $postData['bureau'] ?? ''
                ]);
                break;
                
            case 'tuteur_entreprise':
                // Validation des champs obligatoires pour tuteur entreprise
                if (empty($postData['ville_entreprise']) || empty($postData['adresse_entreprise'])) {
                    throw new Exception("La ville et l'adresse de l'entreprise sont obligatoires pour un tuteur entreprise");
                }
                
                // Créer une nouvelle entreprise avec les informations saisies
                $stmt = $pdo->prepare("
                    INSERT INTO entreprise (adresse, ville) 
                    VALUES (?, ?)
                ");
                $stmt->execute([
                    trim($postData['adresse_entreprise']),
                    trim($postData['ville_entreprise'])
                ]);
                
                $entrepriseId = $pdo->lastInsertId();
                
                // Créer l'entrée tuteur_entreprise avec la nouvelle entreprise
                $stmt = $pdo->prepare("
                    INSERT INTO tuteur_entreprise (Id_TuteurEntreprise, Id_Entreprise) 
                    VALUES (?, ?)
                ");
                $stmt->execute([
                    $userId,
                    $entrepriseId
                ]);
                break;
                
            case 'admin':
                $stmt = $pdo->prepare("
                    INSERT INTO administrateur (Id_Administrateur) 
                    VALUES (?)
                ");
                $stmt->execute([$userId]);
                break;
        }
    }
    
    /**
     * Récupérer les rôles disponibles
     */
    private function getAvailableRoles() {
        return [
            'etudiant' => 'Étudiant',
            'enseignant' => 'Enseignant',
            'tuteur_entreprise' => 'Tuteur Entreprise',
            'admin' => 'Administrateur'
        ];
    }
    
    /**
     * Récupérer les départements
     */
    private function getDepartements() {
        try {
            $pdo = $this->getDatabase();
            $stmt = $pdo->query("SELECT * FROM departement ORDER BY Libelle");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    

    
    /**
     * Récupérer tous les types d'actions
     */
    private function getAllActionTypes() {
        try {
            $pdo = $this->getDatabase();
            $stmt = $pdo->query("SELECT * FROM typeaction ORDER BY libelle");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    // Méthode getAllActionTypesWithAssignments supprimée - interface simplifiée
    
    /**
     * Insérer un nouveau type d'action et retourner son ID
     */
    private function insertActionType($data) {
        $pdo = $this->getDatabase();
        
        $stmt = $pdo->prepare("
            INSERT INTO typeaction (libelle, Executant, delaiEnJours, ReferenceDelai, requisDoc) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        // Calculer le délai en jours à partir de la date de fin (on stocke juste le délai mais on permet la saisie par date)
        $dateDebut = new DateTime();
        $dateFin = new DateTime($data['date_fin']);
        $delaiEnJours = max(1, $dateDebut->diff($dateFin)->days); // Minimum 1 jour
        
        $stmt->execute([
            $data['libelle'],
            $data['Executant'],
            $delaiEnJours,
            'date_fin', // On standardise sur date_fin comme référence
            $data['requisDoc']
        ]);
        
        return $pdo->lastInsertId();
    }
    
    /**
     * Récupérer les exécutants disponibles
     */
    private function getExecutantsAvailable() {
        return [
            'Etudiant' => 'Étudiant',
            'Tuteur pédagogique' => 'Tuteur pédagogique',
            'Tuteur entreprise' => 'Tuteur entreprise',
            'Secrétaire' => 'Secrétaire'
        ];
    }
    

    
    /**
     * Insérer une action pour tous les utilisateurs d'un rôle
     */
    private function insertActionByRole($data) {
        $pdo = $this->getDatabase();
        
        // Récupérer tous les utilisateurs du rôle spécifié
        $users = $this->getUsersByRole($data['role']);
        
        if (empty($users)) {
            return 0; // Retourner 0 au lieu de lancer une exception
        }
        
        // Préparer la requête d'insertion (utiliser Id_Etudiant car la table action est centrée sur les étudiants)
        $stmt = $pdo->prepare("
            INSERT INTO action (Id_TypeAction, Id_Etudiant) 
            VALUES (?, ?)
        ");
        
        $nbAssignations = 0;
        // Insérer une action pour chaque utilisateur du rôle (filtrer seulement les étudiants pour l'instant)
        foreach ($users as $user) {
                         // Ne créer des actions que pour les étudiants car la table action utilise Id_Etudiant
             if ($data['role'] === 'etudiants') {
                 $success = $stmt->execute([
                     $data['Id_TypeAction'],
                     $user['id']
                 ]);
                 if ($success) {
                     $nbAssignations++;
                 }
             }
         }
        
        return $nbAssignations;
    }
    
    /**
     * Convertir l'exécutant en rôle pour l'assignation
     */
    private function convertExecutantToRole($executant) {
        $mapping = [
            'Etudiant' => 'etudiants',
            'Tuteur pédagogique' => 'enseignants',
            'Tuteur entreprise' => 'tuteur_entreprise',
            'Secrétaire' => 'administrateurs' // Les secrétaires sont dans le rôle admin
        ];
        
        return $mapping[$executant] ?? 'etudiants';
    }
    
    // Méthodes getAssignments et deleteAssignment supprimées - interface simplifiée
    

    
    /**
     * Récupérer les utilisateurs récents
     */
    private function getRecentUsers() {
        try {
            $pdo = $this->getDatabase();
            $stmt = $pdo->query("
                SELECT u.*, 
                       CASE 
                           WHEN e.Id_Etudiant IS NOT NULL THEN 'Étudiant'
                           WHEN ens.Id_Enseignant IS NOT NULL THEN 'Enseignant'
                           WHEN te.Id_TuteurEntreprise IS NOT NULL THEN 'Tuteur Entreprise'
                           WHEN a.Id_Administrateur IS NOT NULL THEN 'Administrateur'
                           ELSE 'Non défini'
                       END as role_display
                FROM utilisateur u
                LEFT JOIN etudiant e ON u.Id = e.Id_Etudiant
                LEFT JOIN enseignant ens ON u.Id = ens.Id_Enseignant
                LEFT JOIN tuteur_entreprise te ON u.Id = te.Id_TuteurEntreprise
                LEFT JOIN administrateur a ON u.Id = a.Id_Administrateur
                ORDER BY u.Id DESC
                LIMIT 5
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Récupérer les actions récentes
     */
    private function getRecentActions() {
        try {
            $pdo = $this->getDatabase();
            $stmt = $pdo->query("
                SELECT a.*, ta.libelle, u.nom, u.prenom
                FROM action a
                JOIN typeaction ta ON a.Id_TypeAction = ta.Id_TypeAction
                JOIN utilisateur u ON a.Id_Etudiant = u.Id
                ORDER BY a.Id_Action DESC
                LIMIT 5
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Modifier un utilisateur
     */
    public function updateUser() {
        $this->requireAdminAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['id']) || !isset($input['role'])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Données manquantes']);
            return;
        }
        
        try {
            $pdo = $this->getDatabase();
            
            $roleTable = '';
            $roleField = '';
            
            switch ($input['role']) {
                case 'etudiants':
                    $roleTable = 'etudiant';
                    $roleField = 'Id_Etudiant';
                    break;
                case 'enseignants':
                    $roleTable = 'enseignant';
                    $roleField = 'Id_Enseignant';
                    break;
                case 'tuteur_entreprise':
                    $roleTable = 'tuteur_entreprise';
                    $roleField = 'Id_TuteurEntreprise';
                    break;
                case 'administrateurs':
                    $roleTable = 'administrateur';
                    $roleField = 'Id_Administrateur';
                    break;
                default:
                    throw new Exception('Rôle invalide');
            }
            
            // Commencer une transaction pour mettre à jour utilisateur et table de rôle
            $pdo->beginTransaction();
            
            try {
                // Mise à jour dans la table utilisateur
                $stmt = $pdo->prepare("
                    UPDATE utilisateur 
                    SET nom = ?, prenom = ?, email = ?, telephone = ? 
                    WHERE Id = ?
                ");
                
                $success1 = $stmt->execute([
                    $input['nom'],
                    $input['prenom'],
                    $input['email'],
                    $input['telephone'] ?? null,
                    $input['id']
                ]);
                
                // Mise à jour dans la table de rôle spécifique si nécessaire
                $success2 = true; // Par défaut
                
                // La table etudiant n'a que Id_Etudiant, pas de champs supplémentaires à mettre à jour
                if ($input['role'] === 'etudiants') {
                    $success2 = true; // Pas de mise à jour nécessaire pour la table etudiant
                } elseif ($input['role'] === 'enseignants' && isset($input['bureau'])) {
                    $stmtRole = $pdo->prepare("UPDATE enseignant SET Bureau = ? WHERE Id_Enseignant = ?");
                    $success2 = $stmtRole->execute([$input['bureau'], $input['id']]);
                }
                
                if ($success1 && $success2) {
                    $pdo->commit();
                    $success = true;
                } else {
                    $pdo->rollback();
                    $success = false;
                }
                
            } catch (Exception $e) {
                $pdo->rollback();
                throw $e;
            }
            
            header('Content-Type: application/json');
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Utilisateur modifié avec succès']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la modification']);
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Supprimer un utilisateur
     */
    public function deleteUser() {
        $this->requireAdminAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['id']) || !isset($input['role'])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Données manquantes']);
            return;
        }
        
        try {
            $pdo = $this->getDatabase();
            
            $roleTable = '';
            $roleField = '';
            
            switch ($input['role']) {
                case 'etudiants':
                    $roleTable = 'etudiant';
                    $roleField = 'Id_Etudiant';
                    break;
                case 'enseignants':
                    $roleTable = 'enseignant';
                    $roleField = 'Id_Enseignant';
                    break;
                case 'tuteur_entreprise':
                    $roleTable = 'tuteur_entreprise';
                    $roleField = 'Id_TuteurEntreprise';
                    break;
                case 'administrateurs':
                    $roleTable = 'administrateur';
                    $roleField = 'Id_Administrateur';
                    break;
                default:
                    throw new Exception('Rôle invalide');
            }
            
            // Gérer les contraintes de clé étrangère avant suppression
            $pdo->beginTransaction();
            
            try {
                // ÉTAPE 1 : Gérer les messages (commun à tous les rôles)
                // Supprimer tous les messages liés à cet utilisateur (expéditeur ET destinataire)
                $stmtDeleteMessagesExp = $pdo->prepare("DELETE FROM message WHERE expediteur_id = ?");
                $stmtDeleteMessagesExp->execute([$input['id']]);
                
                $stmtDeleteMessagesDest = $pdo->prepare("DELETE FROM message WHERE destinataire_id = ?");
                $stmtDeleteMessagesDest->execute([$input['id']]);
                
                // Supprimer aussi les vues de sections si la table existe
                try {
                    $stmtCheckTable = $pdo->query("SHOW TABLES LIKE 'vues_sections'");
                    if ($stmtCheckTable->rowCount() > 0) {
                        $stmtDeleteVues = $pdo->prepare("DELETE FROM vues_sections WHERE user_id = ?");
                        $stmtDeleteVues->execute([$input['id']]);
                    }
                } catch (Exception $e) {
                    // Table n'existe pas, continuer sans erreur
                    error_log("Table vues_sections n'existe pas : " . $e->getMessage());
                }
                
                // ÉTAPE 2 : Vérifier les références selon le rôle
                switch ($input['role']) {
                    case 'etudiants':
                        // Supprimer automatiquement toutes les dépendances de l'étudiant
                        
                        // 1. Supprimer les actions de l'étudiant
                        $stmtDeleteActions = $pdo->prepare("DELETE FROM action WHERE Id_Etudiant = ?");
                        $stmtDeleteActions->execute([$input['id']]);
                        
                        // 2. Supprimer les inscriptions de l'étudiant
                        $stmtDeleteInscriptions = $pdo->prepare("DELETE FROM inscription WHERE Id_Etudiant = ?");
                        $stmtDeleteInscriptions->execute([$input['id']]);
                        
                        // 3. Supprimer les stages de l'étudiant
                        $stmtDeleteStages = $pdo->prepare("DELETE FROM stage WHERE Id_Etudiant = ?");
                        $stmtDeleteStages->execute([$input['id']]);
                        break;
                        
                    case 'enseignants':
                        // Mettre à NULL les références dans les stages (au lieu de bloquer)
                        $stmtUpdate = $pdo->prepare("UPDATE stage SET Id_Enseignant = NULL WHERE Id_Enseignant = ?");
                        $stmtUpdate->execute([$input['id']]);
                        
                        // Mettre à NULL les références dans les semestres
                        $stmtUpdateSemestre = $pdo->prepare("UPDATE semestre SET Id_Enseignant = NULL WHERE Id_Enseignant = ?");
                        $stmtUpdateSemestre->execute([$input['id']]);
                        break;
                        
                    case 'tuteur_entreprise':
                        // Mettre à NULL les références dans les stages (tuteur entreprise est optionnel)
                        $stmtUpdate = $pdo->prepare("UPDATE stage SET Id_TuteurEntreprise = NULL WHERE Id_TuteurEntreprise = ?");
                        $stmtUpdate->execute([$input['id']]);
                        break;
                        
                    case 'administrateurs':
                        // Aucune contrainte particulière pour les administrateurs (sauf messages déjà traités)
                        break;
                }
                
                // Supprimer de la table de rôle
                $stmt1 = $pdo->prepare("DELETE FROM {$roleTable} WHERE {$roleField} = ?");
                $success1 = $stmt1->execute([$input['id']]);
                
                // Supprimer de la table utilisateur
                $stmt2 = $pdo->prepare("DELETE FROM utilisateur WHERE Id = ?");
                $success2 = $stmt2->execute([$input['id']]);
                
                if ($success1 && $success2) {
                    $pdo->commit();
                    $success = true;
                } else {
                    throw new Exception("Erreur lors de la suppression des données utilisateur");
                }
                
            } catch (Exception $e) {
                $pdo->rollback();
                throw $e;
            }
            
            header('Content-Type: application/json');
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Utilisateur supprimé avec succès']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
        }
    }

    /**
     * Modifier un type d'action
     */
    public function updateActionType() {
        $this->requireAdminAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['id'])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Données manquantes']);
            return;
        }
        
        try {
            $data = [
                'libelle' => $input['libelle'],
                'Executant' => $input['executant'],
    
                'date_fin' => $input['date_fin'],
                'requisDoc' => $input['requis_doc'] ? 1 : 0
            ];
            
            $success = ActionType::update($input['id'], $data);
            
            header('Content-Type: application/json');
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Type d\'action modifié avec succès']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la modification']);
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Supprimer un type d'action
     */
    public function deleteActionType() {
        $this->requireAdminAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['id'])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Données manquantes']);
            return;
        }
        
        try {
            $success = ActionType::delete($input['id']);
            
            header('Content-Type: application/json');
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Type d\'action supprimé avec succès']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
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
    
    /**
     * Récupère l'utilisateur connecté
     */
    private function getCurrentUser() {
        return $this->user;
    }

    /**
     * Afficher le formulaire de création de stage
     */
    public function createStage() {
        $this->requireAdminAuth();
        
        // Récupérer les données pour le formulaire
        $formData = Stage::getFormData();
        $error = null;
        $postData = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $postData = [
                    'Id_Annee' => (int)$_POST['Id_Annee'],
                    'Id_Etudiant' => (int)$_POST['Id_Etudiant'],
                    'Id_Enseignant' => (int)$_POST['Id_Enseignant'],
                    'Id_TuteurEntreprise' => !empty($_POST['Id_TuteurEntreprise']) ? (int)$_POST['Id_TuteurEntreprise'] : null,
                    'date_debut' => $_POST['date_debut'],
                    'date_fin' => $_POST['date_fin'],
                    'mission' => trim($_POST['mission']),
                    'date_soutenance' => !empty($_POST['date_soutenance']) ? $_POST['date_soutenance'] : null,
                    'salle_Soutenance' => !empty($_POST['salle_Soutenance']) ? trim($_POST['salle_Soutenance']) : null
                ];
                
                // Validation
                $errors = Stage::validate($postData);
                if (!empty($errors)) {
                    throw new Exception(implode(', ', $errors));
                }
                
                // Créer le stage
                Stage::create($postData);
                
                // Affichage avec succès
                $this->renderAdmin('create_stage', array_merge($formData, [
                    'success' => 'Stage créé avec succès !',
                    'form_data' => []
                ]));
                return;
                
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        
        $this->renderAdmin('create_stage', array_merge($formData, [
            'error' => $error,
            'form_data' => $postData
        ]));
    }
}
?>