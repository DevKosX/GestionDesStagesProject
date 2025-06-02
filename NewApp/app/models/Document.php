<?php
require_once __DIR__ . '/../../config/database.php';

class Document {
    public static function upload($expediteur_id, $destinataire_id, $titre, $file) {
        global $pdo;
        
        // Créer le dossier de stockage s'il n'existe pas
        $upload_dir = __DIR__ . '/../../storage/documents/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Générer un nom de fichier unique
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $file_extension;
        $filepath = $upload_dir . $filename;
        
        // Vérifier la taille du fichier (max 10MB)
        if ($file['size'] > 10 * 1024 * 1024) {
            return ['success' => false, 'error' => 'Le fichier est trop volumineux (max 10MB)'];
        }
        
        // Vérifier les types de fichiers autorisés
        $allowed_types = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'zip', 'rar'];
        if (!in_array(strtolower($file_extension), $allowed_types)) {
            return ['success' => false, 'error' => 'Type de fichier non autorisé'];
        }
        
        // Déplacer le fichier uploadé
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            // Utiliser la table message pour stocker les infos du document
            $contenu_message = "Document partagé: " . $titre . " | Fichier: " . $filename;
            
            $stmt = $pdo->prepare("
                INSERT INTO message (expediteur_id, destinataire_id, contenu, date_envoi) 
                VALUES (?, ?, ?, NOW())
            ");
            
            $result = $stmt->execute([
                $expediteur_id,
                $destinataire_id,
                $contenu_message
            ]);
            
            if ($result) {
                return ['success' => true, 'message' => 'Document uploadé avec succès'];
            } else {
                unlink($filepath); // Supprimer le fichier si l'insertion échoue
                return ['success' => false, 'error' => 'Erreur lors de l\'enregistrement en base de données'];
            }
        } else {
            return ['success' => false, 'error' => 'Erreur lors de l\'upload du fichier'];
        }
    }
    
    public static function getDocumentsEnvoyes($user_id) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT m.*, u.nom as destinataire_nom, u.prenom as destinataire_prenom 
            FROM message m 
            JOIN utilisateur u ON m.destinataire_id = u.Id 
            WHERE m.expediteur_id = ? AND m.contenu LIKE 'Document partagé:%' 
            ORDER BY m.date_envoi DESC
        ");
        $stmt->execute([$user_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Traiter les résultats pour extraire les infos du document
        foreach ($results as &$result) {
            $parts = explode('|', $result['contenu']);
            $result['titre'] = str_replace('Document partagé: ', '', $parts[0]);
            $result['nom_fichier'] = isset($parts[1]) ? str_replace(' Fichier: ', '', $parts[1]) : '';
            $result['date_upload'] = $result['date_envoi'];
            
            // Calculer la taille du fichier
            $filepath = __DIR__ . '/../../storage/documents/' . $result['nom_fichier'];
            $result['taille'] = file_exists($filepath) ? filesize($filepath) : 0;
        }
        
        return $results;
    }
    
    public static function getDocumentsRecus($user_id) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT m.*, u.nom as expediteur_nom, u.prenom as expediteur_prenom 
            FROM message m 
            JOIN utilisateur u ON m.expediteur_id = u.Id 
            WHERE m.destinataire_id = ? AND m.contenu LIKE 'Document partagé:%' 
            ORDER BY m.date_envoi DESC
        ");
        $stmt->execute([$user_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Traiter les résultats pour extraire les infos du document
        foreach ($results as &$result) {
            $parts = explode('|', $result['contenu']);
            $result['titre'] = str_replace('Document partagé: ', '', $parts[0]);
            $result['nom_fichier'] = isset($parts[1]) ? str_replace(' Fichier: ', '', $parts[1]) : '';
            $result['date_upload'] = $result['date_envoi'];
            
            // Calculer la taille du fichier
            $filepath = __DIR__ . '/../../storage/documents/' . $result['nom_fichier'];
            $result['taille'] = file_exists($filepath) ? filesize($filepath) : 0;
        }
        
        return $results;
    }
    
    public static function getDocument($document_id, $user_id) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT * FROM message 
            WHERE id = ? AND (expediteur_id = ? OR destinataire_id = ?) AND contenu LIKE 'Document partagé:%'
        ");
        $stmt->execute([$document_id, $user_id, $user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $parts = explode('|', $result['contenu']);
            $result['titre'] = str_replace('Document partagé: ', '', $parts[0]);
            $result['nom_fichier'] = isset($parts[1]) ? str_replace(' Fichier: ', '', $parts[1]) : '';
            $result['nom_original'] = $result['nom_fichier']; // Simplification
        }
        
        return $result;
    }
    
    public static function downloadDocument($document_id, $user_id) {
        $document = self::getDocument($document_id, $user_id);
        
        if (!$document) {
            return ['success' => false, 'error' => 'Document non trouvé ou accès non autorisé'];
        }
        
        $filepath = __DIR__ . '/../../storage/documents/' . $document['nom_fichier'];
        
        if (!file_exists($filepath)) {
            return ['success' => false, 'error' => 'Fichier non trouvé sur le serveur'];
        }
        
        // Déterminer le type MIME
        $file_extension = pathinfo($document['nom_fichier'], PATHINFO_EXTENSION);
        $mime_types = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'zip' => 'application/zip'
        ];
        $mime_type = $mime_types[strtolower($file_extension)] ?? 'application/octet-stream';
        
        return [
            'success' => true,
            'filepath' => $filepath,
            'filename' => $document['nom_original'],
            'type' => $mime_type
        ];
    }
    
    public static function getAllUsers() {
        global $pdo;
        $stmt = $pdo->prepare("SELECT Id, nom, prenom, email FROM utilisateur ORDER BY nom, prenom");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
?> 