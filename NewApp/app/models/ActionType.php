<?php
require_once __DIR__ . '/../../config/database.php';

class ActionType {
    
    /**
     * Obtenir tous les types d'actions
     */
    public static function getAll() {
        global $pdo;
        try {
            $stmt = $pdo->query("SELECT * FROM typeaction ORDER BY libelle");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Créer un nouveau type d'action
     */
    public static function create($data) {
        global $pdo;
        
        $stmt = $pdo->prepare("
            INSERT INTO typeaction (libelle, Executant, delaiEnJours, ReferenceDelai, requisDoc) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        // Calculer le délai en jours à partir de la date de fin
        $dateDebut = new DateTime();
        $dateFin = new DateTime($data['date_fin']);
        $delaiEnJours = max(1, $dateDebut->diff($dateFin)->days);
        
        $stmt->execute([
            $data['libelle'],
            $data['Executant'],
            $delaiEnJours,
            'date_fin',
            $data['requisDoc']
        ]);
        
        return $pdo->lastInsertId();
    }

    /**
     * Mettre à jour un type d'action
     */
    public static function update($id, $data) {
        global $pdo;
        
        // Calculer le délai en jours
        $dateDebut = new DateTime();
        $dateFin = new DateTime($data['date_fin']);
        $delaiEnJours = max(1, $dateDebut->diff($dateFin)->days);
        
        $stmt = $pdo->prepare("
            UPDATE typeaction 
            SET libelle = ?, Executant = ?, delaiEnJours = ?, requisDoc = ?
            WHERE Id_TypeAction = ?
        ");
        
        return $stmt->execute([
            $data['libelle'],
            $data['Executant'],
            $delaiEnJours,
            $data['requisDoc'],
            $id
        ]);
    }

    /**
     * Supprimer un type d'action
     */
    public static function delete($id) {
        global $pdo;
        
        try {
            $pdo->beginTransaction();
            
            // Supprimer les actions associées
            $stmtActions = $pdo->prepare("DELETE FROM action WHERE Id_TypeAction = ?");
            $stmtActions->execute([$id]);
            
            // Supprimer le type d'action
            $stmtType = $pdo->prepare("DELETE FROM typeaction WHERE Id_TypeAction = ?");
            $success = $stmtType->execute([$id]);
            
            if ($success) {
                $pdo->commit();
                return true;
            } else {
                $pdo->rollback();
                return false;
            }
            
        } catch (Exception $e) {
            $pdo->rollback();
            throw $e;
        }
    }

    /**
     * Obtenir les exécutants disponibles
     */
    public static function getExecutantsAvailable() {
        return [
            'Etudiant' => 'Étudiant',
            'Tuteur pédagogique' => 'Tuteur pédagogique',
            'Tuteur entreprise' => 'Tuteur entreprise',
            'Secrétaire' => 'Secrétaire'
        ];
    }

    /**
     * Assigner automatiquement les actions aux utilisateurs du rôle approprié
     */
    public static function assignToRole($typeActionId, $executant) {
        global $pdo;
        
        // Conversion de l'exécutant vers un rôle
        $roleMapping = [
            'Etudiant' => 'etudiants',
            'Tuteur pédagogique' => 'enseignants',
            'Tuteur entreprise' => 'tuteur_entreprise',
            'Secrétaire' => 'administrateurs'
        ];
        
        $role = $roleMapping[$executant] ?? 'etudiants';
        
        // Obtenir les utilisateurs du rôle (limité aux étudiants pour l'instant car la table action utilise Id_Etudiant)
        if ($role === 'etudiants') {
            try {
                $stmt = $pdo->prepare("
                    SELECT e.Id_Etudiant as id FROM etudiant e 
                    JOIN utilisateur u ON e.Id_Etudiant = u.Id 
                    ORDER BY u.nom, u.prenom
                ");
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                $stmtInsert = $pdo->prepare("INSERT INTO action (Id_TypeAction, Id_Etudiant) VALUES (?, ?)");
                $nbAssignations = 0;
                
                foreach ($users as $user) {
                    try {
                        $stmtInsert->execute([$typeActionId, $user['id']]);
                        $nbAssignations++;
                    } catch (Exception $e) {
                        // Ignorer les erreurs d'insertion (doublons, etc.)
                    }
                }
                
                return $nbAssignations;
            } catch (Exception $e) {
                return 0;
            }
        }
        
        return 0;
    }
} 