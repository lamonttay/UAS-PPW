<?php
// includes/dashboard_header.php
require_once 'config.php';
require_once 'auth.php';
requireLogin();

$user = getCurrentUser($pdo);
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Momentask Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css?v=2">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <div id="toast-container" class="toast-container"></div>
    <div class="mobile-overlay" id="mobile-overlay"></div>
    
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div style="height: 64px; display: flex; align-items: center; padding: 0 16px; border-bottom: 1px solid var(--border-color); overflow: hidden;">
                <div class="avatar" style="border-radius: 6px; background: white; display: flex; align-items: center; justify-content: center;">
                    <img src="../assets/img/logo.png" alt="Logo" style="width: 24px; height: 24px; object-fit: contain;">
                </div>
                <div class="sidebar-text" style="margin-left: 12px; overflow: hidden;">
                    <div style="font-weight: 800; color: white; font-size: 16px; letter-spacing: -0.02em; white-space: nowrap;">Momentask</div>
                    <div style="font-size: 10px; color: #94A3B8; white-space: nowrap; margin-top: 1px;">Small steps. Big progress.</div>
                </div>
            </div>
            
            <nav style="flex: 1; padding: 12px; overflow-y: auto; display: flex; flex-direction: column; gap: 2px;">
                <a href="dashboard.php" class="nav-item <?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
                    <i data-lucide="layout-dashboard" width="18"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
                <a href="tasks.php" class="nav-item <?= in_array($current_page, ['tasks.php', 'add_task.php', 'edit_task.php']) ? 'active' : '' ?>">
                    <i data-lucide="check-square" width="18"></i>
                    <span class="sidebar-text">Tasks</span>
                </a>
                <a href="categories.php" class="nav-item <?= $current_page == 'categories.php' ? 'active' : '' ?>">
                    <i data-lucide="tag" width="18"></i>
                    <span class="sidebar-text">Categories</span>
                </a>
                <a href="activity_log.php" class="nav-item <?= $current_page == 'activity_log.php' ? 'active' : '' ?>">
                    <i data-lucide="activity" width="18"></i>
                    <span class="sidebar-text">Activity Log</span>
                </a>
            </nav>
            
            <div style="padding: 12px; border-top: 1px solid var(--border-color);">
                <div style="display: flex; align-items: center; gap: 12px; padding: 10px 12px; border-radius: 6px; background: rgba(255,255,255,0.05);">
                    <div class="avatar"><?= htmlspecialchars($user['avatar']) ?></div>
                    <div class="sidebar-text" style="overflow: hidden; flex: 1;">
                        <div style="font-size: 13px; font-weight: 700; color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($user['name']) ?></div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="top-header">
                <button id="toggle-sidebar" class="d-none d-md-flex align-items-center justify-content-center" style="padding: 8px; border-radius: 6px; border: none; background: transparent; cursor: pointer; color: var(--text-muted);">
                    <i data-lucide="chevron-left" width="18"></i>
                </button>
                <button id="mobile-menu-btn" class="d-md-none align-items-center justify-content-center" style="padding: 8px; border-radius: 6px; border: none; background: transparent; cursor: pointer; color: var(--text-muted);">
                    <i data-lucide="menu" width="18"></i>
                </button>
                
                <div style="display: flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: 6px; background: #F3F4F6; flex: 1; max-width: 320px;" class="hide-mobile">
                    <i data-lucide="search" width="15" style="color: var(--text-muted);"></i>
                    <span style="font-size: 13px; color: var(--text-muted);">Search tasks...</span>
                </div>
                
                <div style="flex: 1;"></div>
                
                <a href="logout.php" style="padding: 8px; border-radius: 6px; border: none; background: transparent; cursor: pointer; color: var(--danger); display: flex;" title="Logout">
                    <i data-lucide="log-out" width="18"></i>
                </a>
                
                <a href="add_task.php" class="btn btn-primary" style="padding: 8px 16px; font-size: 13px;">
                    <i data-lucide="plus" width="15"></i> <span class="d-none d-sm-inline">New Task</span>
                </a>
            </header>
            
            <div class="page-content">
