<?php
session_start();
require_once "../../config/db.php";
if(!isset($_SESSION['user'])) { header("Location: ../../public/index.php"); exit; }

$customers = $pdo->query("SELECT * FROM customers")->fetchAll();
?>
<?php
$pageTitle = "Customers";
include __DIR__ . "/../common/head.php";
include __DIR__ . "/../common/nav.php";
?>

<div class="container py-3">
  <div class="app-page-header">
    <div>
      <h1 class="app-title mb-1">Customers</h1>
      <div class="text-muted">Your customer directory.</div>
    </div>
    <div class="app-actions">
      <a class="btn btn-primary btn-sm" href="add.php"><i class="bi bi-plus-lg me-1"></i>Add Customer</a>
    </div>
  </div>

  <div class="app-card p-3 p-md-4">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th style="width:80px;">ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($customers as $c): ?>
            <tr>
              <td class="text-muted"><?= (int)$c['id'] ?></td>
              <td class="fw-semibold"><?= htmlspecialchars($c['name']) ?></td>
              <td class="text-muted"><?= htmlspecialchars($c['phone'] ?? '-') ?></td>
              <td class="text-muted"><?= htmlspecialchars($c['email'] ?? '-') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . "/../common/footer.php"; ?>
