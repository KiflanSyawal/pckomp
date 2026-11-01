<?php
/**
 * Database Setup & Migration Script for pckomp
 * Initializes the SQLite database, creates tables, and populates seed data.
 */

$databaseDir = __DIR__ . DIRECTORY_SEPARATOR . 'database';
$databaseFile = $databaseDir . DIRECTORY_SEPARATOR . 'pckomp.sqlite';
$schemaFile = $databaseDir . DIRECTORY_SEPARATOR . 'schema.sql';
$seedFile = $databaseDir . DIRECTORY_SEPARATOR . 'seed.sql';

if (!is_dir($databaseDir)) {
    mkdir($databaseDir, 0777, true);
}

try {
    $pdo = new PDO('sqlite:' . $databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('PRAGMA foreign_keys = ON;');

    // 1. Run Schema
    if (file_exists($schemaFile)) {
        $schemaSql = file_get_contents($schemaFile);
        $pdo->exec($schemaSql);
    } else {
        throw new Exception("Schema file not found at: $schemaFile");
    }

    // 2. Run Seed
    if (file_exists($seedFile)) {
        $seedSql = file_get_contents($seedFile);
        $pdo->exec($seedSql);
    } else {
        throw new Exception("Seed file not found at: $seedFile");
    }

    // 3. Verify
    $adminCount = $pdo->query('SELECT COUNT(*) FROM admin')->fetchColumn();
    $catCount = $pdo->query('SELECT COUNT(*) FROM category')->fetchColumn();
    $prodCount = $pdo->query('SELECT COUNT(*) FROM product')->fetchColumn();

    $isCli = (php_sapi_name() === 'cli');

    if ($isCli) {
        echo "========================================\n";
        echo " PCKOMP Database Migration & Setup\n";
        echo "========================================\n";
        echo "[OK] DBMS: SQLite 3\n";
        echo "[OK] Database File: {$databaseFile}\n";
        echo "[OK] Admin Users: {$adminCount}\n";
        echo "[OK] Categories:  {$catCount}\n";
        echo "[OK] Products:    {$prodCount}\n";
        echo "[OK] Migration Completed Successfully!\n";
        echo "Default Login Credentials:\n";
        echo "  - Username: admin\n";
        echo "  - Password: admin\n";
        echo "========================================\n";
    } else {
        echo "<!DOCTYPE html><html><head><title>Database Setup</title></head><body style='font-family:sans-serif;padding:30px;'>";
        echo "<h2>Database Setup Completed Successfully!</h2>";
        echo "<p><strong>DBMS:</strong> SQLite 3</p>";
        echo "<p><strong>Database:</strong> {$databaseFile}</p>";
        echo "<ul>";
        echo "<li>Admin users: {$adminCount}</li>";
        echo "<li>Categories: {$catCount}</li>";
        echo "<li>Products: {$prodCount}</li>";
        echo "</ul>";
        echo "<p><strong>Default Login:</strong> admin / admin</p>";
        echo "<p><a href='login.php'>Go to Login Page</a></p>";
        echo "</body></html>";
    }
} catch (Exception $e) {
    if (php_sapi_name() === 'cli') {
        echo "[ERROR] Database Setup Failed: " . $e->getMessage() . "\n";
    } else {
        echo "<h2 style='color:red;'>Database Setup Failed</h2><p>" . htmlspecialchars($e->getMessage()) . "</p>";
    }
    exit(1);
}

