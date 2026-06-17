<?php
// pages/categories.php
require_once '../includes/dashboard_header.php';

$error = '';
$success = '';

// Handle Create Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name']);
    $color = $_POST['color'];
    
    if (empty($name)) {
        $error = "Category name is required.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO categories (user_id, name, color) VALUES (?, ?, ?)");
        if ($stmt->execute([$user['id'], $name, $color])) {
            $success = "Category created successfully.";
        } else {
            $error = "Failed to create category.";
        }
    }
}

// Handle Delete Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = (int)$_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ? AND user_id = ?");
    if ($stmt->execute([$id, $user['id']])) {
        $success = "Category deleted successfully.";
    } else {
        $error = "Failed to delete category.";
    }
}

// Query 1 Tabel: Select all categories for this user
$stmt = $pdo->prepare("SELECT * FROM categories WHERE user_id = ? ORDER BY name");
$stmt->execute([$user['id']]);
$categories = $stmt->fetchAll();
?>
<div style="max-width: 800px; margin: 0 auto;">
    <div class="mb-4">
        <h1 style="font-size: 28px; font-weight: 900;">Categories</h1>
        <p class="text-muted" style="font-size: 13px; margin-top: 4px;">Organize your tasks efficiently</p>
    </div>

    <?php if($error): ?>
    <div style="background: rgba(239,68,68,0.1); color: var(--danger); padding: 12px; border-radius: 8px; font-size: 13px; font-weight: 600; margin-bottom: 20px;">
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>
    
    <?php if($success): ?>
    <div style="background: rgba(16,185,129,0.1); color: var(--success); padding: 12px; border-radius: 8px; font-size: 13px; font-weight: 600; margin-bottom: 20px;">
        <?= htmlspecialchars($success) ?>
    </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Add Category Form -->
        <div class="col-md-5">
            <div class="card" style="padding: 24px;">
                <h2 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">Add Category</h2>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Work, Home">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Color</label>
                        <input type="color" name="color" class="form-control" value="#4F46E5" style="height: 40px; padding: 4px;">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Create Category</button>
                </form>
            </div>
        </div>

        <!-- Category List -->
        <div class="col-md-7">
            <div class="card" style="padding: 24px;">
                <h2 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">Your Categories</h2>
                
                <div class="d-flex flex-column gap-2">
                    <?php if(empty($categories)): ?>
                        <div class="text-center p-4 text-muted">No categories found.</div>
                    <?php endif; ?>
                    
                    <?php foreach($categories as $cat): ?>
                        <div class="d-flex align-items-center justify-content-between p-3" style="border: 1px solid var(--border-color); border-radius: 12px;">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width: 16px; height: 16px; border-radius: 4px; background: <?= htmlspecialchars($cat['color']) ?>;"></div>
                                <span style="font-size: 14px; font-weight: 600;"><?= htmlspecialchars($cat['name']) ?></span>
                            </div>
                            <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this category? Tasks with this category will have it removed.');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                <button type="submit" style="background: transparent; border: none; cursor: pointer; color: var(--danger); opacity: 0.7; transition: opacity 0.2s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.7">
                                    <i data-lucide="trash-2" width="16"></i>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once '../includes/dashboard_footer.php'; ?>
