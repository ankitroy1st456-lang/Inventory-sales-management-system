<?php
session_start();
require_once "../config/db.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if an admin already exists
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'");
    $adminExists = $stmt->fetchColumn();

    if($adminExists == 0){
        // First signup → create admin directly
        echo "Create Admin account.";
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?,?,?)");
        $stmt->execute([$username, $password, 'admin']);
        
        header("Location: index.php");

    } else {
        // Later signups → go to pending_users
        $stmt = $pdo->prepare("INSERT INTO pending_users (username, password) VALUES (?,?)");
        $stmt->execute([$username, $password]);
        echo "Signup request submitted. Waiting for admin approval.";
    }
}
?>
<?php
$pageTitle = "Signup";
include __DIR__ . "/../modules/common/head.php";
?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-7 col-lg-5">
      <div class="app-card p-4 p-md-5">
        <div class="mb-4">
          <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-person-plus fs-3"></i>
            <h1 class="app-title mb-0">Create account</h1>
          </div>
          <div class="text-muted">Request access to the system.</div>
        </div>

        <form method="post" class="vstack gap-3">
          <div>
            <label class="form-label">Username</label>
            <input class="form-control" type="text" name="username" placeholder="Choose a username" required>
          </div>
          <div>
            <label class="form-label">Password</label>
            <input class="form-control" type="password" name="password" placeholder="Create a password" required>
          </div>
          <button class="btn btn-primary w-100" type="submit">
            <i class="bi bi-check2-circle me-1"></i>Signup
          </button>
          <div class="text-center text-muted small">
            <a class="link-light" href="index.php">Back to login</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . "/../modules/common/footer.php"; ?>
