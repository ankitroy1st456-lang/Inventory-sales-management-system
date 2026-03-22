<?php
session_start();
require_once "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user'] = $user;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid credentials";
    }
}
?>
<?php
$pageTitle = "Login";
include __DIR__ . "/../modules/common/head.php";
?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-7 col-lg-5">
      <div class="app-card p-4 p-md-5">
        <div class="mb-4">
          <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-shield-lock fs-3"></i>
            <h1 class="app-title mb-0">Welcome back</h1>
          </div>
          <div class="text-muted">Sign in to continue to your dashboard.</div>
        </div>

        <?php if (isset($error)): ?>
          <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" class="vstack gap-3">
          <div>
            <label class="form-label">Username</label>
            <input class="form-control" type="text" name="username" placeholder="Enter username" required>
          </div>
          <div>
            <label class="form-label">Password</label>
            <input class="form-control" type="password" name="password" placeholder="Enter password" required>
          </div>

          <button class="btn btn-primary w-100" type="submit">
            <i class="bi bi-box-arrow-in-right me-1"></i>Login
          </button>

          <div class="text-center text-muted small">
            Don’t have an account? <a class="link-light" href="signup.php">Create one</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . "/../modules/common/footer.php"; ?>
