<?php
require_once __DIR__ . '/../../config/database.php';

class Evenement {
    public static function getEvenements($user_id = null, $role = null) {
        global $pdo;
        
        // Simplifier la requête pour éviter les erreurs
        $sql = "SELECT 
                    s.Id_Stage as id,
                    CONCAT('Soutenance - ', u.prenom, ' ', u.nom) as titre,
                    CONCAT('Soutenance de stage de ', u.prenom, ' ', u.nom, ' en salle ', COALESCE(s.salle_Soutenance, 'Non définie')) as description,
                    s.date_soutenance as date_evenement,
                    'important' as type_evenement,
                    s.Id_Etudiant as user_id,
                    'general' as role_cible,
                    s.date_debut as date_creation
                FROM stage s
                JOIN etudiant e ON s.Id_Etudiant = e.Id_Etudiant
                JOIN utilisateur u ON e.Id_Etudiant = u.Id
                WHERE s.date_soutenance IS NOT NULL";
        
        $params = [];
        
        if ($user_id && $role === 'eleve') {
            $sql .= " AND s.Id_Etudiant = ?";
            $params[] = $user_id;
        }
        
        $sql .= " ORDER BY s.date_soutenance ASC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getEvenementsProchains($user_id = null, $role = null, $limit = 5) {
        global $pdo;
        
        // Événements fixes
        $evenements_fixes = [
            [
                'id' => 'convention_deadline',
                'titre' => 'Date limite convention de stage',
                'description' => 'Date limite pour soumettre la convention de stage signée',
                'date_evenement' => '2024-03-15',
                'type_evenement' => 'urgent'
            ],
            [
                'id' => 'rapport_deadline',
                'titre' => 'Date limite remise rapport',
                'description' => 'Date limite pour remettre le rapport de stage',
                'date_evenement' => '2024-05-30',
                'type_evenement' => 'urgent'
            ]
        ];
        
        // Récupérer les événements des stages (sans LIMIT dans la requête)
        $sql = "SELECT 
                    CONCAT('soutenance_', s.Id_Stage) as id,
                    CONCAT('Soutenance - ', u.prenom, ' ', u.nom) as titre,
                    CONCAT('Soutenance de stage en salle ', COALESCE(s.salle_Soutenance, 'Non définie')) as description,
                    s.date_soutenance as date_evenement,
                    'important' as type_evenement
                FROM stage s
                JOIN etudiant e ON s.Id_Etudiant = e.Id_Etudiant
                JOIN utilisateur u ON e.Id_Etudiant = u.Id
                WHERE s.date_soutenance >= CURDATE()";
        
        $params = [];
        
        if ($user_id && $role === 'eleve') {
            $sql .= " AND s.Id_Etudiant = ?";
            $params[] = $user_id;
        }
        
        $sql .= " ORDER BY s.date_soutenance ASC";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $evenements_stages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // En cas d'erreur, retourner seulement les événements fixes
            $evenements_stages = [];
        }
        
        // Combiner avec les événements fixes
        $tous_evenements = array_merge($evenements_fixes, $evenements_stages);
        
        // Filtrer par date et trier
        $evenements_prochains = array_filter($tous_evenements, function($event) {
            return strtotime($event['date_evenement']) >= strtotime('today');
        });
        
        usort($evenements_prochains, function($a, $b) {
            return strtotime($a['date_evenement']) - strtotime($b['date_evenement']);
        });
        
        // Appliquer la limite après le tri
        return array_slice($evenements_prochains, 0, $limit);
    }
    
    public static function ajouterEvenement($titre, $description, $date_evenement, $type_evenement, $user_id = null, $role_cible = null) {
        return true;
    }
    
    public static function getEvenementsMois($annee, $mois, $user_id = null, $role = null) {
        global $pdo;
        
        $sql = "SELECT 
                    CONCAT('soutenance_', s.Id_Stage) as id,
                    CONCAT('Soutenance - ', u.prenom, ' ', u.nom) as titre,
                    CONCAT('Soutenance de stage en salle ', COALESCE(s.salle_Soutenance, 'Non définie')) as description,
                    s.date_soutenance as date_evenement,
                    'important' as type_evenement
                FROM stage s
                JOIN etudiant e ON s.Id_Etudiant = e.Id_Etudiant
                JOIN utilisateur u ON e.Id_Etudiant = u.Id
                WHERE YEAR(s.date_soutenance) = ? AND MONTH(s.date_soutenance) = ?";
        
        $params = [$annee, $mois];
        
        if ($user_id && $role === 'eleve') {
            $sql .= " AND s.Id_Etudiant = ?";
            $params[] = $user_id;
        }
        
        $sql .= " ORDER BY s.date_soutenance ASC";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    public static function supprimerEvenement($id, $user_id = null) {
        return false;
    }
    
    public static function initialiserEvenementsDefaut() {
        return true;
    }
}
?> 