<?php
require_once 'includes/config.php';

$password = 'password123';
$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("UPDATE users SET password = ?");
    $stmt->execute([$hash]);
    echo "Successfully updated passwords to password123!";
} catch (Exception $e) {
    echo "Error updating passwords: " . $e->getMessage();
}
?>
