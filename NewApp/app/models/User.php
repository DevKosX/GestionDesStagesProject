<?php
require_once __DIR__ . '/../../config/database.php';

class User {
    public $Id;
    public $nom;
    public $prenom;
    public $email;
    public $telephone;
    public $login;
    public $mot_de_passe;
    
    public static function findByEmail($email) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchObject('User');
    }

    public static function getRole($userId) {
        global $pdo;
        $roles = [
            'admin' => ['administrateur', 'Id_Administrateur'],
            'tuteur' => ['enseignant', 'Id_Enseignant'],
            'tuteur_entreprise' => ['tuteur_entreprise', 'Id_TuteurEntreprise'],
            'eleve' => ['etudiant', 'Id_Etudiant']
        ];
        foreach ($roles as $role => [$table, $col]) {
            $stmt = $pdo->prepare("SELECT 1 FROM $table WHERE $col = ?");
            $stmt->execute([$userId]);
            if ($stmt->fetchColumn()) {
                return $role;
            }
        }
        return null;
    }

    /**
     * Créer un nouveau utilisateur
     */
    public static function create($userData) {
        global $pdo;
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
     * Vérifier si un email existe déjà
     */
    public static function emailExists($email) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Obtenir les utilisateurs par rôle
     */
    public static function getUsersByRole($role) {
        global $pdo;
        
        $roleQueries = [
            'etudiants' => "
                SELECT u.Id as id, u.nom, u.prenom, u.email, u.telephone, 'Étudiant' as role_display
                FROM utilisateur u 
                JOIN etudiant e ON u.Id = e.Id_Etudiant 
                ORDER BY u.nom, u.prenom
            ",
            'enseignants' => "
                SELECT u.Id as id, u.nom, u.prenom, u.email, u.telephone, 
                       en.Bureau, 'Enseignant' as role_display
                FROM utilisateur u 
                JOIN enseignant en ON u.Id = en.Id_Enseignant 
                ORDER BY u.nom, u.prenom
            ",
            'tuteur_entreprise' => "
                SELECT u.Id as id, u.nom, u.prenom, u.email, u.telephone, 
                       e.ville as entreprise_ville, 'Tuteur Entreprise' as role_display
                FROM utilisateur u 
                JOIN tuteur_entreprise te ON u.Id = te.Id_TuteurEntreprise 
                LEFT JOIN entreprise e ON te.Id_Entreprise = e.Id_Entreprise
                ORDER BY u.nom, u.prenom
            ",
            'administrateurs' => "
                SELECT u.Id as id, u.nom, u.prenom, u.email, u.telephone, 'Administrateur' as role_display
                FROM utilisateur u 
                JOIN administrateur a ON u.Id = a.Id_Administrateur 
                ORDER BY u.nom, u.prenom
            "
        ];

        if (!isset($roleQueries[$role])) {
            return [];
        }

        try {
            $stmt = $pdo->query($roleQueries[$role]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Créer une entrée de rôle spécifique
     */
    public static function createRoleEntry($userId, $role, $postData) {
        global $pdo;
        
        try {
            switch ($role) {
                case 'etudiant':
                    $stmt = $pdo->prepare("INSERT INTO etudiant (Id_Etudiant) VALUES (?)");
                    $stmt->execute([$userId]);
                    break;
                    
                case 'enseignant':
                    $bureau = isset($postData['bureau']) ? trim($postData['bureau']) : '';
                    
                    // Vérifier si la colonne Id_Departement existe
                    $hasIdDepartement = self::columnExists('enseignant', 'Id_Departement');
                    
                    if ($hasIdDepartement) {
                        $departement = isset($postData['departement']) ? (int)$postData['departement'] : null;
                        $stmt = $pdo->prepare("INSERT INTO enseignant (Id_Enseignant, Bureau, Id_Departement) VALUES (?, ?, ?)");
                        $stmt->execute([$userId, $bureau, $departement]);
                    } else {
                        $stmt = $pdo->prepare("INSERT INTO enseignant (Id_Enseignant, Bureau) VALUES (?, ?)");
                        $stmt->execute([$userId, $bureau]);
                    }
                    break;
                    
                case 'tuteur_entreprise':
                    // Validation des champs obligatoires
                    if (empty($postData['ville_entreprise']) || empty($postData['adresse_entreprise'])) {
                        throw new Exception("La ville et l'adresse de l'entreprise sont obligatoires pour un tuteur entreprise");
                    }
                    
                    // Créer une nouvelle entreprise
                    $stmt = $pdo->prepare("INSERT INTO entreprise (adresse, ville) VALUES (?, ?)");
                    $stmt->execute([
                        trim($postData['adresse_entreprise']),
                        trim($postData['ville_entreprise'])
                    ]);
                    $entrepriseId = $pdo->lastInsertId();
                    
                    $stmt = $pdo->prepare("INSERT INTO tuteur_entreprise (Id_TuteurEntreprise, Id_Entreprise) VALUES (?, ?)");
                    $stmt->execute([$userId, $entrepriseId]);
                    break;
                    
                case 'admin':
                    $stmt = $pdo->prepare("INSERT INTO administrateur (Id_Administrateur) VALUES (?)");
                    $stmt->execute([$userId]);
                    break;
                    
                default:
                    throw new Exception("Rôle non reconnu : " . $role);
            }
        } catch (Exception $e) {
            // Log de l'erreur pour debug
            error_log("Erreur createRoleEntry: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Vérifier si une colonne existe dans une table
     */
    private static function columnExists($table, $column) {
        global $pdo;
        try {
            $stmt = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
            $stmt->execute([$column]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Obtenir les rôles disponibles
     */
    public static function getAvailableRoles() {
        return [
            'etudiant' => 'Étudiant',
            'enseignant' => 'Enseignant',
            'tuteur_entreprise' => 'Tuteur Entreprise',
            'admin' => 'Administrateur'
        ];
    }

    /**
     * Obtenir les statistiques des utilisateurs
     */
    public static function getStats() {
        global $pdo;
        
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM utilisateur");
            $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            $roles = [
                'etudiants' => "SELECT COUNT(*) FROM etudiant",
                'enseignants' => "SELECT COUNT(*) FROM enseignant", 
                'tuteur_entreprise' => "SELECT COUNT(*) FROM tuteur_entreprise",
                'administrateurs' => "SELECT COUNT(*) FROM administrateur"
            ];
            
            $roleStats = [];
            foreach ($roles as $role => $query) {
                $stmt = $pdo->query($query);
                $roleStats[$role] = $stmt->fetchColumn();
            }
            
            return [
                'total_users' => $totalUsers,
                'roles' => $roleStats
            ];
        } catch (Exception $e) {
            return [
                'total_users' => 0,
                'roles' => ['etudiants' => 0, 'enseignants' => 0, 'tuteur_entreprise' => 0, 'administrateurs' => 0]
            ];
        }
    }
} 