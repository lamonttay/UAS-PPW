<?php
// pages/edit_task.php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireLogin();

$user = getCurrentUser($pdo);

if (!isset($_GET['id'])) {
    header("Location: tasks.php");
    exit;
}
$taskId = (int)$_GET['id'];

// Get existing task
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
$stmt->execute([$taskId, $user['id']]);
$task = $stmt->fetch();

if (!$task) {
    header("Location: tasks.php");
    exit;
}

$stmtCats = $pdo->prepare("SELECT id, name FROM categories WHERE user_id = ?");
$stmtCats->execute([$user['id']]);
$categories = $stmtCats->fetchAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $priority = $_POST['priority'];
    $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
    
    if (empty($title)) {
        $error = "Task title is required.";
    } elseif (!$error) {
        $stmtUpdate = $pdo->prepare("UPDATE tasks SET category_id = ?, title = ?, description = ?, priority = ?, due_date = ? WHERE id = ? AND user_id = ?");
        if ($stmtUpdate->execute([$category_id, $title, $description, $priority, $due_date, $taskId, $user['id']])) {
            header("Location: tasks.php");
            exit;
        } else {
            $error = "Failed to update task.";
        }
    }
}

require_once '../includes/dashboard_header.php';
?>
<div style="max-width: 600px; margin: 0 auto;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 style="font-size: 24px; font-weight: 800;">Edit Task</h1>
        <a href="tasks.php" class="btn btn-outline" style="padding: 8px 14px; font-size: 13px;"><i data-lucide="arrow-left" width="14"></i> Back</a>
    </div>

    <?php if($error): ?>
    <div style="background: rgba(239,68,68,0.1); color: var(--danger); padding: 12px; border-radius: 8px; font-size: 13px; font-weight: 600; margin-bottom: 20px;">
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <div class="card" style="padding: 24px;">
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Task Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($task['title']) ?>">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($task['description']) ?></textarea>
            </div>
            
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Priority</label>
                    <select name="priority" class="form-control">
                        <option value="low" <?= $task['priority'] == 'low' ? 'selected' : '' ?>>Low</option>
                        <option value="medium" <?= $task['priority'] == 'medium' ? 'selected' : '' ?>>Medium</option>
                        <option value="high" <?= $task['priority'] == 'high' ? 'selected' : '' ?>>High</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-control">
                        <option value="">No Category</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $task['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="row g-3 mb-3">
                <div class="col-md-12">
                    <label class="form-label">Due Date</label>
                    <input type="date" name="due_date" class="form-control" value="<?= htmlspecialchars($task['due_date']) ?>">
                </div>
            </div>
            
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="tasks.php" class="btn" style="background: #F1F5F9; color: #64748B;">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const title = document.querySelector('input[name="title"]').value.trim();
            const priority = document.querySelector('select[name="priority"]').value;
            
            if (title.length < 3) {
                e.preventDefault();
                alert('Task title must be at least 3 characters long.');
                return;
            }
            
            if (!['low', 'medium', 'high'].includes(priority)) {
                e.preventDefault();
                alert('Invalid priority selected.');
                return;
            }
            
            if (!confirm('Are you sure you want to save these changes?')) {
                e.preventDefault();
            }
        });
    });
    </script>
</div>
<?php require_once '../includes/dashboard_footer.php'; ?>
