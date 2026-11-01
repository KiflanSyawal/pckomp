<?php
/**
 * Vercel Serverless Entry Point / Front Controller
 * Routes incoming web requests to the appropriate PHP scripts.
 */

// Fix working directory so includes work relative to project root
chdir(dirname(__DIR__));

$requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = trim($requestUri, '/');

// Handle root URL
if (empty($path) || $path === 'index.php') {
    require __DIR__ . '/../index.php';
    exit;
}

$targetFile = __DIR__ . '/../' . $path;

// Allow direct execution of valid PHP scripts in the project
if (file_exists($targetFile) && is_file($targetFile)) {
    $extension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    // Prevent direct execution of files in database folder
    if (strpos(realpath($targetFile), realpath(__DIR__ . '/../database')) === 0) {
        http_response_code(403);
        echo "403 Forbidden";
        exit;
    }

    if ($extension === 'php') {
        require $targetFile;
        exit;
    }
}

// Fallback: If URL doesn't have .php extension, check if matching .php file exists
if (file_exists($targetFile . '.php') && is_file($targetFile . '.php')) {
    require $targetFile . '.php';
    exit;
}

// 404 Not Found
http_response_code(404);
echo "404 Not Found";

