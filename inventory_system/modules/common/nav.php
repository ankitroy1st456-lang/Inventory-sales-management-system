<?php
if (!isset($appBase)) {
  $appBase = "/inventory_system";
}
$isAdmin = isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? "") === "admin";
$userLabel = isset($_SESSION['user']['username']) ? (string)$_SESSION['user']['username'] : "";
?>
<nav class="app-navbar navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand app-brand d-flex align-items-center gap-2" href="<?= htmlspecialchars($appBase) ?>/public/dashboard.php">
      <span class="app-brand-mark"><i class="bi bi-grid-1x2-fill"></i></span>
      <span class="app-brand-text">Inventory</span>
    </a>
    <button class="navbar-toggler app-nav-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#appNav" aria-controls="appNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="appNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-lg-1">
        <li class="nav-item">
          <a class="nav-link app-nav-link" href="<?= htmlspecialchars($appBase) ?>/public/dashboard.php"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link app-nav-link" href="<?= htmlspecialchars($appBase) ?>/modules/products/list.php"><i class="bi bi-box-seam me-1"></i>Products</a>
        </li>
        <li class="nav-item">
          <a class="nav-link app-nav-link" href="<?= htmlspecialchars($appBase) ?>/modules/categories/list.php"><i class="bi bi-folder2 me-1"></i>Categories</a>
        </li>
        <li class="nav-item">
          <a class="nav-link app-nav-link" href="<?= htmlspecialchars($appBase) ?>/modules/suppliers/list.php"><i class="bi bi-truck me-1"></i>Suppliers</a>
        </li>
        <li class="nav-item">
          <a class="nav-link app-nav-link" href="<?= htmlspecialchars($appBase) ?>/modules/customers/list.php"><i class="bi bi-people me-1"></i>Customers</a>
        </li>
        <li class="nav-item">
          <a class="nav-link app-nav-link" href="<?= htmlspecialchars($appBase) ?>/modules/sales/list.php"><i class="bi bi-receipt me-1"></i>Sales</a>
        </li>
        <li class="nav-item">
          <a class="nav-link app-nav-link" href="<?= htmlspecialchars($appBase) ?>/modules/purchases/list.php"><i class="bi bi-bag-check me-1"></i>Purchases</a>
        </li>
        <?php if ($isAdmin): ?>
        <li class="nav-item dropdown">
          <a class="nav-link app-nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-shield-lock me-1"></i>Admin</a>
          <ul class="dropdown-menu app-dropdown">
            <li><a class="dropdown-item" href="<?= htmlspecialchars($appBase) ?>/modules/reports/index.php"><i class="bi bi-graph-up-arrow me-2"></i>Reports</a></li>
            <li><a class="dropdown-item" href="<?= htmlspecialchars($appBase) ?>/modules/users/list.php"><i class="bi bi-person-gear me-2"></i>Users</a></li>
            <li><a class="dropdown-item" href="<?= htmlspecialchars($appBase) ?>/modules/users/pending.php"><i class="bi bi-hourglass-split me-2"></i>Pending signups</a></li>
          </ul>
        </li>
        <?php endif; ?>
      </ul>
      <div class="d-flex align-items-center gap-2 flex-wrap justify-content-lg-end">
        <?php if ($userLabel !== ""): ?>
        <span class="app-user-pill small"><i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($userLabel) ?></span>
        <?php endif; ?>
        <a class="btn btn-sm app-btn-logout" href="<?= htmlspecialchars($appBase) ?>/public/logout.php"><i class="bi bi-box-arrow-right me-1"></i>Logout</a>
      </div>
    </div>
  </div>
</nav>
