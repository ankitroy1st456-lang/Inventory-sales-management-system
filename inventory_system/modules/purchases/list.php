<?php
session_start();
require_once "../../config/db.php";
if(!isset($_SESSION['user'])) { header("Location: ../../public/index.php"); exit; }

$purchases = $pdo->query("SELECT p.id, s.name AS supplier, p.date, p.total 
                          FROM purchases p 
                          JOIN suppliers s ON p.supplier_id=s.id")->fetchAll();
?>
<?php
$pageTitle = "Purchases";
include __DIR__ . "/../common/head.php";
include __DIR__ . "/../common/nav.php";
?>

<div class="container py-3">
  <div class="app-page-header">
    <div>
      <h1 class="app-title mb-1">Purchases</h1>
      <div class="text-muted">Track supplier purchases.</div>
    </div>
    <div class="app-actions">
      <a class="btn btn-primary btn-sm" href="add.php"><i class="bi bi-plus-lg me-1"></i>New Purchase</a>
    </div>
  </div>

  <div class="app-card p-3 p-md-4">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th style="width:80px;">ID</th>
            <th>Supplier</th>
            <th style="width:160px;">Date</th>
            <th style="width:140px;">Total</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($purchases as $p): ?>
            <tr>
              <td class="text-muted"><?= (int)$p['id'] ?></td>
              <td class="fw-semibold"><?= htmlspecialchars($p['supplier']) ?></td>
              <td class="text-muted"><?= htmlspecialchars($p['date']) ?></td>
              <td><?= htmlspecialchars($p['total']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . "/../common/footer.php"; ?>
