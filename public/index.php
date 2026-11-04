<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// When the project folder itself is the web root (http://localhost/starcliqs on XAMPP), the root
// .htaccess forwards requests into public/ without "/public" appearing in the URL. Point SCRIPT_NAME
// at the project folder so Laravel works out the right base path for routes and asset URLs.
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
if (str_ends_with($scriptName, '/public/index.php') && ! str_contains($requestPath, '/public/')) {
    $_SERVER['SCRIPT_NAME'] = $_SERVER['PHP_SELF'] = substr($scriptName, 0, -strlen('/public/index.php')).'/index.php';
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
