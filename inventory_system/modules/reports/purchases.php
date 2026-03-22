<?php
session_start();
require_once "../../config/db.php";
if($_SESSION['user']['role'] !== 'admin'){ die("Access denied"); }

// Query: purchases grouped by supplier
$stmt = $pdo->query("SELECT s.name AS supplier, SUM(p.total) as total_purchase 
                     FROM purchases p 
                     JOIN suppliers s ON p.supplier_id=s.id 
                     GROUP BY s.name ORDER BY s.name ASC");
$data = $stmt->fetchAll();

// Prepare arrays
$labels = [];
$values = [];
foreach($data as $row){
    $labels[] = $row['supplier'];
    $values[] = $row['total_purchase'];
}
?>
<?php
$pageTitle = "Purchases Report";
include __DIR__ . "/../common/head.php";
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php include __DIR__ . "/../common/nav.php"; ?>

<div class="container py-3">
  <div class="app-page-header">
    <div>
      <h1 class="app-title mb-1">Purchases Report</h1>
      <div class="text-muted">Total purchases by supplier.</div>
    </div>
    <div class="app-actions">
      <a class="btn btn-outline-light btn-sm" href="index.php"><i class="bi bi-arrow-left me-1"></i>Reports</a>
      <button type="button" class="btn btn-outline-light btn-sm" onclick="showTable()"><i class="bi bi-table me-1"></i>Table</button>
      <button type="button" class="btn btn-outline-light btn-sm" onclick="showChart()"><i class="bi bi-bar-chart me-1"></i>Chart</button>
    </div>
  </div>

  <div id="tableView" class="app-card p-3 p-md-4">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr><th>Supplier</th><th>Total Purchases</th></tr>
        </thead>
        <tbody>
          <?php foreach($data as $row): ?>
            <tr>
              <td class="fw-semibold"><?= htmlspecialchars($row['supplier']) ?></td>
              <td><?= htmlspecialchars($row['total_purchase']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div id="chartView" class="app-card p-3 p-md-4" style="display:none;">
    <canvas id="purchaseChart" height="110"></canvas>
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

const ctx = document.getElementById('purchaseChart').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: <?= json_encode($labels) ?>,
    datasets: [{
      label: 'Total Purchases',
      data: <?= json_encode($values) ?>,
      backgroundColor: 'rgba(245,158,11,.55)',
      borderColor: 'rgba(245,158,11,.9)',
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    plugins: { title: { display: false } },
    scales: {
      x: { ticks: { color: 'rgba(255,255,255,.70)' }, grid: { color: 'rgba(255,255,255,.06)' } },
      y: { ticks: { color: 'rgba(255,255,255,.70)' }, grid: { color: 'rgba(255,255,255,.06)' } }
    }
  }
});
</script>

<?php include __DIR__ . "/../common/footer.php"; ?>
