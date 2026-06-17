<?php
// pages/dashboard.php
require_once '../includes/dashboard_header.php';

// Fetch summary
$stmt = $pdo->prepare("SELECT * FROM v_user_task_summary WHERE user_id = ?");
$stmt->execute([$user['id']]);
$summary = $stmt->fetch();

$total_tasks = $summary ? $summary['total_tasks'] : 0;
$completed = $summary ? $summary['completed_tasks'] : 0;
$pending = $summary ? $summary['pending_tasks'] : 0;
$pct = $total_tasks > 0 ? round(($completed / $total_tasks) * 100) : 0;

// Fetch today's tasks
$stmtTasks = $pdo->prepare("
    SELECT t.*, c.name as category_name, c.color as category_color 
    FROM tasks t 
    LEFT JOIN categories c ON t.category_id = c.id 
    WHERE t.user_id = ? 
    ORDER BY (t.status = 'completed') ASC, t.due_date ASC 
    LIMIT 6
");
$stmtTasks->execute([$user['id']]);
$tasks = $stmtTasks->fetchAll();

$greeting = 'Good ' . (date('H') < 12 ? 'Morning' : (date('H') < 17 ? 'Afternoon' : 'Evening'));
?>
<div style="max-width: 1200px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 28px; font-weight: 900;"><?= $greeting ?>, <?= htmlspecialchars(explode(' ', $user['name'])[0]) ?> 👋</h1>
        <p class="text-muted" style="font-size: 14px; margin-top: 4px;">
            <?= $completed ?> of <?= $total_tasks ?> tasks completed · <?= $pct ?>% done
        </p>
    </div>

    <!-- Stats row -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card h-100" style="padding: 20px;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div style="width: 40px; height: 40px; border-radius: 12px; background: rgba(79,70,229,0.08); display: flex; align-items: center; justify-content: center;">
                        <i data-lucide="check-square" color="#4F46E5" width="18"></i>
                    </div>
                </div>
                <div style="font-size: 24px; font-weight: 900; line-height: 1;"><?= $completed ?>/<?= $total_tasks ?></div>
                <div style="font-size: 13px; margin-top: 4px;" class="text-muted">Tasks Completed</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card h-100" style="padding: 20px;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div style="width: 40px; height: 40px; border-radius: 12px; background: rgba(245,158,11,0.08); display: flex; align-items: center; justify-content: center;">
                        <i data-lucide="clock" color="#F59E0B" width="18"></i>
                    </div>
                </div>
                <div style="font-size: 24px; font-weight: 900; line-height: 1;"><?= $pending ?></div>
                <div style="font-size: 13px; margin-top: 4px;" class="text-muted">Pending Tasks</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card h-100" style="padding: 20px;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div style="width: 40px; height: 40px; border-radius: 12px; background: rgba(239,68,68,0.08); display: flex; align-items: center; justify-content: center;">
                        <i data-lucide="flame" color="#EF4444" width="18"></i>
                    </div>
                </div>
                <div style="font-size: 24px; font-weight: 900; line-height: 1;">23 days</div>
                <div style="font-size: 13px; margin-top: 4px;" class="text-muted">Current Streak</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card h-100" style="padding: 20px;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div style="width: 40px; height: 40px; border-radius: 12px; background: rgba(124,58,237,0.08); display: flex; align-items: center; justify-content: center;">
                        <i data-lucide="zap" color="#7C3AED" width="18"></i>
                    </div>
                </div>
                <div style="font-size: 24px; font-weight: 900; line-height: 1;">3h 24m</div>
                <div style="font-size: 13px; margin-top: 4px;" class="text-muted">Focus Time</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Tasks List -->
        <div class="col-lg-8">
            <div class="card" style="padding: 24px;">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h2 style="font-size: 16px; font-weight: 700;">Recent Tasks</h2>
                        <p class="text-muted" style="font-size: 12px; margin-top: 2px;"><?= date('l, F j') ?></p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width: 120px; height: 6px; border-radius: 99px; background: #F1F5F9;">
                                <div id="progress-bar-fill" style="height: 6px; border-radius: 99px; background: var(--primary-gradient); width: <?= $pct ?>%; transition: width 0.4s ease;"></div>
                            </div>
                            <span id="progress-pct-text" style="font-size: 12px; font-weight: 600;" class="text-muted"><?= $pct ?>%</span>
                        </div>
                        <a href="add_task.php" style="width: 30px; height: 30px; border-radius: 10px; background: var(--primary-gradient); display: flex; align-items: center; justify-content: center; color: white;">
                            <i data-lucide="plus" width="15"></i>
                        </a>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2">
                    <?php if(empty($tasks)): ?>
                        <div class="text-center p-4 text-muted">
                            <i data-lucide="check-square" width="32" style="opacity: 0.3; margin-bottom: 8px;"></i>
                            <p style="font-size: 14px; font-weight: 500;">No tasks found. Get started by creating one!</p>
                        </div>
                    <?php endif; ?>
                    <?php foreach($tasks as $task): 
                        $is_done = $task['status'] === 'completed';
                        $priorityClass = 'badge-' . $task['priority'];
                        $priorityLabel = ucfirst($task['priority']);
                        if($task['priority'] === 'medium') $priorityLabel = 'Med';
                    ?>
                    <div class="task-item <?= $is_done ? 'done' : '' ?>">
                        <button class="task-checkbox <?= $is_done ? 'done' : '' ?>" data-id="<?= $task['id'] ?>">
                            <?php if($is_done): ?><i data-lucide="check" style="width: 14px; height: 14px;"></i><?php endif; ?>
                        </button>
                        <div style="flex: 1; min-width: 0;">
                            <div class="task-text text-truncate">
                                <?= htmlspecialchars($task['title']) ?>
                            </div>
                            <div class="d-flex align-items-center gap-2" style="margin-top: 3px;">
                                <span style="font-size: 11px;" class="text-muted">
                                    <?= $task['due_date'] ? date('M j', strtotime($task['due_date'])) : 'No due date' ?>
                                </span>
                                <?php if($task['category_name']): ?>
                                <span style="font-size: 11px; font-weight: 500; color: <?= $task['category_color'] ?>; background: <?= $task['category_color'] ?>20; padding: 1px 6px; border-radius: 6px;">
                                    <?= htmlspecialchars($task['category_name']) ?>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <span class="badge <?= $priorityClass ?> flex-shrink-0"><?= $priorityLabel ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <a href="tasks.php" class="btn w-100 mt-3" style="border: 1.5px dashed rgba(79,70,229,0.25); color: var(--primary); font-size: 13px; font-weight: 600;">
                    <i data-lucide="list" width="14"></i> View all tasks
                </a>
            </div>
        </div>

        <div class="col-lg-4">
            <?php
            $cal_month = isset($_GET['cal_month']) ? (int)$_GET['cal_month'] : (int)date('m');
            $cal_year = isset($_GET['cal_year']) ? (int)$_GET['cal_year'] : (int)date('Y');
            
            $first_day = date('w', strtotime(sprintf('%04d-%02d-01', $cal_year, $cal_month)));
            $days_in_month = date('t', strtotime(sprintf('%04d-%02d-01', $cal_year, $cal_month)));
            
            $prev_month = $cal_month - 1;
            $prev_year = $cal_year;
            if ($prev_month < 1) { $prev_month = 12; $prev_year--; }
            
            $next_month = $cal_month + 1;
            $next_year = $cal_year;
            if ($next_month > 12) { $next_month = 1; $next_year++; }
            
            $month_name = date('F Y', strtotime(sprintf('%04d-%02d-01', $cal_year, $cal_month)));
            ?>
            <!-- Calendar Widget -->
            <div class="card mb-4" style="padding: 20px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span style="font-weight: 600; font-size: 15px;"><?= $month_name ?></span>
                    <div class="d-flex gap-1">
                        <a href="?cal_month=<?= $prev_month ?>&cal_year=<?= $prev_year ?>" style="padding: 4px; border-radius: 6px; color: var(--text-muted);"><i data-lucide="chevron-left" width="16"></i></a>
                        <a href="?cal_month=<?= $next_month ?>&cal_year=<?= $next_year ?>" style="padding: 4px; border-radius: 6px; color: var(--text-muted);"><i data-lucide="chevron-right" width="16"></i></a>
                    </div>
                </div>
                <div class="d-grid text-center" style="grid-template-columns: repeat(7, 1fr); gap: 4px;">
                    <?php 
                    $days = ['Su','Mo','Tu','We','Th','Fr','Sa'];
                    foreach($days as $d) echo "<div style='font-size: 11px; font-weight: 600; color: var(--text-muted); padding-bottom: 6px;'>$d</div>";
                    
                    for($i=0; $i<$first_day; $i++) echo "<div></div>";
                    for($i=1; $i<=$days_in_month; $i++) {
                        $date_str = sprintf('%04d-%02d-%02d', $cal_year, $cal_month, $i);
                        $is_today = $date_str === date('Y-m-d');
                        $bg = $is_today ? 'var(--primary-gradient)' : 'transparent';
                        $color = $is_today ? 'white' : 'inherit';
                        $weight = $is_today ? '700' : '400';
                        $hover_bg = $is_today ? 'var(--primary-gradient)' : 'rgba(0,0,0,0.05)';
                        echo "<div style='aspect-ratio: 1; display: flex; align-items: center; justify-content: center;'>
                                <a href='tasks.php?date=$date_str' title='View tasks for $date_str' onmouseover=\"this.style.background='$hover_bg'\" onmouseout=\"this.style.background='$bg'\" style='text-decoration: none; width: 28px; height: 28px; border-radius: 8px; background: $bg; color: $color; font-weight: $weight; display: flex; align-items: center; justify-content: center; font-size: 12px; transition: background 0.2s;'>$i</a>
                              </div>";
                    }
                    ?>
                </div>
            </div>
            
            <!-- Quick actions -->
            <a href="categories.php" class="card d-block" style="padding: 16px; margin-bottom: 12px; transition: transform 0.2s;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(6,182,212,0.1); display: flex; align-items: center; justify-content: center;">
                        <i data-lucide="tag" color="#06B6D4" width="18"></i>
                    </div>
                    <div>
                        <div style="font-size: 13px; font-weight: 700; color: var(--text-main);">Manage Categories</div>
                        <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Organize your workspace</div>
                    </div>
                </div>
            </a>
            
            <a href="activity_log.php" class="card d-block" style="padding: 16px; transition: transform 0.2s;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(16,185,129,0.1); display: flex; align-items: center; justify-content: center;">
                        <i data-lucide="activity" color="#10B981" width="18"></i>
                    </div>
                    <div>
                        <div style="font-size: 13px; font-weight: 700; color: var(--text-main);">Activity History</div>
                        <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Review your progress</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
<?php require_once '../includes/dashboard_footer.php'; ?>
