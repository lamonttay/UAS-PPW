<?php
// pages/delete_task.php
require_once '../includes/config.php';
require_once '../includes/auth.php';

requireLogin();

if (isset($_GET['id'])) {
    $taskId = (int)$_GET['id'];
    $userId = $_SESSION['user_id'];
    
    // Verify ownership
    $stmt = $pdo->prepare("SELECT id FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$taskId, $userId]);
    
    if ($stmt->fetch()) {
        $del = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
        $del->execute([$taskId]);
    }
}

header("Location: tasks.php");
exit;
?>
