<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/Action.php';

class Evenement {
    /**
     * Récupère les événements de soutenance
     */
    public static function getSoutenances($user_id = null, $role = null) {
        global $pdo;
        
        $sql = "SELECT 
                    CONCAT('soutenance_', s.Id_Stage) as id,
                    CONCAT('Soutenance - ', u.prenom, ' ', u.nom) as titre,
                    CONCAT('Soutenance de stage en salle ', COALESCE(s.salle_Soutenance, 'Non définie')) as description,
                    s.date_soutenance as date_evenement,
                    'soutenance' as type_evenement,
                    s.Id_Etudiant as etudiant_id
                FROM stage s
                JOIN etudiant e ON s.Id_Etudiant = e.Id_Etudiant
                JOIN utilisateur u ON e.Id_Etudiant = u.Id
                WHERE s.date_soutenance IS NOT NULL";
        
        $params = [];
        
        // Filtrer selon le rôle
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
    
    /**
     * Récupère les événements prochains (soutenances + actions)
     */
    public static function getEvenementsProchains($user_id = null, $role = null, $limit = 10) {
        // Récupérer les soutenances à venir
        $soutenances = self::getSoutenancesProchaines($user_id, $role);
        
        // Récupérer les actions prochaines
        $actions = Action::getActionsProchaines($user_id, $role, 20);
        
        // Combiner tous les événements
        $tous_evenements = array_merge($soutenances, $actions);
        
        // Filtrer les événements futurs et trier par date
        $evenements_prochains = array_filter($tous_evenements, function($event) {
            return strtotime($event['date_evenement']) >= strtotime('today');
        });
        
        usort($evenements_prochains, function($a, $b) {
            return strtotime($a['date_evenement']) - strtotime($b['date_evenement']);
        });
        
        return array_slice($evenements_prochains, 0, $limit);
    }
    
    /**
     * Récupère uniquement les soutenances à venir
     */
    public static function getSoutenancesProchaines($user_id = null, $role = null) {
        global $pdo;
        
        $sql = "SELECT 
                    CONCAT('soutenance_', s.Id_Stage) as id,
                    CONCAT('Soutenance - ', u.prenom, ' ', u.nom) as titre,
                    CONCAT('Soutenance de stage en salle ', COALESCE(s.salle_Soutenance, 'Non définie')) as description,
                    s.date_soutenance as date_evenement,
                    'soutenance' as type_evenement,
                    s.Id_Etudiant as etudiant_id
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
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Récupère les événements d'un mois spécifique
     */
    public static function getEvenementsMois($annee, $mois, $user_id = null, $role = null) {
        // Récupérer les soutenances du mois
        $soutenances = self::getSoutenancesMois($annee, $mois, $user_id, $role);
        
        // Récupérer les actions du mois
        $actions = Action::getActionsMois($annee, $mois, $user_id, $role);
        
        // Combiner et trier
        $tous_evenements = array_merge($soutenances, $actions);
        
        usort($tous_evenements, function($a, $b) {
            return strtotime($a['date_evenement']) - strtotime($b['date_evenement']);
        });
        
        return $tous_evenements;
    }
    
    /**
     * Récupère les soutenances d'un mois spécifique
     */
    private static function getSoutenancesMois($annee, $mois, $user_id = null, $role = null) {
        global $pdo;
        
        $sql = "SELECT 
                    CONCAT('soutenance_', s.Id_Stage) as id,
                    CONCAT('Soutenance - ', u.prenom, ' ', u.nom) as titre,
                    CONCAT('Soutenance de stage en salle ', COALESCE(s.salle_Soutenance, 'Non définie')) as description,
                    s.date_soutenance as date_evenement,
                    'soutenance' as type_evenement,
                    s.Id_Etudiant as etudiant_id
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
    
    public static function ajouterEvenement($titre, $description, $date_evenement, $type_evenement, $user_id = null, $role_cible = null) {
        return true;
    }
    
    public static function supprimerEvenement($id, $user_id = null) {
        return false;
    }
    
    public static function initialiserEvenementsDefaut() {
        return true;
    }
}
?> 