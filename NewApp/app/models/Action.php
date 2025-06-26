<?php
require_once __DIR__ . '/../../config/database.php';

class Action {
    
    /**
     * Récupère les actions d'un utilisateur avec leurs échéances calculées
     */
    public static function getActionsAvecEcheances($user_id = null, $role = null) {
        global $pdo;
        
        $sql = "SELECT 
                    a.Id_Action,
                    a.Id_Etudiant,
                    a.Id_Stage,
                    a.date_realisation,
                    a.est_notifie,
                    a.lienDocument,
                    
                    ta.Id_TypeAction,
                    ta.libelle as titre_action,
                    ta.Executant,

                    ta.delaiEnJours,
                    ta.ReferenceDelai,
                    ta.requisDoc,
                    
                    s.date_debut,
                    s.date_fin,
                    s.date_soutenance,
                    s.mission,
                    
                    u.prenom,
                    u.nom,
                    
                    CASE 
                        WHEN ta.ReferenceDelai = 'date_debut' AND s.date_debut IS NOT NULL THEN 
                            DATE_ADD(s.date_debut, INTERVAL ta.delaiEnJours DAY)
                        WHEN ta.ReferenceDelai = 'date_fin' AND s.date_fin IS NOT NULL THEN 
                            DATE_ADD(s.date_fin, INTERVAL ta.delaiEnJours DAY)
                        WHEN ta.ReferenceDelai = 'date_soutenance' AND s.date_soutenance IS NOT NULL THEN 
                            DATE_SUB(s.date_soutenance, INTERVAL ta.delaiEnJours DAY)
                        -- Si pas de stage ou pas de date de référence, utiliser une échéance par défaut
                        WHEN s.Id_Stage IS NULL OR (ta.ReferenceDelai = 'date_debut' AND s.date_debut IS NULL) OR 
                             (ta.ReferenceDelai = 'date_fin' AND s.date_fin IS NULL) OR 
                             (ta.ReferenceDelai = 'date_soutenance' AND s.date_soutenance IS NULL) THEN
                            DATE_ADD(CURDATE(), INTERVAL ta.delaiEnJours DAY)
                        ELSE NULL
                    END as date_echeance
                    
                FROM action a
                JOIN typeaction ta ON a.Id_TypeAction = ta.Id_TypeAction
                LEFT JOIN stage s ON a.Id_Stage = s.Id_Stage OR (a.Id_Stage IS NULL AND s.Id_Etudiant = a.Id_Etudiant)
                JOIN etudiant e ON a.Id_Etudiant = e.Id_Etudiant
                JOIN utilisateur u ON e.Id_Etudiant = u.Id
                WHERE 1=1";
        
        $params = [];
        
        // Filtrer selon le rôle
        if ($user_id && $role === 'eleve') {
            $sql .= " AND a.Id_Etudiant = ?";
            $params[] = $user_id;
        } elseif ($user_id && $role === 'enseignant') {
            $sql .= " AND s.Id_Enseignant = ?";
            $params[] = $user_id;
        }
        
        $sql .= " ORDER BY date_echeance ASC";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Récupère les actions à venir pour le calendrier
     */
    public static function getActionsProchaines($user_id = null, $role = null, $limit = 10) {
        $actions = self::getActionsAvecEcheances($user_id, $role);
        
        $actions_prochaines = [];
        
        foreach ($actions as $action) {
            if ($action['date_echeance']) {
                
                // Filtrer selon le rôle de l'utilisateur
                $afficher_action = false;
                
                if ($role === 'eleve') {
                    // Les étudiants ne voient que les actions qu'ils doivent eux-mêmes effectuer
                    // Normaliser l'exécutant en supprimant les espaces et accents
                    $executant_normalise = trim(str_replace(['É', 'È', 'Ê'], 'E', $action['Executant']));
                    $afficher_action = (strcasecmp($executant_normalise, 'Etudiant') === 0);
                } elseif ($role === 'enseignant') {
                    // Les enseignants voient les actions qu'ils doivent effectuer et celles de leurs étudiants
                    $executant_normalise = trim(str_replace(['É', 'È', 'Ê'], 'E', $action['Executant']));
                    $afficher_action = (strcasecmp($executant_normalise, 'Tuteur pedagogique') === 0 || 
                                      strcasecmp($executant_normalise, 'Etudiant') === 0);
                } else {
                    // Pour les autres rôles (admin, secrétaire), afficher toutes les actions
                    $afficher_action = true;
                }
                
                if (!$afficher_action) {
                    continue; // Passer à l'action suivante
                }
                
                // Déterminer le statut et la priorité
                $jours_restants = (strtotime($action['date_echeance']) - strtotime('today')) / (60 * 60 * 24);
                $est_realise = !empty($action['date_realisation']);
                $est_urgent = $jours_restants <= 3 && !$est_realise;
                $est_en_retard = $jours_restants < 0 && !$est_realise;
                
                // Déterminer le type d'événement
                $type_evenement = 'action';
                if ($est_en_retard) {
                    $type_evenement = 'retard';
                } elseif ($est_urgent) {
                    $type_evenement = 'urgent';
                } elseif ($est_realise) {
                    $type_evenement = 'termine';
                }
                
                // Créer une description adaptée au rôle
                $description = "";
                if ($role !== 'eleve') {
                    $description = "À faire par " . $action['Executant'] . " - " . $action['prenom'] . " " . $action['nom'];
                }
                
                // Créer l'événement pour le calendrier
                $actions_prochaines[] = [
                    'id' => 'action_' . $action['Id_Action'],
                    'titre' => $action['titre_action'],
                    'description' => $description,
                    'date_evenement' => $action['date_echeance'],
                    'type_evenement' => $type_evenement,
                    'executant' => $action['Executant'],

                    'est_realise' => $est_realise,
                    'jours_restants' => round($jours_restants),
                    'requisDoc' => $action['requisDoc'],
                    'lienDocument' => $action['lienDocument'],
                    'etudiant' => $action['prenom'] . " " . $action['nom']
                ];
            }
        }
        
        // Trier par date d'échéance
        usort($actions_prochaines, function($a, $b) {
            return strtotime($a['date_evenement']) - strtotime($b['date_evenement']);
        });
        
        return array_slice($actions_prochaines, 0, $limit);
    }
    
    /**
     * Récupère les actions pour un mois donné
     */
    public static function getActionsMois($annee, $mois, $user_id = null, $role = null) {
        $actions = self::getActionsAvecEcheances($user_id, $role);
        
        $actions_mois = [];
        
        foreach ($actions as $action) {
            if ($action['date_echeance']) {
                $date_action = date('Y-m-d', strtotime($action['date_echeance']));
                $annee_action = date('Y', strtotime($date_action));
                $mois_action = date('m', strtotime($date_action));
                
                if ($annee_action == $annee && $mois_action == $mois) {
                    $jours_restants = (strtotime($action['date_echeance']) - strtotime('today')) / (60 * 60 * 24);
                    $est_realise = !empty($action['date_realisation']);
                    $est_urgent = $jours_restants <= 3 && !$est_realise;
                    $est_en_retard = $jours_restants < 0 && !$est_realise;
                    
                    $type_evenement = 'action';
                    if ($est_en_retard) {
                        $type_evenement = 'retard';
                    } elseif ($est_urgent) {
                        $type_evenement = 'urgent';
                    } elseif ($est_realise) {
                        $type_evenement = 'termine';
                    }
                    
                    $actions_mois[] = [
                        'id' => 'action_' . $action['Id_Action'],
                        'titre' => $action['titre_action'],
                        'description' => "À faire par " . $action['Executant'] . " - " . $action['prenom'] . " " . $action['nom'],
                        'date_evenement' => $action['date_echeance'],
                        'type_evenement' => $type_evenement,
                        'executant' => $action['Executant'],
                        'est_realise' => $est_realise,
                        'jours_restants' => round($jours_restants)
                    ];
                }
            }
        }
        
        return $actions_mois;
    }
    
    /**
     * Obtient le statut d'une action
     */
    public static function getStatutAction($action) {
        if (!empty($action['date_realisation'])) {
            return 'Terminé';
        }
        
        if (!$action['date_echeance']) {
            return 'En attente';
        }
        
        $jours_restants = (strtotime($action['date_echeance']) - strtotime('today')) / (60 * 60 * 24);
        
        if ($jours_restants < 0) {
            return 'En retard';
        } elseif ($jours_restants <= 3) {
            return 'Urgent';
        } else {
            return 'À faire';
        }
    }
}
?> 