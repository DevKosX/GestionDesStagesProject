<?php
require_once __DIR__ . '/../../config/database.php';


class Message {
    public static function envoyer($expediteur_id, $destinataire_id, $contenu) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO message (expediteur_id, destinataire_id, contenu) VALUES (?, ?, ?)");
        return $stmt->execute([$expediteur_id, $destinataire_id, $contenu]);
    }

    public static function messagesRecus($user_id) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT m.*, 
                   u.email as expediteur_email, 
                   u.nom as expediteur_nom, 
                   u.prenom as expediteur_prenom 
            FROM message m 
            JOIN utilisateur u ON m.expediteur_id = u.Id 
            WHERE m.destinataire_id = ? AND m.contenu NOT LIKE 'Document partagé:%'
            ORDER BY m.date_envoi DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function messagesEnvoyes($user_id) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT m.*, 
                   u.email as destinataire_email, 
                   u.nom as destinataire_nom, 
                   u.prenom as destinataire_prenom 
            FROM message m 
            JOIN utilisateur u ON m.destinataire_id = u.Id 
            WHERE m.expediteur_id = ? AND m.contenu NOT LIKE 'Document partagé:%'
            ORDER BY m.date_envoi DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function trouverDestinataireParEmail($email) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT Id FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn();
    }
} 