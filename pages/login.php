<?php
// pages/login.php
require_once '../includes/config.php';
require_once '../includes/auth.php';

if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please enter email and password.";
    } else {
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
}

$body_class = 'd-flex align-items-center justify-content-center vh-100';
require_once '../includes/header.php';
?>
<div class="card" style="width: 100%; max-width: 400px; padding: 40px 32px;">
    <div class="text-center mb-4">
        <div style="width: 72px; height: 72px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
            <img src="../assets/img/logo.png" alt="Momentask Logo" style="width: 100%; height: 100%; object-fit: contain;">
        </div>
        <h1 style="font-size: 24px; font-weight: 800; margin-bottom: 8px;">Welcome Back</h1>
        <p class="text-muted" style="font-size: 14px;">Log in to continue using Momentask.</p>
    </div>

    <?php if($error): ?>
    <div style="background: rgba(239,68,68,0.1); color: var(--danger); padding: 12px; border-radius: 8px; font-size: 13px; font-weight: 600; margin-bottom: 20px; text-align: center;">
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required placeholder="you@example.com" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
        </div>
        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required placeholder="••••••••">
        </div>
        <button type="submit" class="btn btn-primary w-100" style="padding: 12px; font-size: 15px;">Sign In</button>
    </form>
    
    <div class="text-center mt-4">
        <span class="text-muted" style="font-size: 13px;">Don't have an account? <a href="register.php" style="color: var(--primary); font-weight: 600;">Sign up</a></span>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
