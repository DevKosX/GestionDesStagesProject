<?php
require_once __DIR__ . '/../../config/database.php';

class Stage {
    
    /**
     * Créer un nouveau stage
     */
    public static function create($data) {
        global $pdo;
        
        $stmt = $pdo->prepare("
            INSERT INTO stage (Id_Annee, Id_Etudiant, Id_Enseignant, Id_TuteurEntreprise, 
                             date_debut, date_fin, mission, date_soutenance, salle_Soutenance) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $data['Id_Annee'],
            $data['Id_Etudiant'],
            $data['Id_Enseignant'],
            $data['Id_TuteurEntreprise'],
            $data['date_debut'],
            $data['date_fin'],
            $data['mission'],
            $data['date_soutenance'],
            $data['salle_Soutenance']
        ]);
        
        return $pdo->lastInsertId();
    }

    /**
     * Obtenir tous les stages
     */
    public static function getAll() {
        global $pdo;
        try {
            $stmt = $pdo->query("
                SELECT s.*, 
                       ue.prenom as etudiant_prenom, ue.nom as etudiant_nom,
                       uen.prenom as enseignant_prenom, uen.nom as enseignant_nom,
                       ute.prenom as tuteur_prenom, ute.nom as tuteur_nom,
                       a.libelle as annee_libelle
                FROM stage s
                LEFT JOIN utilisateur ue ON s.Id_Etudiant = ue.Id
                LEFT JOIN utilisateur uen ON s.Id_Enseignant = uen.Id  
                LEFT JOIN utilisateur ute ON s.Id_TuteurEntreprise = ute.Id
                LEFT JOIN annee a ON s.Id_Annee = a.Id_Annee
                ORDER BY s.date_debut DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Obtenir les données nécessaires pour le formulaire de création
     */
    public static function getFormData() {
        global $pdo;
        
        $data = [
            'etudiants' => [],
            'enseignants' => [],
            'tuteurs_entreprise' => [],
            'annees' => []
        ];
        
        try {
            // Étudiants
            $stmt = $pdo->prepare("
                SELECT e.Id_Etudiant, u.nom, u.prenom, u.email 
                FROM etudiant e 
                JOIN utilisateur u ON e.Id_Etudiant = u.Id 
                ORDER BY u.nom, u.prenom
            ");
            $stmt->execute();
            $data['etudiants'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $data['etudiants'] = [];
        }
        
        try {
            // Enseignants
            $stmt = $pdo->prepare("
                SELECT en.Id_Enseignant, u.nom, u.prenom, u.email 
                FROM enseignant en 
                JOIN utilisateur u ON en.Id_Enseignant = u.Id 
                ORDER BY u.nom, u.prenom
            ");
            $stmt->execute();
            $data['enseignants'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $data['enseignants'] = [];
        }
        
        try {
            // Tuteurs entreprise
            $stmt = $pdo->prepare("
                SELECT te.Id_TuteurEntreprise, u.nom, u.prenom, u.email, e.ville 
                FROM tuteur_entreprise te 
                JOIN utilisateur u ON te.Id_TuteurEntreprise = u.Id 
                LEFT JOIN entreprise e ON te.Id_Entreprise = e.Id_Entreprise
                ORDER BY u.nom, u.prenom
            ");
            $stmt->execute();
            $data['tuteurs_entreprise'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $data['tuteurs_entreprise'] = [];
        }
        
        try {
            // Années
            $stmt = $pdo->prepare("SELECT * FROM annee ORDER BY libelle");
            $stmt->execute();
            $data['annees'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $data['annees'] = [];
        }
        
        return $data;
    }

    /**
     * Valider les données d'un stage
     */
    public static function validate($data) {
        $errors = [];
        
        // Champs obligatoires
        if (empty($data['Id_Annee'])) $errors[] = "L'année universitaire est obligatoire";
        if (empty($data['Id_Etudiant'])) $errors[] = "L'étudiant est obligatoire";
        if (empty($data['Id_Enseignant'])) $errors[] = "Le tuteur pédagogique est obligatoire";
        if (empty($data['date_debut'])) $errors[] = "La date de début est obligatoire";
        if (empty($data['date_fin'])) $errors[] = "La date de fin est obligatoire";
        if (empty($data['mission'])) $errors[] = "La mission est obligatoire";
        
        // Validation des dates
        if (!empty($data['date_debut']) && !empty($data['date_fin'])) {
            if (strtotime($data['date_fin']) <= strtotime($data['date_debut'])) {
                $errors[] = "La date de fin doit être après la date de début";
            }
        }
        
        if (!empty($data['date_soutenance']) && !empty($data['date_fin'])) {
            if (strtotime($data['date_soutenance']) < strtotime($data['date_fin'])) {
                $errors[] = "La date de soutenance doit être après la fin du stage";
            }
        }
        
        return $errors;
    }
} 