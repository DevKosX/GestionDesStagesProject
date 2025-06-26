<?php
require_once __DIR__ . '/../../config/database.php';

class Department {
    
    /**
     * Obtenir tous les départements
     */
    public static function getAll() {
        global $pdo;
        try {
            $stmt = $pdo->query("SELECT * FROM departement ORDER BY Libelle");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Obtenir un département par ID
     */
    public static function findById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM departement WHERE Id_Departement = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
} 