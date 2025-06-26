<?php
require_once __DIR__ . '/../../config/database.php';

class Notification {
    
    /**
     * Marquer une section comme vue par l'utilisateur
     */
    public static function marquerSectionVue($user_id, $section_name) {
        global $pdo;
        
        try {
            $stmt = $pdo->prepare("
                INSERT INTO vues_sections (user_id, section_name, last_viewed_at) 
                VALUES (?, ?, NOW()) 
                ON DUPLICATE KEY UPDATE last_viewed_at = NOW()
            ");
            return $stmt->execute([$user_id, $section_name]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Obtenir la dernière vue d'une section par l'utilisateur
     */
    public static function getDerniereVueSection($user_id, $section_name) {
        global $pdo;
        
        try {
            $stmt = $pdo->prepare("
                SELECT last_viewed_at 
                FROM vues_sections 
                WHERE user_id = ? AND section_name = ?
            ");
            $stmt->execute([$user_id, $section_name]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['last_viewed_at'] : null;
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Compter les nouveaux messages non lus
     */
    public static function compterNouveauxMessages($user_id) {
        global $pdo;
        
        try {
            $derniere_vue = self::getDerniereVueSection($user_id, 'messagerie');
            
            if ($derniere_vue) {
                // Compter les messages reçus après la dernière vue de la messagerie
                $stmt = $pdo->prepare("
                    SELECT COUNT(*) as total 
                    FROM message 
                    WHERE destinataire_id = ? 
                    AND contenu NOT LIKE 'Document partagé:%'
                    AND date_envoi > ?
                ");
                $stmt->execute([$user_id, $derniere_vue]);
            } else {
                // Si jamais vu la messagerie, compter tous les messages
                $stmt = $pdo->prepare("
                    SELECT COUNT(*) as total 
                    FROM message 
                    WHERE destinataire_id = ? 
                    AND contenu NOT LIKE 'Document partagé:%'
                ");
                $stmt->execute([$user_id]);
            }
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (int)$result['total'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Compter les nouveaux éléments dans le suivi des stages
     */
    public static function compterNouveauxStages($user_id, $role) {
        global $pdo;
        
        try {
            $derniere_vue = self::getDerniereVueSection($user_id, 'suivi-stages');
            
            if ($role === 'eleve') {
                // Pour les étudiants : compter les stages actifs
                if ($derniere_vue) {
                    // Si déjà vu, ne compter que si il y a eu des changements récents
                    // Pour l'instant, on compte 0 si déjà vu (disparition du badge)
                    return 0;
                } else {
                    // Si jamais vu, compter les stages actifs
                    $stmt = $pdo->prepare("
                        SELECT COUNT(*) as total 
                        FROM stage 
                        WHERE Id_Etudiant = ? 
                        AND date_debut <= CURDATE() 
                        AND date_fin >= CURDATE()
                    ");
                    $stmt->execute([$user_id]);
                }
            } elseif ($role === 'enseignant' || $role === 'tuteur') {
                // Pour les enseignants : compter les stages qu'ils supervisent
                if ($derniere_vue) {
                    return 0; // Badge disparaît après vue
                } else {
                    $stmt = $pdo->prepare("
                        SELECT COUNT(*) as total 
                        FROM stage 
                        WHERE Id_Enseignant = ? 
                        AND date_debut <= CURDATE() 
                        AND date_fin >= CURDATE()
                    ");
                    $stmt->execute([$user_id]);
                }
            } elseif ($role === 'tuteur_entreprise') {
                // Pour les tuteurs d'entreprise : compter leurs stages
                if ($derniere_vue) {
                    return 0;
                } else {
                    $stmt = $pdo->prepare("
                        SELECT COUNT(*) as total 
                        FROM stage s
                        JOIN tuteur_entreprise te ON s.Id_TuteurEntreprise = te.Id_TuteurEntreprise
                        WHERE te.Id_TuteurEntreprise = ? 
                        AND s.date_debut <= CURDATE() 
                        AND s.date_fin >= CURDATE()
                    ");
                    $stmt->execute([$user_id]);
                }
            } else {
                // Pour les autres rôles (admin, etc.)
                if ($derniere_vue) {
                    return 0;
                } else {
                    $stmt = $pdo->prepare("
                        SELECT COUNT(*) as total 
                        FROM stage 
                        WHERE date_debut <= CURDATE() 
                        AND date_fin >= CURDATE()
                    ");
                    $stmt->execute();
                }
            }
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (int)$result['total'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Compter les nouveaux documents non lus
     */
    public static function compterNouveauxDocuments($user_id) {
        global $pdo;
        
        try {
            $derniere_vue = self::getDerniereVueSection($user_id, 'documents');
            
            if ($derniere_vue) {
                // Compter les documents reçus après la dernière vue de la section documents
                $stmt = $pdo->prepare("
                    SELECT COUNT(*) as total 
                    FROM message 
                    WHERE destinataire_id = ? 
                    AND contenu LIKE 'Document partagé:%'
                    AND date_envoi > ?
                ");
                $stmt->execute([$user_id, $derniere_vue]);
            } else {
                // Si jamais vu la section documents, compter tous les documents
                $stmt = $pdo->prepare("
                    SELECT COUNT(*) as total 
                    FROM message 
                    WHERE destinataire_id = ? 
                    AND contenu LIKE 'Document partagé:%'
                ");
                $stmt->execute([$user_id]);
            }
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (int)$result['total'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Compter les événements urgents dans le calendrier
     */
    public static function compterEvenementsUrgents($user_id, $role) {
        global $pdo;
        
        try {
            $derniere_vue = self::getDerniereVueSection($user_id, 'calendrier');
            
            // Si l'utilisateur a déjà vu le calendrier, on peut soit :
            // 1. Ne compter que les nouveaux événements urgents (complexe)
            // 2. Faire disparaître le badge (simple)
            // Pour l'instant, on utilise l'approche simple
            if ($derniere_vue) {
                return 0; // Badge disparaît après avoir vu le calendrier
            }
            
            // Importer la classe Action pour utiliser sa logique
            require_once __DIR__ . '/Action.php';
            require_once __DIR__ . '/Evenement.php';
            
            $count_urgents = 0;
            $seuil_urgent = strtotime('+7 days'); // Événements dans les 7 prochains jours
            $aujourd_hui = strtotime('today');
            
            // Compter les actions urgentes
            $actions_urgentes = Action::getActionsProchaines($user_id, $role, 100);
            
            foreach ($actions_urgentes as $action) {
                $date_evenement = strtotime($action['date_evenement']);
                $est_urgent = $date_evenement <= $seuil_urgent && $date_evenement >= $aujourd_hui && !$action['est_realise'];
                $est_en_retard = $action['type_evenement'] === 'retard';
                
                if ($est_urgent || $est_en_retard) {
                    $count_urgents++;
                }
            }
            
            // Compter les soutenances à venir
            $soutenances = Evenement::getSoutenancesProchaines($user_id, $role);
            foreach ($soutenances as $soutenance) {
                $date_soutenance = strtotime($soutenance['date_evenement']);
                if ($date_soutenance <= $seuil_urgent && $date_soutenance >= $aujourd_hui) {
                    $count_urgents++;
                }
            }
            
            return $count_urgents;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Obtenir toutes les statistiques de notification pour un utilisateur
     */
    public static function getStatsNotifications($user_id, $role) {
        return [
            'messages_recus' => self::compterNouveauxMessages($user_id),
            'stages_actifs' => self::compterNouveauxStages($user_id, $role),
            'documents_recus' => self::compterNouveauxDocuments($user_id),
            'evenements_urgents' => self::compterEvenementsUrgents($user_id, $role)
        ];
    }
}
?> 