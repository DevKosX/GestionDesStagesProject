<?php
// Helpers d'URL - Wrappers propres autour du Router
// Supprime la redondance en utilisant uniquement les méthodes du Router

if (!function_exists('url')) {
    function url($path = '') {
        global $router;
        if ($router instanceof Router) {
            return $router->url($path);
        }
        // Fallback minimal si le router n'est pas disponible
        throw new Exception('Router non initialisé pour url()');
    }
}

if (!function_exists('asset')) {
    function asset($path = '') {
        global $router;
        if ($router instanceof Router) {
            return $router->asset($path);
        }
        // Fallback minimal si le router n'est pas disponible
        throw new Exception('Router non initialisé pour asset()');
    }
}

if (!function_exists('redirect')) {
    function redirect($path = '') {
        global $router;
        if ($router instanceof Router) {
            header('Location: ' . $router->url($path));
            exit;
        }
        // Fallback minimal si le router n'est pas disponible
        throw new Exception('Router non initialisé pour redirect()');
    }
}

if (!function_exists('getBasePath')) {
    function getBasePath() {
        global $router;
        if ($router instanceof Router) {
            return $router->getBasePath();
        }
        // Fallback minimal si le router n'est pas disponible
        throw new Exception('Router non initialisé pour getBasePath()');
    }
}
?> 