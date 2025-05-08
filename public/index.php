<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

// Configuração básica
define('BASE_URL', '/');
define('API_URL', '/api');

// Roteamento básico
$request = $_SERVER['REQUEST_URI'];
$request = str_replace(BASE_URL, '', $request);

// Remove query strings
if (strpos($request, '?') !== false) {
    $request = substr($request, 0, strpos($request, '?'));
}

// Rotas do frontend
switch ($request) {
    case '':
    case '/':
        require __DIR__ . '/views/login.php';
        break;
    case '/dashboard':
        require __DIR__ . '/views/dashboard.php';
        break;
    case '/clients':
        require __DIR__ . '/views/clients.php';
        break;
    case '/requests':
        require __DIR__ . '/views/requests.php';
        break;
    case '/calendar':
        require __DIR__ . '/views/calendar.php';
        break;
    case '/settings':
        require __DIR__ . '/views/settings.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/views/404.php';
        break;
}
