<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['rapport'])) {
    $uploadDir = '../../uploads/'; // Dossier où les fichiers seront enregistrés
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Crée le dossier si nécessaire
    }

    $fileName = basename($_FILES['rapport']['name']);
    $uploadFile = $uploadDir . $fileName;

    // Vérifiez si le fichier est bien téléchargé
    if (move_uploaded_file($_FILES['rapport']['tmp_name'], $uploadFile)) {
        echo "Le fichier a été téléchargé avec succès.";
    } else {
        echo "Erreur lors du téléchargement du fichier.";
    }
} else {
    echo "Aucune donnée reçue.";
}
?>
