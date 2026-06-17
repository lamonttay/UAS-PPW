<?php
// pages/toggle_task.php
require_once '../includes/config.php';
require_once '../includes/auth.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $taskId = (int)$_POST['id'];
    $userId = $_SESSION['user_id'];
    
    // Check if task exists and belongs to user
    $stmt = $pdo->prepare("SELECT status FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$taskId, $userId]);
    $task = $stmt->fetch();
    
    if ($task) {
        $newStatus = $task['status'] === 'completed' ? 'pending' : 'completed';
        
        $update = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ?");
        if ($update->execute([$newStatus, $taskId])) {
            
            // Recalculate percentage for UI update
            $stmtSum = $pdo->prepare("SELECT COUNT(*) as total, SUM(status = 'completed') as completed FROM tasks WHERE user_id = ?");
            $stmtSum->execute([$userId]);
            $sum = $stmtSum->fetch();
            $pct = $sum['total'] > 0 ? round(($sum['completed'] / $sum['total']) * 100) : 0;
            
            echo json_encode(['success' => true, 'status' => $newStatus, 'pct' => $pct]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Database error']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Task not found']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>
