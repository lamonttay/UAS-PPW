<?php
// pages/register.php
require_once '../includes/config.php';
require_once '../includes/auth.php';

if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email is already registered.";
        } else {
            // Create initials
            $words = explode(" ", $name);
            $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
            
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, avatar) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$name, $email, $hash, $initials])) {
                $success = "Registration successful! You can now log in.";
                // Create default categories for user
                $userId = $pdo->lastInsertId();
                $stmtCat = $pdo->prepare("INSERT INTO categories (user_id, name, color) VALUES (?, ?, ?)");
                $stmtCat->execute([$userId, 'Work', '#0066FF']);
                $stmtCat->execute([$userId, 'Personal', '#0066FF']);
                $stmtCat->execute([$userId, 'Health', '#10B981']);
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}

$body_class = 'd-flex align-items-center justify-content-center vh-100';
require_once '../includes/header.php';
?>
<div class="card" style="width: 100%; max-width: 400px; padding: 40px 32px;">
    <div class="text-center mb-4">
        <h1 style="font-size: 24px; font-weight: 800; margin-bottom: 8px;">Create Account</h1>
        <p class="text-muted" style="font-size: 14px;">Join Momentask today.</p>
    </div>

    <?php if($error): ?>
    <div style="background: rgba(239,68,68,0.1); color: var(--danger); padding: 12px; border-radius: 8px; font-size: 13px; font-weight: 600; margin-bottom: 20px; text-align: center;">
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>
    
    <?php if($success): ?>
    <div style="background: rgba(16,185,129,0.1); color: var(--success); padding: 12px; border-radius: 8px; font-size: 13px; font-weight: 600; margin-bottom: 20px; text-align: center;">
        <?= htmlspecialchars($success) ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required placeholder="John Doe" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required placeholder="you@example.com" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required placeholder="••••••••">
        </div>
        <div class="mb-4">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" required placeholder="••••••••">
        </div>
        <button type="submit" class="btn btn-primary w-100" style="padding: 12px; font-size: 15px;">Create Account</button>
    </form>
    
    <div class="text-center mt-4">
        <span class="text-muted" style="font-size: 13px;">Already have an account? <a href="login.php" style="color: var(--primary); font-weight: 600;">Sign in</a></span>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
