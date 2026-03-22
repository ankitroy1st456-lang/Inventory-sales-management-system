<?php
session_start();
require_once "../../config/db.php";
if($_SESSION['user']['role'] !== 'admin'){ die("Access denied"); }

$users = $pdo->query("SELECT id, username, role FROM users")->fetchAll();
?>
<?php
$pageTitle = "Manage Users";
include __DIR__ . "/../common/head.php";
include __DIR__ . "/../common/nav.php";
?>

<div class="container py-3">
  <div class="app-page-header">
    <div>
      <h1 class="app-title mb-1">Manage Users</h1>
      <div class="text-muted">Admin-only user management.</div>
    </div>
    <div class="app-actions">
      <a class="btn btn-outline-light btn-sm" href="/inventory_system/modules/users/pending.php">
        <i class="bi bi-person-check me-1"></i>Pending Signups
      </a>
    </div>
  </div>

  <div class="app-card p-3 p-md-4">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th style="width:80px;">ID</th>
            <th>Username</th>
            <th style="width:140px;">Role</th>
            <th style="width:260px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($users as $u): ?>
            <tr>
              <td class="text-muted"><?= (int)$u['id'] ?></td>
              <td class="fw-semibold"><?= htmlspecialchars($u['username']) ?></td>
              <td>
                <?php if($u['role'] === 'admin'): ?>
                  <span class="badge text-bg-primary">admin</span>
                <?php else: ?>
                  <span class="badge text-bg-dark border" style="border-color: rgba(255,255,255,.12)!important;">staff</span>
                <?php endif; ?>
              </td>
              <td>
                <div class="d-flex flex-wrap gap-2">
                  <?php if($u['role'] === 'staff'): ?>
                    <a class="btn btn-sm btn-outline-light" href="promote.php?id=<?= (int)$u['id'] ?>">
                      <i class="bi bi-arrow-up-circle me-1"></i>Promote to Admin
                    </a>
                  <?php else: ?>
                    <span class="text-muted small d-inline-flex align-items-center"><i class="bi bi-shield-check me-1"></i>Admin</span>
                  <?php endif; ?>
                  <a class="btn btn-sm btn-outline-danger" href="delete.php?id=<?= (int)$u['id'] ?>" onclick="return confirm('Delete user?')">
                    <i class="bi bi-trash3 me-1"></i>Delete
                  </a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . "/../common/footer.php"; ?>
