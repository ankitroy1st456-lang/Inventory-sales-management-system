<?php
session_start();
require_once "../config/db.php";
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

// Quick counts
$totalProducts  = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalSuppliers = $pdo->query("SELECT COUNT(*) FROM suppliers")->fetchColumn();
$totalCustomers = $pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn();

// Totals
$totalSales     = $pdo->query("SELECT SUM(total) FROM sales")->fetchColumn();
$totalPurchases = $pdo->query("SELECT SUM(total) FROM purchases")->fetchColumn();

// Today’s activity
$todaySales     = $pdo->query("SELECT SUM(total) FROM sales WHERE DATE(date)=CURDATE()")->fetchColumn();
$todayPurchases = $pdo->query("SELECT SUM(total) FROM purchases WHERE DATE(date)=CURDATE()")->fetchColumn();

// Low stock
$lowStock = $pdo->query("SELECT COUNT(*) FROM products WHERE stock < 5")->fetchColumn();
?>
<?php
$pageTitle = "Dashboard";
include __DIR__ . "/../modules/common/head.php";
include __DIR__ . "/../modules/common/nav.php";
?>

<div class="container py-3">
  <div class="app-page-header">
    <div>
      <h1 class="app-title mb-1">Dashboard</h1>
      <div class="text-muted">Welcome, <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong></div>
    </div>
    <div class="app-actions">
      <a class="btn btn-outline-light btn-sm" href="/inventory_system/modules/products/list.php"><i class="bi bi-box me-1"></i>Products</a>
      <a class="btn btn-outline-light btn-sm" href="/inventory_system/modules/sales/list.php"><i class="bi bi-receipt me-1"></i>Sales</a>
      <a class="btn btn-outline-light btn-sm" href="/inventory_system/modules/purchases/list.php"><i class="bi bi-bag-check me-1"></i>Purchases</a>
    </div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-12 col-md-4">
      <div class="app-card p-3 h-100">
        <div class="text-muted small">Products</div>
        <div class="fs-2 fw-semibold"><?= (int)$totalProducts ?></div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="app-card p-3 h-100">
        <div class="text-muted small">Suppliers</div>
        <div class="fs-2 fw-semibold"><?= (int)$totalSuppliers ?></div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="app-card p-3 h-100">
        <div class="text-muted small">Customers</div>
        <div class="fs-2 fw-semibold"><?= (int)$totalCustomers ?></div>
      </div>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-12 col-lg-8">
      <div class="app-card p-3 p-md-4 h-100">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h2 class="app-title">Totals</h2>
          <span class="badge text-bg-dark border" style="border-color: rgba(255,255,255,.12)!important;">Overview</span>
        </div>
        <div class="row g-3">
          <div class="col-12 col-md-6">
            <div class="app-card p-3">
              <div class="text-muted small">Total sales value</div>
              <div class="fs-4 fw-semibold"><?= (float)($totalSales ?? 0) ?></div>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="app-card p-3">
              <div class="text-muted small">Total purchases value</div>
              <div class="fs-4 fw-semibold"><?= (float)($totalPurchases ?? 0) ?></div>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="app-card p-3">
              <div class="text-muted small">Today’s sales</div>
              <div class="fs-4 fw-semibold"><?= (float)($todaySales ?? 0) ?></div>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="app-card p-3">
              <div class="text-muted small">Today’s purchases</div>
              <div class="fs-4 fw-semibold"><?= (float)($todayPurchases ?? 0) ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="app-card p-3 p-md-4 h-100">
        <h2 class="app-title mb-2">Alerts</h2>
        <div class="text-muted mb-3">Quick attention items.</div>
        <div class="d-flex align-items-center justify-content-between app-card p-3">
          <div>
            <div class="fw-semibold">Low stock</div>
            <div class="text-muted small">Products with stock &lt; 5</div>
          </div>
          <span class="badge rounded-pill text-bg-danger"><?= (int)$lowStock ?></span>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . "/../modules/common/footer.php"; ?>