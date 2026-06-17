<?php
require_once 'includes/config.php';

try {
    // Add columns if they don't exist
    $pdo->exec("ALTER TABLE users ADD COLUMN last_active_date DATE DEFAULT NULL");
    echo "Added last_active_date.\n";
} catch (PDOException $e) {
    echo "last_active_date might already exist.\n";
}

try {
    $pdo->exec("ALTER TABLE users ADD COLUMN current_streak INT DEFAULT 0");
    echo "Added current_streak.\n";
} catch (PDOException $e) {
    echo "current_streak might already exist.\n";
}

try {
    $pdo->exec("ALTER TABLE users ADD COLUMN focus_time_minutes INT DEFAULT 0");
    echo "Added focus_time_minutes.\n";
} catch (PDOException $e) {
    echo "focus_time_minutes might already exist.\n";
}

// Update database.sql to include these columns in the users table CREATE TABLE statement
?>
