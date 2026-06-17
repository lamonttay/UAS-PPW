<?php
// index.php
require_once 'includes/config.php';
require_once 'includes/auth.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    header("Location: pages/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Momentask - Productivity App</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap-grid.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body style="background: #F8FAFC; overflow-x: hidden;">

    <!-- Navbar -->
    <nav class="landing-nav">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: linear-gradient(135deg, #4F46E5, #7C3AED); display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="zap" color="white" width="16"></i>
                </div>
                <span style="font-weight: 800; color: #111827; font-size: 17px; letter-spacing: -0.02em;">Momentask</span>
            </div>
            <div class="d-none d-md-flex gap-4" style="color: #6B7280; font-size: 14px;">
                <a href="#features" style="font-weight: 500;">Features</a>
                <a href="#reviews" style="font-weight: 500;">Reviews</a>
                <a href="#pricing" style="font-weight: 500;">Pricing</a>
            </div>
            <div class="d-none d-md-flex gap-3 align-items-center">
                <a href="pages/login.php" style="color: #6B7280; font-size: 14px; font-weight: 600;">Sign In</a>
                <a href="pages/register.php" class="btn btn-primary">Get Started Free</a>
            </div>
            <button class="d-md-none" style="border: none; background: transparent;" id="mobile-menu-btn">
                <i data-lucide="menu" color="#374151"></i>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobile-menu" style="display: none; position: fixed; inset: 0; z-index: 40; background: rgba(248,250,252,0.97); backdrop-filter: blur(16px); padding-top: 80px;" class="px-4">
        <div class="d-flex flex-column gap-4 text-center">
            <a href="#features" class="text-dark fw-bold" style="font-size: 18px;">Features</a>
            <a href="#reviews" class="text-dark fw-bold" style="font-size: 18px;">Reviews</a>
            <a href="pages/login.php" class="text-dark fw-bold" style="font-size: 18px;">Sign In</a>
            <a href="pages/register.php" class="btn btn-primary w-100 py-3 mt-2" style="font-size: 16px;">Get Started Free</a>
        </div>
    </div>

    <!-- Hero -->
    <section class="hero-section">
        <div class="landing-blob landing-blob-1"></div>
        <div class="landing-blob landing-blob-2"></div>
        
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px; border-radius: 99px; background: rgba(79,70,229,0.08); border: 1px solid rgba(79,70,229,0.2); color: #4F46E5; font-size: 13px; font-weight: 500; margin-bottom: 24px;">
                        <i data-lucide="zap" width="13"></i> Introducing Momentask 2.0
                    </div>
                    <h1 style="font-size: clamp(40px, 5.5vw, 68px); line-height: 1.08; font-weight: 900; color: #0F172A; margin-bottom: 24px;">
                        Build Momentum,<br>
                        <span style="background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 50%, #06B6D4 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">One Task</span> at a Time.
                    </h1>
                    <p style="font-size: 18px; line-height: 1.7; color: #64748B; margin-bottom: 36px; max-width: 480px;">
                        Stop procrastinating and start making real progress. Tasks, schedules, habits, and focus sessions — all in one beautifully designed workspace.
                    </p>
                    <div class="d-flex flex-wrap gap-3 mb-5">
                        <a href="pages/register.php" class="btn btn-primary" style="padding: 14px 24px; border-radius: 14px; font-size: 15px; font-weight: 700;">
                            Get Started Free <i data-lucide="arrow-right" width="18"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <!-- Dashboard Mockup Image or CSS representation -->
                    <div style="background: #13111D; border-radius: 20px; border: 1px solid rgba(99,91,255,0.3); box-shadow: 0 32px 80px rgba(79,70,229,0.3); padding: 20px; height: 400px; display: flex; align-items: center; justify-content: center; color: white;">
                        [Dashboard App Preview]
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" style="padding: 96px 24px;">
        <div class="container">
            <div class="text-center mb-5">
                <div style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px; border-radius: 99px; background: rgba(79,70,229,0.08); border: 1px solid rgba(79,70,229,0.2); color: #4F46E5; font-size: 13px; margin-bottom: 16px;">
                    Everything you need
                </div>
                <h2 style="font-size: clamp(32px, 4vw, 52px); font-weight: 900; color: #0F172A; margin-bottom: 16px;">Stop switching between apps.</h2>
            </div>

            <div class="feature-grid">
                <!-- Feature 1 -->
                <div class="card" style="border: 1px solid rgba(0,0,0,0.06); transition: transform 0.2s;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(79,70,229,0.09); display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                        <i data-lucide="check-square" color="#4F46E5"></i>
                    </div>
                    <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 8px;">Smart To-Do Lists</h3>
                    <p style="font-size: 14px; color: #64748B;">Organize tasks with priorities, due dates, and tags. Never lose track of what matters most.</p>
                </div>
                <!-- Feature 2 -->
                <div class="card" style="border: 1px solid rgba(0,0,0,0.06);">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(6,182,212,0.09); display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                        <i data-lucide="calendar" color="#06B6D4"></i>
                    </div>
                    <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 8px;">Daily & Weekly Planner</h3>
                    <p style="font-size: 14px; color: #64748B;">Plan your days and weeks with a beautiful drag-and-drop interface.</p>
                </div>
                <!-- Feature 3 -->
                <div class="card" style="border: 1px solid rgba(0,0,0,0.06);">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16,185,129,0.09); display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                        <i data-lucide="repeat" color="#10B981"></i>
                    </div>
                    <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 8px;">Habit Tracking</h3>
                    <p style="font-size: 14px; color: #64748B;">Build powerful habits with streak tracking and visual progress rings.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Dark -->
    <section style="padding: 96px 24px; background: linear-gradient(135deg, #0F0D1A 0%, #1E1B4B 50%, #0F0D1A 100%); text-align: center; color: white;">
        <div class="container">
            <h2 style="font-size: clamp(32px, 4vw, 52px); font-weight: 900; color: white; margin-bottom: 48px;">Real results from real users.</h2>
            <div class="row gap-4 justify-content-center">
                <div class="col-md-3">
                    <div style="font-size: clamp(40px, 5vw, 60px); font-weight: 900; line-height: 1;">3.2x</div>
                    <div style="font-weight: 600; margin-top: 8px;">More tasks completed</div>
                </div>
                <div class="col-md-3">
                    <div style="font-size: clamp(40px, 5vw, 60px); font-weight: 900; line-height: 1;">47m</div>
                    <div style="font-weight: 600; margin-top: 8px;">Saved per day</div>
                </div>
                <div class="col-md-3">
                    <div style="font-size: clamp(40px, 5vw, 60px); font-weight: 900; line-height: 1;">91%</div>
                    <div style="font-weight: 600; margin-top: 8px;">Habit success rate</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer style="padding: 48px 24px; background: #080614;">
        <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
            <div class="d-flex align-items-center gap-2">
                <div style="width: 28px; height: 28px; border-radius: 8px; background: linear-gradient(135deg, #4F46E5, #7C3AED); display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="zap" color="white" width="14"></i>
                </div>
                <span style="font-weight: 800; color: white; font-size: 15px;">Momentask</span>
            </div>
            <p style="color: rgba(255,255,255,0.25); font-size: 13px; margin: 0;">© 2026 Momentask. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        lucide.createIcons();
        $('#mobile-menu-btn').click(function() {
            $('#mobile-menu').fadeToggle();
        });
    </script>
</body>
</html>
