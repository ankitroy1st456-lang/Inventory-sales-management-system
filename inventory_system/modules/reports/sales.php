<?php
session_start();
require_once "../../config/db.php";
if($_SESSION['user']['role'] !== 'admin'){ die("Access denied"); }

$from = $_GET['from'] ?? null;
$to   = $_GET['to'] ?? null;

$query = "SELECT DATE(date) as sale_date, SUM(total) as daily_total 
          FROM sales";
$params = [];

if($from && $to){
    $query .= " WHERE date BETWEEN ? AND ?";
    $params = [$from, $to];
}
$query .= " GROUP BY DATE(date) ORDER BY sale_date ASC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$data = $stmt->fetchAll();

$labels = [];
$totals = [];
foreach($data as $row){
    $labels[] = $row['sale_date'];
    $totals[] = $row['daily_total'];
}
?>
<?php
$pageTitle = "Sales Report";
include __DIR__ . "/../common/head.php";
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php include __DIR__ . "/../common/nav.php"; ?>

<div class="container py-3">
  <div class="app-page-header">
    <div>
      <h1 class="app-title mb-1">Sales Report</h1>
      <div class="text-muted">View totals by day, filterable by date.</div>
    </div>
    <div class="app-actions">
      <a class="btn btn-outline-light btn-sm" href="index.php"><i class="bi bi-arrow-left me-1"></i>Reports</a>
      <button type="button" class="btn btn-outline-light btn-sm" onclick="showTable()"><i class="bi bi-table me-1"></i>Table</button>
      <button type="button" class="btn btn-outline-light btn-sm" onclick="showChart()"><i class="bi bi-graph-up me-1"></i>Chart</button>
    </div>
  </div>

  <div class="app-card p-3 p-md-4 mb-3">
    <form method="get" class="row g-3 align-items-end">
      <div class="col-12 col-md-4">
        <label class="form-label">From</label>
        <input class="form-control" type="date" name="from" value="<?= htmlspecialchars($from ?? '') ?>">
      </div>
      <div class="col-12 col-md-4">
        <label class="form-label">To</label>
        <input class="form-control" type="date" name="to" value="<?= htmlspecialchars($to ?? '') ?>">
      </div>
      <div class="col-12 col-md-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-funnel me-1"></i>Filter</button>
        <a class="btn btn-outline-light" href="sales.php">Reset</a>
      </div>
    </form>
  </div>

  <div id="tableView" class="app-card p-3 p-md-4">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr><th>Date</th><th>Total Sales</th></tr>
        </thead>
        <tbody>
          <?php foreach($data as $row): ?>
            <tr>
              <td class="text-muted"><?= htmlspecialchars($row['sale_date']) ?></td>
              <td class="fw-semibold"><?= htmlspecialchars($row['daily_total']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div id="chartView" class="app-card p-3 p-md-4" style="display:none;">
    <canvas id="salesChart" height="110"></canvas>
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

const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
  type: 'line',
  data: {
    labels: <?= json_encode($labels) ?>,
    datasets: [{
      label: 'Daily Sales',
      data: <?= json_encode($totals) ?>,
      borderColor: '#60a5fa',
      backgroundColor: 'rgba(96,165,250,.18)',
      fill: true,
      tension: .35,
      pointRadius: 2
    }]
  },
  options: {
    responsive: true,
    plugins: {
      title: { display: false }
    },
    scales: {
      x: { ticks: { color: 'rgba(255,255,255,.70)' }, grid: { color: 'rgba(255,255,255,.06)' } },
      y: { ticks: { color: 'rgba(255,255,255,.70)' }, grid: { color: 'rgba(255,255,255,.06)' } }
    }
  }
});
</script>

<?php include __DIR__ . "/../common/footer.php"; ?>
