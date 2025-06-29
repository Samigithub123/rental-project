<?php 
 
$basePath = 'Rental'; 
 
$requestUri = $_SERVER['REQUEST_URI']; 
 
$path = str_replace('/' . $basePath, '', $requestUri); 
$path = parse_url($path, PHP_URL_PATH); 
$path = trim($path, '/'); 
 
if ($path === 'logout') { 
    require_once __DIR__ . '/actions/logout.php'; 
    exit; 
} 
 
if ($path === 'login-handler') { 
    require_once __DIR__ . '/actions/login.php'; 
    exit; 
} 
 
if ($path === 'register-handler') { 
    require_once __DIR__ . '/actions/register.php'; 
    exit; 
} 

// Handle special cases for pages with hyphens
if ($path === 'mijn-reserveringen') {
    $path = 'mijn_reserveringen';
} else if ($path === 'reservering_bevestiging') {
    $path = 'reservering_bevestiging';
}

$page = empty($path) ? 'home' : $path; 
$file = __DIR__ . '/pages/' . $page . '.php'; 
 
if (file_exists($file)) { 
    include $file; 
} else { 
    http_response_code(404); 
    include __DIR__ . '/pages/404.php'; 
} 
