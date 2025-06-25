<?php
require_once __DIR__ . '/../../config/database.php';

class Model {
    protected $pdo;
    protected $table;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    /**
     * Exécute une requête préparée en toute sécurité
     */
    protected function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Récupère tous les enregistrements
     */
    protected function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }
    
    /**
     * Récupère un seul enregistrement
     */
    protected function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
    }
    
    /**
     * Compte le nombre d'enregistrements
     */
    protected function count($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetchColumn() : 0;
    }
    
    /**
     * Récupère le dernier ID inséré
     */
    protected function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
    
    /**
     * Démarre une transaction
     */
    protected function beginTransaction() {
        return $this->pdo->beginTransaction();
    }
    
    /**
     * Valide une transaction
     */
    protected function commit() {
        return $this->pdo->commit();
    }
    
    /**
     * Annule une transaction
     */
    protected function rollback() {
        return $this->pdo->rollback();
    }
} 