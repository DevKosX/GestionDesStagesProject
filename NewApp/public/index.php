<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/models/User.php';  // Charger la classe User AVANT session_start()
session_start();  // Démarrer la session APRÈS avoir chargé les classes
require_once __DIR__ . '/../routes/web.php'; 