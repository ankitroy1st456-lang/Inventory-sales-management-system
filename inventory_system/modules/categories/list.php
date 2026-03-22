<?php
session_start();
require_once "../../config/db.php";
if(!isset($_SESSION['user'])) { header("Location: ../../public/index.php"); exit; }

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<?php
$pageTitle = "Categories";
include __DIR__ . "/../common/head.php";
include __DIR__ . "/../common/nav.php";
?>

<div class="container py-3">
  <div class="app-page-header">
    <div>
      <h1 class="app-title mb-1">Categories</h1>
      <div class="text-muted">Organize products into categories.</div>
    </div>
    <div class="app-actions">
      <a class="btn btn-primary btn-sm" href="add.php"><i class="bi bi-plus-lg me-1"></i>Add Category</a>
    </div>
  </div>

  <div class="app-card p-3 p-md-4">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th style="width:80px;">ID</th>
            <th>Name</th>
            <th style="width:180px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($categories as $c): ?>
            <tr>
              <td class="text-muted"><?= (int)$c['id'] ?></td>
              <td class="fw-semibold"><?= htmlspecialchars($c['name']) ?></td>
              <td>
                <div class="d-flex gap-2">
                  <a class="btn btn-sm btn-outline-light" href="edit.php?id=<?= (int)$c['id'] ?>">
                    <i class="bi bi-pencil me-1"></i>Edit
                  </a>
                  <a class="btn btn-sm btn-outline-danger" href="delete.php?id=<?= (int)$c['id'] ?>" onclick="return confirm('Delete?')">
                    <i class="bi bi-trash3 me-1"></i>Delete
                  </a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . "/../common/footer.php"; ?>
