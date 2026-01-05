<?php
/**
 * Simple safe migration script to add missing columns/tables
 * Run once on local (php migrate_db.php) or open in browser then delete the file.
 */
include __DIR__ . '/database/config.php';

function column_exists($conn, $table, $column) {
    $table = $conn->real_escape_string($table);
    $column = $conn->real_escape_string($column);
    $res = $conn->query("SHOW COLUMNS FROM `{$table}` LIKE '{$column}'");
    return ($res && $res->num_rows > 0);
}

$queries = [];

// parts table columns
if (!column_exists($conn, 'parts', 'deskripsi')) {
    $queries[] = "ALTER TABLE parts ADD COLUMN deskripsi TEXT DEFAULT NULL";
}
if (!column_exists($conn, 'parts', 'image')) {
    $queries[] = "ALTER TABLE parts ADD COLUMN image VARCHAR(255) DEFAULT NULL";
}
if (!column_exists($conn, 'parts', 'created_at')) {
    $queries[] = "ALTER TABLE parts ADD COLUMN created_at DATETIME DEFAULT CURRENT_TIMESTAMP";
}

// services
if (!column_exists($conn, 'services', 'deskripsi')) {
    $queries[] = "ALTER TABLE services ADD COLUMN deskripsi TEXT DEFAULT NULL";
}
if (!column_exists($conn, 'services', 'created_at')) {
    $queries[] = "ALTER TABLE services ADD COLUMN created_at DATETIME DEFAULT CURRENT_TIMESTAMP";
}

// messages
if (!column_exists($conn, 'messages', 'created_at')) {
    $queries[] = "ALTER TABLE messages ADD COLUMN created_at DATETIME DEFAULT CURRENT_TIMESTAMP";
}

// users table
$res = $conn->query("SHOW TABLES LIKE 'users'");
if (!$res || $res->num_rows === 0) {
    $queries[] = "CREATE TABLE users (
      id INT AUTO_INCREMENT PRIMARY KEY,
      username VARCHAR(100) UNIQUE NOT NULL,
      password VARCHAR(255) NOT NULL,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
}

if (empty($queries)) {
    echo "No migrations to run. Database appears up-to-date." . PHP_EOL;
    exit;
}

foreach ($queries as $q) {
    echo "Running: {$q}\n";
    if ($conn->query($q) === TRUE) {
        echo "Success\n\n";
    } else {
        echo "Error: " . $conn->error . "\n\n";
    }
}

echo "Migration finished. Please remove migrate_db.php after verifying (for safety)." . PHP_EOL;
?>