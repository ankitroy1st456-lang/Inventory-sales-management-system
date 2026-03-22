<?php
session_start();
require_once "../../config/db.php";
if($_SESSION['user']['role'] !== 'admin'){ die("Access denied"); }

$pending = $pdo->query("SELECT * FROM pending_users")->fetchAll();

if(isset($_GET['approve'])){
    $id = $_GET['approve'];
    $stmt = $pdo->prepare("SELECT * FROM pending_users WHERE id=?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    // move to users table
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?,?,?)");
    $stmt->execute([$user['username'], $user['password'], 'staff']);

    // delete from pending
    $pdo->prepare("DELETE FROM pending_users WHERE id=?")->execute([$id]);

    header("Location: pending.php");
    exit;
}

if(isset($_GET['reject'])){
    $pdo->prepare("DELETE FROM pending_users WHERE id=?")->execute([$_GET['reject']]);
    header("Location: pending.php");
    exit;
}
?>
<?php
$pageTitle = "Pending Signups";
include __DIR__ . "/../common/head.php";
include __DIR__ . "/../common/nav.php";
?>

<div class="container py-3">
  <div class="app-page-header">
    <div>
      <h1 class="app-title mb-1">Pending Signups</h1>
      <div class="text-muted">Approve or reject new signup requests.</div>
    </div>
    <div class="app-actions">
      <a class="btn btn-outline-light btn-sm" href="list.php"><i class="bi bi-people me-1"></i>Manage Users</a>
    </div>
  </div>

  <div class="app-card p-3 p-md-4">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th style="width:80px;">ID</th>
            <th>Username</th>
            <th style="width:220px;">Requested At</th>
            <th style="width:220px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($pending as $p): ?>
            <tr>
              <td class="text-muted"><?= (int)$p['id'] ?></td>
              <td class="fw-semibold"><?= htmlspecialchars($p['username']) ?></td>
              <td class="text-muted"><?= htmlspecialchars($p['requested_at'] ?? '-') ?></td>
              <td>
                <div class="d-flex gap-2">
                  <a class="btn btn-sm btn-success" href="pending.php?approve=<?= (int)$p['id'] ?>">
                    <i class="bi bi-check2 me-1"></i>Approve
                  </a>
                  <a class="btn btn-sm btn-outline-danger" href="pending.php?reject=<?= (int)$p['id'] ?>" onclick="return confirm('Reject signup?')">
                    <i class="bi bi-x-lg me-1"></i>Reject
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
