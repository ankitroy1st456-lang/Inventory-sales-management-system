<?php
session_start();
require_once "../../config/db.php";
if(!isset($_SESSION['user'])) { header("Location: ../../public/index.php"); exit; }

$stmt = $pdo->query("SELECT p.*, c.name AS category FROM products p 
                     LEFT JOIN categories c ON p.category_id=c.id");
$products = $stmt->fetchAll();
?>
<?php
$pageTitle = "Products";
include __DIR__ . "/../common/head.php";
include __DIR__ . "/../common/nav.php";
?>

<div class="container py-3">
  <div class="app-page-header">
    <div>
      <h1 class="app-title mb-1">Products</h1>
      <div class="text-muted">Manage your product catalog.</div>
    </div>
    <div class="app-actions">
      <a class="btn btn-primary btn-sm" href="add.php"><i class="bi bi-plus-lg me-1"></i>Add Product</a>
    </div>
  </div>

  <div class="app-card p-3 p-md-4">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th style="width:70px;">ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Cost</th>
            <th>Sell</th>
            <th>Stock</th>
            <th style="width:160px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($products as $p): ?>
            <tr>
              <td class="text-muted"><?= (int)$p['id'] ?></td>
              <td class="fw-semibold"><?= htmlspecialchars($p['name']) ?></td>
              <td class="text-muted"><?= htmlspecialchars($p['category'] ?? '-') ?></td>
              <td><?= htmlspecialchars($p['cost_price']) ?></td>
              <td><?= htmlspecialchars($p['sell_price']) ?></td>
              <td>
                <?php if ((int)$p['stock'] < 5): ?>
                  <span class="badge text-bg-danger"><?= (int)$p['stock'] ?></span>
                <?php else: ?>
                  <span class="badge text-bg-success"><?= (int)$p['stock'] ?></span>
                <?php endif; ?>
              </td>
              <td>
                <div class="d-flex gap-2">
                  <a class="btn btn-sm btn-outline-light" href="edit.php?id=<?= (int)$p['id'] ?>">
                    <i class="bi bi-pencil me-1"></i>Edit
                  </a>
                  <a class="btn btn-sm btn-outline-danger" href="delete.php?id=<?= (int)$p['id'] ?>" onclick="return confirm('Delete?')">
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
