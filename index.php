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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap-grid.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=2">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body style="background: #FAFAFA; overflow-x: hidden;">

    <!-- Navbar -->
    <nav class="landing-nav">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <div style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                    <img src="assets/img/logo.png" alt="Momentask Logo"
                        style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <span
                    style="font-weight: 800; color: #111827; font-size: 17px; letter-spacing: -0.02em;">Momentask</span>
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
    <div id="mobile-menu"
        style="display: none; position: fixed; inset: 0; z-index: 40; background: rgba(248,250,252,0.97); backdrop-filter: blur(16px); padding-top: 80px;"
        class="px-4">
        <div class="d-flex flex-column gap-4 text-center">
            <a href="#features" class="text-dark fw-bold" style="font-size: 18px;">Features</a>
            <a href="#reviews" class="text-dark fw-bold" style="font-size: 18px;">Reviews</a>
            <a href="pages/login.php" class="text-dark fw-bold" style="font-size: 18px;">Sign In</a>
            <a href="pages/register.php" class="btn btn-primary w-100 py-3 mt-2" style="font-size: 16px;">Get Started
                Free</a>
        </div>
    </div>

    <!-- Hero -->
    <section class="hero-section">
        <div class="landing-blob landing-blob-1"></div>
        <div class="landing-blob landing-blob-2"></div>

        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1
                        style="font-size: clamp(40px, 5.5vw, 68px); line-height: 1.08; font-weight: 900; color: #0F172A; margin-bottom: 24px;">
                        Build Momentum,<br>
                        <span
                            style="color: #0066FF;">One
                            Task</span> at a Time.
                    </h1>
                    <p
                        style="font-size: 18px; line-height: 1.7; color: #64748B; margin-bottom: 36px; max-width: 480px;">
                        Stop procrastinating and start making real progress. Tasks, schedules, habits, and focus
                        sessions — all in one beautifully designed workspace.
                    </p>
                    <div class="d-flex flex-wrap gap-3 mb-5">
                        <a href="pages/register.php" class="btn btn-primary"
                            style="padding: 14px 24px; border-radius: 6px; font-size: 15px; font-weight: 700;">
                            Get Started Free <i data-lucide="arrow-right" width="18"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block position-relative" style="z-index: 10;">
                    <!-- Dashboard Mockup CSS representation -->
                    <div
                        style="background: #ffffff; border-radius: 8px; border: 1px solid rgba(0,0,0,0.08); box-shadow: 0 32px 80px rgba(0,102,255,0.15); height: 440px; width: 100%; overflow: hidden; display: flex; flex-direction: column;">
                        <!-- Mock Header -->
                        <div
                            style="height: 50px; border-bottom: 1px solid rgba(0,0,0,0.05); display: flex; align-items: center; padding: 0 20px; gap: 8px;">
                            <div style="width: 12px; height: 12px; border-radius: 50%; background: #EF4444;"></div>
                            <div style="width: 12px; height: 12px; border-radius: 50%; background: #F59E0B;"></div>
                            <div style="width: 12px; height: 12px; border-radius: 50%; background: #10B981;"></div>
                        </div>
                        <div style="flex: 1; display: flex;">
                            <!-- Mock Sidebar -->
                            <div
                                style="width: 160px; border-right: 1px solid rgba(0,0,0,0.05); padding: 20px; display: flex; flex-direction: column; gap: 20px; background: #0F172A;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div
                                        style="width: 24px; height: 24px; border-radius: 6px; background: white; display: flex; align-items: center; justify-content: center;">
                                        <div style="width: 16px; height: 16px; background: #0066FF; border-radius: 4px;"></div>
                                    </div>
                                    <div
                                        style="height: 10px; width: 60%; background: rgba(255,255,255,0.2); border-radius: 6px;">
                                    </div>
                                </div>
                                <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 20px;">
                                    <div
                                        style="height: 12px; width: 80%; background: rgba(0,102,255,0.8); border-radius: 6px;">
                                    </div>
                                    <div
                                        style="height: 12px; width: 60%; background: rgba(255,255,255,0.15); border-radius: 6px;">
                                    </div>
                                    <div
                                        style="height: 12px; width: 70%; background: rgba(255,255,255,0.15); border-radius: 6px;">
                                    </div>
                                    <div
                                        style="height: 12px; width: 50%; background: rgba(255,255,255,0.15); border-radius: 6px;">
                                    </div>
                                </div>
                            </div>
                            <!-- Mock Content -->
                            <div
                                style="flex: 1; padding: 24px; display: flex; flex-direction: column; gap: 20px; background: #ffffff;">
                                <div style="height: 24px; width: 40%; background: rgba(0,0,0,0.1); border-radius: 6px;">
                                </div>

                                <div style="display: flex; gap: 12px;">
                                    <div
                                        style="flex: 1; height: 84px; background: white; border: 1px solid rgba(0,0,0,0.08); box-shadow: 0 4px 12px rgba(0,0,0,0.02); border-radius: 6px; padding: 14px;">
                                        <div
                                            style="height: 24px; width: 36px; background: rgba(0,102,255,0.1); border-radius: 8px; margin-bottom: 12px;">
                                        </div>
                                        <div
                                            style="height: 8px; width: 50%; background: rgba(0,0,0,0.06); border-radius: 4px;">
                                        </div>
                                    </div>
                                    <div
                                        style="flex: 1; height: 84px; background: white; border: 1px solid rgba(0,0,0,0.08); box-shadow: 0 4px 12px rgba(0,0,0,0.02); border-radius: 6px; padding: 14px;">
                                        <div
                                            style="height: 24px; width: 36px; background: rgba(16,185,129,0.1); border-radius: 8px; margin-bottom: 12px;">
                                        </div>
                                        <div
                                            style="height: 8px; width: 50%; background: rgba(0,0,0,0.06); border-radius: 4px;">
                                        </div>
                                    </div>
                                </div>

                                <div
                                    style="flex: 1; display: flex; flex-direction: column; gap: 12px; margin-top: 8px;">
                                    <div
                                        style="height: 52px; width: 100%; background: white; border: 1px solid rgba(0,0,0,0.06); border-radius: 6px; display: flex; align-items: center; justify-content: space-between; padding: 0 16px;">
                                        <div style="display: flex; align-items: center; gap: 12px; width: 100%;">
                                            <div
                                                style="width: 18px; height: 18px; border-radius: 4px; border: 2px solid rgba(0,0,0,0.1);">
                                            </div>
                                            <div
                                                style="height: 10px; width: 60%; background: rgba(0,0,0,0.06); border-radius: 4px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        style="height: 52px; width: 100%; background: white; border: 1px solid rgba(0,0,0,0.06); border-radius: 6px; display: flex; align-items: center; justify-content: space-between; padding: 0 16px;">
                                        <div style="display: flex; align-items: center; gap: 12px; width: 100%;">
                                            <div
                                                style="width: 18px; height: 18px; border-radius: 4px; background: #0066FF; display: flex; align-items: center; justify-content: center;">
                                                <i data-lucide="check" width="12" color="white"></i></div>
                                            <div
                                                style="height: 10px; width: 40%; background: rgba(0,0,0,0.06); border-radius: 4px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        style="height: 52px; width: 100%; background: white; border: 1px solid rgba(0,0,0,0.06); border-radius: 6px; display: flex; align-items: center; justify-content: space-between; padding: 0 16px;">
                                        <div style="display: flex; align-items: center; gap: 12px; width: 100%;">
                                            <div
                                                style="width: 18px; height: 18px; border-radius: 4px; border: 2px solid rgba(0,0,0,0.1);">
                                            </div>
                                            <div
                                                style="height: 10px; width: 70%; background: rgba(0,0,0,0.06); border-radius: 4px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Decorative Floating Element -->
                    <div
                        style="position: absolute; bottom: 40px; right: -20px; background: rgba(255,255,255,0.95); backdrop-filter: blur(12px); border: 1px solid rgba(0,0,0,0.08); border-radius: 8px; padding: 16px; display: flex; align-items: center; gap: 14px; box-shadow: 0 20px 40px rgba(0,0,0,0.12); z-index: 20;">
                        <div
                            style="width: 40px; height: 40px; border-radius: 50%; background: var(--success); display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="check" color="white" width="20"></i>
                        </div>
                        <div>
                            <div style="color: #1E293B; font-weight: 700; font-size: 14px;">Task Completed</div>
                            <div style="color: #64748B; font-size: 12px;">Submit project to professor</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" style="padding: 96px 24px;">
        <div class="container">
            <div class="text-center mb-5">
                <div
                    style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px; border-radius: 8px; background: rgba(0,102,255,0.08); border: 1px solid rgba(0,102,255,0.2); color: #0066FF; font-size: 13px; margin-bottom: 16px;">
                    Everything you need
                </div>
                <h2 style="font-size: clamp(32px, 4vw, 52px); font-weight: 900; color: #0F172A; margin-bottom: 16px;">
                    Stop switching between apps.</h2>
            </div>

            <div class="feature-grid">
                <!-- Feature 1 -->
                <div class="card" style="border: 1px solid rgba(0,0,0,0.06); transition: transform 0.2s;">
                    <div
                        style="width: 48px; height: 48px; border-radius: 6px; background: rgba(0,102,255,0.09); display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                        <i data-lucide="check-square" color="#0066FF"></i>
                    </div>
                    <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 8px;">Smart To-Do Lists
                    </h3>
                    <p style="font-size: 14px; color: #64748B;">Organize tasks with priorities, due dates, and tags.
                        Never lose track of what matters most.</p>
                </div>
                <!-- Feature 2 -->
                <div class="card" style="border: 1px solid rgba(0,0,0,0.06);">
                    <div
                        style="width: 48px; height: 48px; border-radius: 6px; background: rgba(6,182,212,0.09); display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                        <i data-lucide="calendar" color="#06B6D4"></i>
                    </div>
                    <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 8px;">Daily & Weekly
                        Planner</h3>
                    <p style="font-size: 14px; color: #64748B;">Plan your days and weeks with a beautiful drag-and-drop
                        interface.</p>
                </div>
                <!-- Feature 3 -->
                <div class="card" style="border: 1px solid rgba(0,0,0,0.06);">
                    <div
                        style="width: 48px; height: 48px; border-radius: 6px; background: rgba(16,185,129,0.09); display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                        <i data-lucide="repeat" color="#10B981"></i>
                    </div>
                    <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 8px;">Habit Tracking
                    </h3>
                    <p style="font-size: 14px; color: #64748B;">Build powerful habits with streak tracking and visual
                        progress rings.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews -->
    <section id="reviews" style="padding: 96px 24px; background: #FAFAFA;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 style="font-size: clamp(32px, 4vw, 52px); font-weight: 900; color: #0F172A; margin-bottom: 16px;">
                    Loved by high achievers.</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card p-4 h-100" style="border: 1px solid rgba(0,0,0,0.06);">
                        <div class="d-flex gap-1 mb-3">
                            <i data-lucide="star" fill="#F59E0B" color="#F59E0B" width="16"></i>
                            <i data-lucide="star" fill="#F59E0B" color="#F59E0B" width="16"></i>
                            <i data-lucide="star" fill="#F59E0B" color="#F59E0B" width="16"></i>
                            <i data-lucide="star" fill="#F59E0B" color="#F59E0B" width="16"></i>
                            <i data-lucide="star" fill="#F59E0B" color="#F59E0B" width="16"></i>
                        </div>
                        <p style="font-style: italic; color: #333; margin-bottom: 20px;">"Momentask has completely
                            changed how I organize my studies. Highly recommended!"</p>
                        <div style="font-weight: 700; color: #0F172A; margin-top: auto;">- Sarah J.</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 h-100" style="border: 1px solid rgba(0,0,0,0.06);">
                        <div class="d-flex gap-1 mb-3">
                            <i data-lucide="star" fill="#F59E0B" color="#F59E0B" width="16"></i>
                            <i data-lucide="star" fill="#F59E0B" color="#F59E0B" width="16"></i>
                            <i data-lucide="star" fill="#F59E0B" color="#F59E0B" width="16"></i>
                            <i data-lucide="star" fill="#F59E0B" color="#F59E0B" width="16"></i>
                            <i data-lucide="star" fill="#F59E0B" color="#F59E0B" width="16"></i>
                        </div>
                        <p style="font-style: italic; color: #333; margin-bottom: 20px;">"The cleanest interface I've
                            ever used. It actually makes me want to do my tasks."</p>
                        <div style="font-weight: 700; color: #0F172A; margin-top: auto;">- Michael K.</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 h-100" style="border: 1px solid rgba(0,0,0,0.06);">
                        <div class="d-flex gap-1 mb-3">
                            <i data-lucide="star" fill="#F59E0B" color="#F59E0B" width="16"></i>
                            <i data-lucide="star" fill="#F59E0B" color="#F59E0B" width="16"></i>
                            <i data-lucide="star" fill="#F59E0B" color="#F59E0B" width="16"></i>
                            <i data-lucide="star" fill="#F59E0B" color="#F59E0B" width="16"></i>
                            <i data-lucide="star" fill="#F59E0B" color="#F59E0B" width="16"></i>
                        </div>
                        <p style="font-style: italic; color: #333; margin-bottom: 20px;">"The calendar and habits
                            integration is genius. I finally uninstalled 3 other apps."</p>
                        <div style="font-weight: 700; color: #0F172A; margin-top: auto;">- Emily R.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing -->
    <section id="pricing" style="padding: 96px 24px;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 style="font-size: clamp(32px, 4vw, 52px); font-weight: 900; color: #0F172A; margin-bottom: 16px;">
                    Simple, transparent pricing.</h2>
            </div>
            <div class="row justify-content-center gap-4">
                <div class="col-md-5 col-lg-4">
                    <div class="card p-5" style="border: 1px solid rgba(0,0,0,0.06); height: 100%;">
                        <h3 style="font-size: 24px; font-weight: 800; margin-bottom: 16px;">Free</h3>
                        <div style="font-size: 48px; font-weight: 900; color: #0F172A; margin-bottom: 24px;">$0</div>
                        <ul class="list-unstyled mb-4"
                            style="color: #64748B; font-size: 15px; display: flex; flex-direction: column; gap: 12px;">
                            <li><i data-lucide="check" color="var(--success)" width="16" class="me-2"></i>Unlimited
                                tasks</li>
                            <li><i data-lucide="check" color="var(--success)" width="16" class="me-2"></i>Basic
                                categories</li>
                            <li><i data-lucide="check" color="var(--success)" width="16" class="me-2"></i>Daily calendar
                                view</li>
                        </ul>
                        <a href="pages/register.php" class="btn btn-outline w-100 py-3 mt-auto"
                            style="font-weight: 700;">Get Started Free</a>
                    </div>
                </div>
                <div class="col-md-5 col-lg-4">
                    <div class="card p-5"
                        style="border: 2px solid var(--primary); box-shadow: 0 20px 40px rgba(0,102,255,0.15); height: 100%; position: relative;">
                        <div
                            style="position: absolute; top: -14px; left: 50%; transform: translateX(-50%); background: var(--primary); color: white; padding: 4px 16px; border-radius: 8px; font-size: 12px; font-weight: 700;">
                            MOST POPULAR</div>
                        <h3 style="font-size: 24px; font-weight: 800; margin-bottom: 16px;">Pro</h3>
                        <div style="font-size: 48px; font-weight: 900; color: #0F172A; margin-bottom: 24px;">$5<span
                                style="font-size: 16px; color: #64748B; font-weight: 500;">/mo</span></div>
                        <ul class="list-unstyled mb-4"
                            style="color: #64748B; font-size: 15px; display: flex; flex-direction: column; gap: 12px;">
                            <li><i data-lucide="check" color="var(--success)" width="16" class="me-2"></i>Everything in
                                Free</li>
                            <li><i data-lucide="check" color="var(--success)" width="16" class="me-2"></i>Advanced habit
                                tracking</li>
                            <li><i data-lucide="check" color="var(--success)" width="16" class="me-2"></i>Detailed
                                statistics</li>
                        </ul>
                        <a href="pages/register.php" class="btn btn-primary w-100 py-3 mt-auto"
                            style="font-weight: 700;">Upgrade to Pro</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Dark -->
    <section
        style="padding: 96px 24px; background: linear-gradient(135deg, #0F0D1A 0%, #1E1B4B 50%, #0F0D1A 100%); text-align: center; color: white;">
        <div class="container">
            <h2 style="font-size: clamp(32px, 4vw, 52px); font-weight: 900; color: white; margin-bottom: 48px;">Real
                results from real users.</h2>
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
                <div style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                    <img src="assets/img/logo.png" alt="Momentask Logo"
                        style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <span style="font-weight: 800; color: white; font-size: 15px;">Momentask</span>
            </div>
            <p style="color: rgba(255,255,255,0.25); font-size: 13px; margin: 0;">© 2026 Momentask. All rights reserved.
            </p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        lucide.createIcons();
        $('#mobile-menu-btn').click(function (e) {
            e.stopPropagation();
            $('#mobile-menu').slideToggle('fast');
        });

        // Close menu when clicking outside
        $(document).click(function (e) {
            if (!$(e.target).closest('#mobile-menu, #mobile-menu-btn').length) {
                $('#mobile-menu').slideUp('fast');
            }
        });
    </script>
</body>

</html>