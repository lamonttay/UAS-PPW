<?php
// includes/auth.php
require_once 'config.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: " . BASE_URL . "/pages/login.php");
        exit;
    }
}

function getCurrentUser($pdo) {
    if (!isLoggedIn()) return null;
    
    $user_id = $_SESSION['user_id'];
    
    // Fetch the user data
    $stmt = $pdo->prepare("SELECT id, name, email, avatar, last_active_date, current_streak, focus_time_minutes FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    if ($user) {
        $today = date('Y-m-d');
        $last_active = $user['last_active_date'];
        
        if ($last_active !== $today) {
            $yesterday = date('Y-m-d', strtotime('-1 day'));
            
            if ($last_active === $yesterday) {
                // Consecutive day
                $new_streak = $user['current_streak'] + 1;
            } else {
                // Break in streak
                $new_streak = 1;
            }
            
            // Update database
            $updateStmt = $pdo->prepare("UPDATE users SET last_active_date = ?, current_streak = ? WHERE id = ?");
            $updateStmt->execute([$today, $new_streak, $user_id]);
            
            // Update local user array so the current page load sees the new values
            $user['last_active_date'] = $today;
            $user['current_streak'] = $new_streak;
        }
    }
    
    return $user;
}
?>
