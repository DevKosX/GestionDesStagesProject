<?php
require_once __DIR__ . '/../../config/database.php';

class Enterprise {
    
    /**
     * Créer une nouvelle entreprise
     */
    public static function create($data) {
        global $pdo;
        $stmt = $pdo->prepare("
            INSERT INTO entreprise (adresse, ville) 
            VALUES (?, ?)
        ");
        $stmt->execute([
            $data['adresse'],
            $data['ville']
        ]);
        return $pdo->lastInsertId();
    }

    /**
     * Obtenir toutes les entreprises
     */
    public static function getAll() {
        global $pdo;
        try {
            $stmt = $pdo->query("SELECT * FROM entreprise ORDER BY ville, adresse");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Obtenir une entreprise par ID
     */
    public static function findById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM entreprise WHERE Id_Entreprise = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
} 