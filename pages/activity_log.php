<?php
// pages/activity_log.php
require_once '../includes/dashboard_header.php';

// Complex Query (JOIN 3 Tabel: task_logs, tasks, users)
$query = "
    SELECT tl.id as log_id, tl.action, tl.note, tl.logged_at, 
           t.title as task_title, 
           u.name as user_name 
    FROM task_logs tl
    JOIN tasks t ON tl.task_id = t.id
    JOIN users u ON tl.user_id = u.id
    WHERE tl.user_id = ?
    ORDER BY tl.logged_at DESC
    LIMIT 50
";
$stmt = $pdo->prepare($query);
$stmt->execute([$user['id']]);
$logs = $stmt->fetchAll();
?>
<div style="max-width: 800px; margin: 0 auto;">
    <div class="mb-4">
        <h1 style="font-size: 28px; font-weight: 900;">Activity Log</h1>
        <p class="text-muted" style="font-size: 13px; margin-top: 4px;">Track your task interactions and progress</p>
    </div>

    <div class="card" style="padding: 24px;">
        <div class="d-flex flex-column gap-3">
            <?php if(empty($logs)): ?>
                <div class="text-center p-4 text-muted">
                    <i data-lucide="activity" width="32" style="opacity: 0.3; margin-bottom: 8px;"></i>
                    <p style="font-size: 14px; font-weight: 500;">No activity yet.</p>
                </div>
            <?php endif; ?>

            <?php foreach($logs as $log): 
                $icon = 'info';
                $color = '#64748B';
                $bg = 'rgba(100,116,139,0.1)';
                
                if ($log['action'] === 'created') {
                    $icon = 'plus';
                    $color = '#4F46E5';
                    $bg = 'rgba(79,70,229,0.1)';
                } elseif ($log['action'] === 'updated') {
                    $icon = 'edit-2';
                    $color = '#F59E0B';
                    $bg = 'rgba(245,158,11,0.1)';
                } elseif ($log['action'] === 'completed') {
                    $icon = 'check-circle';
                    $color = '#10B981';
                    $bg = 'rgba(16,185,129,0.1)';
                } elseif ($log['action'] === 'deleted') {
                    $icon = 'trash-2';
                    $color = '#EF4444';
                    $bg = 'rgba(239,68,68,0.1)';
                }
            ?>
            <div class="d-flex gap-3 p-3" style="border: 1px solid var(--border-color); border-radius: 12px; align-items: flex-start;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: <?= $bg ?>; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i data-lucide="<?= $icon ?>" color="<?= $color ?>" width="16"></i>
                </div>
                <div>
                    <div style="font-size: 14px; font-weight: 600; color: var(--text-main); margin-bottom: 2px;">
                        <?= htmlspecialchars($log['note']) ?>
                    </div>
                    <div style="font-size: 12px; color: var(--text-muted);">
                        Task: <?= htmlspecialchars($log['task_title']) ?> • <?= date('M j, Y, g:i a', strtotime($log['logged_at'])) ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php require_once '../includes/dashboard_footer.php'; ?>
