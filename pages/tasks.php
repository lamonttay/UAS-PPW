<?php
// pages/tasks.php
require_once '../includes/dashboard_header.php';

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$date_filter = isset($_GET['date']) ? trim($_GET['date']) : '';

$query = "SELECT t.*, c.name as category_name, c.color as category_color 
          FROM tasks t 
          LEFT JOIN categories c ON t.category_id = c.id 
          WHERE t.user_id = :user_id";

$params = [':user_id' => $user['id']];

if ($filter === 'active') {
    $query .= " AND t.status = 'pending'";
} elseif ($filter === 'done') {
    $query .= " AND t.status = 'completed'";
}

if ($search !== '') {
    $query .= " AND t.title LIKE :search";
    $params[':search'] = "%$search%";
}

if ($date_filter !== '') {
    $query .= " AND DATE(t.due_date) = :date_filter";
    $params[':date_filter'] = $date_filter;
}

$query .= " ORDER BY (t.status = 'completed') ASC, t.due_date ASC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$tasks = $stmt->fetchAll();

$pending = 0;
$completed = 0;
foreach($tasks as $t) {
    if($t['status'] === 'completed') $completed++; else $pending++;
}

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 5;
$total_items = count($tasks);
$total_pages = ceil($total_items / $limit);
$paginated_tasks = array_slice($tasks, ($page - 1) * $limit, $limit);
?>
<div style="max-width: 800px; margin: 0 auto;">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 style="font-size: 28px; font-weight: 900;">Tasks</h1>
            <p class="text-muted" style="font-size: 13px; margin-top: 4px;">
                <?= $pending ?> pending · <?= $completed ?> completed
                <?= $date_filter ? ' · <span style="color: var(--primary); font-weight: 600;">Date: ' . date('M j, Y', strtotime($date_filter)) . '</span> <a href="tasks.php" style="color: var(--danger); margin-left: 4px;">Clear</a>' : '' ?>
            </p>
        </div>
        <a href="add_task.php" class="btn btn-primary" style="padding: 10px 18px; border-radius: 6px; font-size: 14px; box-shadow: 0 4px 14px rgba(0,102,255,0.3);">
            <i data-lucide="plus" width="16"></i> <span class="d-none d-sm-inline">Add Task</span>
        </a>
    </div>

    <!-- Controls -->
    <div class="d-flex flex-wrap gap-2 mb-4">
        <form method="GET" action="" class="d-flex align-items-center gap-2" style="padding: 9px 14px; border-radius: 6px; background: var(--card-bg); border: 1px solid var(--border-color); flex: 1; min-width: 180px; max-width: 280px;">
            <i data-lucide="search" width="14" style="color: var(--text-muted);"></i>
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search tasks..." style="background: transparent; border: none; outline: none; font-size: 13px; color: var(--text-main); font-family: Inter; flex: 1; width: 100%;">
            <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
        </form>
        <div style="display: flex; border-radius: 6px; overflow: hidden; border: 1px solid var(--border-color); background: var(--card-bg);">
            <?php foreach(['all', 'active', 'done'] as $f): 
                $isActive = $filter === $f;
                $bg = $isActive ? 'var(--primary-gradient)' : 'transparent';
                $color = $isActive ? 'white' : 'var(--text-muted)';
            ?>
                <a href="?filter=<?= $f ?><?= $search ? '&search='.urlencode($search) : '' ?>" style="padding: 9px 18px; border: none; font-size: 13px; font-weight: 600; text-transform: capitalize; background: <?= $bg ?>; color: <?= $color ?>; transition: all 0.2s;">
                    <?= $f ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Tasks List -->
    <div class="d-flex flex-column gap-2">
        <?php if(empty($paginated_tasks)): ?>
            <div class="text-center" style="padding: 60px 0; color: #94A3B8;">
                <i data-lucide="check-square" width="44" style="opacity: 0.3; margin-bottom: 12px;"></i>
                <p style="font-weight: 600; font-size: 15px;">No tasks found</p>
                <p style="font-size: 13px; margin-top: 4px;">Try adjusting your filter or add a new task</p>
            </div>
        <?php endif; ?>
        
        <?php foreach($paginated_tasks as $task): 
            $is_done = $task['status'] === 'completed';
            $priorityClass = 'badge-' . $task['priority'];
            $priorityLabel = ucfirst($task['priority']);
            if($task['priority'] === 'medium') $priorityLabel = 'Med';
        ?>
        <div class="card d-flex flex-row align-items-center gap-3 task-item <?= $is_done ? 'done' : '' ?>" style="padding: 14px 18px; transition: all 0.15s; border-radius: 6px; background: var(--card-bg); border: 1px solid var(--border-color);">
            <button class="task-checkbox <?= $is_done ? 'done' : '' ?>" data-id="<?= $task['id'] ?>" style="flex-shrink: 0;">
                <?php if($is_done): ?><i data-lucide="check" style="width: 14px; height: 14px;"></i><?php endif; ?>
            </button>

            <div style="flex: 1; min-width: 0;">
                <div class="task-text text-truncate" style="font-size: 14px; font-weight: 500; text-decoration: <?= $is_done ? 'line-through' : 'none' ?>; color: <?= $is_done ? 'var(--text-muted)' : 'var(--text-main)' ?>;">
                    <a href="edit_task.php?id=<?= $task['id'] ?>" style="color: inherit; text-decoration: inherit;"><?= htmlspecialchars($task['title']) ?></a>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2" style="margin-top: 4px;">
                    <i data-lucide="clock" width="11" style="color: #94A3B8;"></i>
                    <span style="font-size: 12px; color: #94A3B8;">
                        <?= $task['due_date'] ? date('M j', strtotime($task['due_date'])) : 'No due date' ?>
                    </span>
                    <?php if($task['category_name']): ?>
                    <span style="font-size: 11px; font-weight: 600; color: <?= $task['category_color'] ?>; background: <?= $task['category_color'] ?>20; padding: 1px 7px; border-radius: 6px;">
                        <?= htmlspecialchars($task['category_name']) ?>
                    </span>
                    <?php endif; ?>
                </div>
            </div>

            <span class="badge <?= $priorityClass ?> flex-shrink-0"><?= $priorityLabel ?></span>

            <a href="delete_task.php?id=<?= $task['id'] ?>" class="delete-task-btn text-danger ms-2" style="opacity: 0.7; transition: opacity 0.15s;">
                <i data-lucide="trash-2" width="16"></i>
            </a>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if($total_pages > 1): ?>
    <div class="d-flex justify-content-center gap-2" style="margin-top: 24px;">
        <?php
        $base_url = "?filter=" . urlencode($filter) . "&search=" . urlencode($search) . "&date=" . urlencode($date_filter);
        if($page > 1): ?>
            <a href="<?= $base_url ?>&page=<?= $page - 1 ?>" class="btn btn-outline" style="padding: 6px 12px; font-size: 13px;"><i data-lucide="chevron-left" width="16"></i> Prev</a>
        <?php endif; ?>
        
        <div style="display: flex; gap: 4px;">
            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <a href="<?= $base_url ?>&page=<?= $i ?>" style="width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 600; text-decoration: none; <?= $i === $page ? 'background: var(--primary-gradient); color: white; border: 1px solid transparent;' : 'background: var(--card-bg); border: 1px solid var(--border-color); color: var(--text-muted);' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>

        <?php if($page < $total_pages): ?>
            <a href="<?= $base_url ?>&page=<?= $page + 1 ?>" class="btn btn-outline" style="padding: 6px 12px; font-size: 13px;">Next <i data-lucide="chevron-right" width="16"></i></a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<?php require_once '../includes/dashboard_footer.php'; ?>
