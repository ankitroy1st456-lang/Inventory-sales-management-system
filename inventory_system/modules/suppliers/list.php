<?php
session_start();
require_once "../../config/db.php";
if(!isset($_SESSION['user'])) { header("Location: ../../public/index.php"); exit; }

$suppliers = $pdo->query("SELECT * FROM suppliers")->fetchAll();
?>
<?php
$pageTitle = "Suppliers";
include __DIR__ . "/../common/head.php";
include __DIR__ . "/../common/nav.php";
?>

<div class="container py-3">
  <div class="app-page-header">
    <div>
      <h1 class="app-title mb-1">Suppliers</h1>
      <div class="text-muted">Manage supplier contacts.</div>
    </div>
    <div class="app-actions">
      <a class="btn btn-primary btn-sm" href="add.php"><i class="bi bi-plus-lg me-1"></i>Add Supplier</a>
    </div>
  </div>

  <div class="app-card p-3 p-md-4">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th style="width:80px;">ID</th>
            <th>Name</th>
            <th>Contact</th>
            <th>Phone</th>
            <th>Email</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($suppliers as $s): ?>
            <tr>
              <td class="text-muted"><?= (int)$s['id'] ?></td>
              <td class="fw-semibold"><?= htmlspecialchars($s['name']) ?></td>
              <td class="text-muted"><?= htmlspecialchars($s['contact'] ?? '-') ?></td>
              <td class="text-muted"><?= htmlspecialchars($s['phone'] ?? '-') ?></td>
              <td class="text-muted"><?= htmlspecialchars($s['email'] ?? '-') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . "/../common/footer.php"; ?>
