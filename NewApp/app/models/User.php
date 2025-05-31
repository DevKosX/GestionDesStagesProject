<?php
require_once __DIR__ . '/../../config/database.php';
class User {
    public static function findByEmail($email) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchObject('User');
    }

    public static function getRole($userId) {
        global $pdo;
        $roles = [
            'admin' => ['administrateur', 'Id_Administrateur'],
            'tuteur' => ['enseignant', 'Id_Enseignant'],
            'tuteur_entreprise' => ['tuteur_entreprise', 'Id_TuteurEntreprise'],
            'eleve' => ['etudiant', 'Id_Etudiant']
        ];
        foreach ($roles as $role => [$table, $col]) {
            $stmt = $pdo->prepare("SELECT 1 FROM $table WHERE $col = ?");
            $stmt->execute([$userId]);
            if ($stmt->fetchColumn()) {
                return $role;
            }
        }
        return null;
    }
} 