<?php
if (session_status() === PHP_SESSION_ACTIVE) {
    session_unset();
    session_destroy();
}

    // Renvoyez une réponse JSON
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Vous avez été déconnecté avec succès.']);
    exit();
} else {
    // Si la requête n'est pas une requête POST, renvoyer une erreur
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit();
}
?>
