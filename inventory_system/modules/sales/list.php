<?php
session_start();
require_once "../../config/db.php";
if (!isset($_SESSION['user'])) {
  header("Location: ../../public/index.php");
  exit;
}
$sales = $pdo->query("
  SELECT s.id, s.customer_id, c.name AS customer, s.date, s.total,
         si.qty, p.name AS product_name, p.sell_price,
         COALESCE((SELECT SUM(amount) FROM payments WHERE sale_id = s.id), 0) AS paid
        FROM sales s
        JOIN customers c ON s.customer_id = c.id
        JOIN sale_items si ON si.sale_id = s.id
        JOIN products p ON si.product_id = p.id
")->fetchAll();



?>
<?php
$pageTitle = "Sales";
include __DIR__ . "/../common/head.php";
include __DIR__ . "/../common/nav.php";
?>

<div class="container py-3">
  <div class="app-page-header">
    <div>
      <h1 class="app-title mb-1">Sales</h1>
      <div class="text-muted">Track customer sales.</div>
    </div>
    <div class="app-actions">
      <a class="btn btn-primary btn-sm" href="add.php"><i class="bi bi-plus-lg me-1"></i>New Sale</a>
    </div>
  </div>

  <div class="app-card p-3 p-md-4">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th style="width:80px;">ID</th>
            <th>Customer</th>
            <th>Product</th>
            <th>Qty</th>
            <th style="width:160px;">Date</th>
            <th style="width:140px;">Total</th>
            <th style="width:140px;">status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($sales as $s): ?>
            <tr>
              <td class="text-muted"><?= (int)$s['id'] ?></td>
              <td class="fw-semibold">
                <a href="bill.php?customer_id=<?= (int)$s['customer_id'] ?>">
                  <?= htmlspecialchars($s['customer']) ?>
                </a>
              </td>
              <td><?= htmlspecialchars($s['product_name']) ?></td>
              <td><?= htmlspecialchars($s['qty']) ?></td>
              <td class="text-muted"><?= htmlspecialchars($s['date']) ?></td>
              <td><?= htmlspecialchars($s['total']) ?></td>
              <td>
                <?php
                $due = $s['total'] - $s['paid'];
                if ($due <= 0) {
                  echo '<span style="color: green; font-weight: bold;">Paid</span>';
                } else {
                  echo '<span style="color: red; font-weight: bold;">Due</span>';
                }
                ?>
              </td>


            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . "/../common/footer.php"; ?>