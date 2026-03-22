<?php
session_start();
require_once "../../config/db.php";
if($_SESSION['user']['role'] !== 'admin'){ die("Access denied"); }

// Query: product stock levels
$stmt = $pdo->query("SELECT name, stock FROM products ORDER BY name ASC");
$data = $stmt->fetchAll();

// Prepare arrays
$labels = [];
$values = [];
foreach($data as $row){
    $labels[] = $row['name'];
    $values[] = $row['stock'];
}
?>
<?php
$pageTitle = "Stock Report";
include __DIR__ . "/../common/head.php";
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php include __DIR__ . "/../common/nav.php"; ?>

<div class="container py-3">
  <div class="app-page-header">
    <div>
      <h1 class="app-title mb-1">Stock Report</h1>
      <div class="text-muted">Current stock levels by product.</div>
    </div>
    <div class="app-actions">
      <a class="btn btn-outline-light btn-sm" href="index.php"><i class="bi bi-arrow-left me-1"></i>Reports</a>
      <button type="button" class="btn btn-outline-light btn-sm" onclick="showTable()"><i class="bi bi-table me-1"></i>Table</button>
      <button type="button" class="btn btn-outline-light btn-sm" onclick="showChart()"><i class="bi bi-pie-chart me-1"></i>Chart</button>
    </div>
  </div>

  <div id="tableView" class="app-card p-3 p-md-4">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr><th>Product</th><th style="width:140px;">Stock</th></tr>
        </thead>
        <tbody>
          <?php foreach($data as $row): ?>
            <tr>
              <td class="fw-semibold"><?= htmlspecialchars($row['name']) ?></td>
              <td>
                <?php if ((int)$row['stock'] < 5): ?>
                  <span class="badge text-bg-danger"><?= (int)$row['stock'] ?></span>
                <?php else: ?>
                  <span class="badge text-bg-success"><?= (int)$row['stock'] ?></span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div id="chartView" class="app-card p-3 p-md-4" style="display:none;">
    <canvas id="stockChart" height="130"></canvas>
  </div>
</div>

<script>
function showTable(){
  document.getElementById('tableView').style.display = 'block';
  document.getElementById('chartView').style.display = 'none';
}
function showChart(){
  document.getElementById('tableView').style.display = 'none';
  document.getElementById('chartView').style.display = 'block';
}

const ctx = document.getElementById('stockChart').getContext('2d');
new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: <?= json_encode($labels) ?>,
    datasets: [{
      label: 'Stock Levels',
      data: <?= json_encode($values) ?>,
      backgroundColor: [
        'rgba(239, 68, 68, .55)',
        'rgba(59, 130, 246, .55)',
        'rgba(34, 197, 94, .55)',
        'rgba(168, 85, 247, .55)',
        'rgba(245, 158, 11, .55)',
        'rgba(14, 165, 233, .55)'
      ],
      borderColor: 'rgba(255,255,255,.18)',
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    plugins: { title: { display: false }, legend: { labels: { color: 'rgba(255,255,255,.75)' } } }
  }
});
</script>

<?php include __DIR__ . "/../common/footer.php"; ?>
