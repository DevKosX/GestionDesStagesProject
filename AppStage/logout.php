<?php
session_start();
$response = array();

if (session_destroy()) {
    $response['success'] = true;
    $response['message'] = 'Déconnexion réussie.';
} else {
    $response['success'] = false;
    $response['message'] = 'Erreur lors de la déconnexion.';
}

header('Content-Type: application/json');
echo json_encode($response);
?>