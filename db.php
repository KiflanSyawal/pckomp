<?php
/**
 * Database Connection & Configuration File
 * DBMS: SQLite 3 (via PHP PDO)
 * 
 * Replaces the unavailable MySQL/XAMPP database with a reliable,
 * zero-configuration, file-based SQLite database.
 */

// Ensure session is safely configured and started
if (session_status() === PHP_SESSION_NONE) {
    $currentSessPath = session_save_path();
    if (empty($currentSessPath) || !@is_writable($currentSessPath)) {
        $localSessDir = __DIR__ . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'sessions';
        if (!is_dir($localSessDir)) {
            @mkdir($localSessDir, 0777, true);
        }
        if (@is_writable($localSessDir)) {
            @ini_set('session.save_path', $localSessDir);
        }
    }
    @session_start();
}

$dbPath = __DIR__ . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'pckomp.sqlite';
$dbDir  = dirname($dbPath);

if (!is_dir($dbDir)) {
    @mkdir($dbDir, 0777, true);
}

$needBootstrap = !file_exists($dbPath) || filesize($dbPath) === 0;

try {
    $conn = new PDO('sqlite:' . $dbPath);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $conn->exec('PRAGMA foreign_keys = ON;');

    // Automatically create schema and seed data if database is fresh
    if ($needBootstrap) {
        $schemaFile = $dbDir . DIRECTORY_SEPARATOR . 'schema.sql';
        $seedFile   = $dbDir . DIRECTORY_SEPARATOR . 'seed.sql';

        if (file_exists($schemaFile)) {
            $conn->exec(file_get_contents($schemaFile));
        }
        if (file_exists($seedFile)) {
            $conn->exec(file_get_contents($seedFile));
        }
    }
} catch (PDOException $e) {
    die('Gagal Terhubung Kedalam Database: ' . $e->getMessage());
}
?>
