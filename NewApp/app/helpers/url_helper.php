<?php
// Helper pour générer les URLs de façon dynamique

if (!function_exists('getBaseUrl')) {
    function getBaseUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        return $protocol . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/';
    }
}

if (!function_exists('url')) {
    function url($path = '') {
        return getBaseUrl() . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    function asset($path = '') {
        return getBaseUrl() . 'assets/' . ltrim($path, '/');
    }
}
?> 