<?php
session_start();
require_once "../../config/db.php";
if ($_SESSION['user']['role'] !== 'admin') {
    die("Access denied");
}
?>
<?php
$pageTitle = "Reports";
include __DIR__ . "/../common/head.php";
include __DIR__ . "/../common/nav.php";
?>

<div class="container py-3">
  <div class="app-page-header">
    <div>
      <h1 class="app-title mb-1">Reports</h1>
      <div class="text-muted">Admin analytics and insights.</div>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-12 col-md-4">
      <a class="text-decoration-none" href="sales.php">
        <div class="app-card p-3 h-100">
          <div class="d-flex align-items-center gap-2 mb-1">
            <i class="bi bi-graph-up"></i>
            <div class="fw-semibold">Sales Report</div>
          </div>
          <div class="text-muted small">Daily sales totals with chart/table view.</div>
        </div>
      </a>
    </div>
    <div class="col-12 col-md-4">
      <a class="text-decoration-none" href="purchases.php">
        <div class="app-card p-3 h-100">
          <div class="d-flex align-items-center gap-2 mb-1">
            <i class="bi bi-bar-chart"></i>
            <div class="fw-semibold">Purchases Report</div>
          </div>
          <div class="text-muted small">Purchases grouped by supplier.</div>
        </div>
      </a>
    </div>
    <div class="col-12 col-md-4">
      <a class="text-decoration-none" href="stock.php">
        <div class="app-card p-3 h-100">
          <div class="d-flex align-items-center gap-2 mb-1">
            <i class="bi bi-pie-chart"></i>
            <div class="fw-semibold">Stock Report</div>
          </div>
          <div class="text-muted small">Stock levels and low-stock visibility.</div>
        </div>
      </a>
    </div>
  </div>
</div>

<?php include __DIR__ . "/../common/footer.php"; ?>