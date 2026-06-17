<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$minutes = isset($data['minutes']) ? (int)$data['minutes'] : 0;

if ($minutes <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid minutes']);
    exit;
}

try {
    $user_id = $_SESSION['user_id'];
    
    // Update the focus time in database
    $stmt = $pdo->prepare("UPDATE users SET focus_time_minutes = focus_time_minutes + ? WHERE id = ?");
    $stmt->execute([$minutes, $user_id]);
    
    // Fetch the updated total to return it
    $fetchStmt = $pdo->prepare("SELECT focus_time_minutes FROM users WHERE id = ?");
    $fetchStmt->execute([$user_id]);
    $result = $fetchStmt->fetch();
    
    echo json_encode([
        'success' => true, 
        'total_focus_time' => $result['focus_time_minutes']
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
