<?php
session_start();
require_once "../../config/db.php";
if(!isset($_SESSION['user'])) { header("Location: ../../public/index.php"); exit; }

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id=?");
$stmt->execute([$id]);
$category = $stmt->fetch();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $stmt = $pdo->prepare("UPDATE categories SET name=? WHERE id=?");
    $stmt->execute([$_POST['name'], $id]);
    header("Location: list.php");
    exit;
}
?>
<?php
$pageTitle = "Edit Category";
include __DIR__ . "/../common/head.php";
include __DIR__ . "/../common/nav.php";
?>

<div class="container py-3">
  <div class="app-page-header">
    <div>
      <h1 class="app-title mb-1">Edit Category</h1>
      <div class="text-muted">Update category name.</div>
    </div>
    <div class="app-actions">
      <a class="btn btn-outline-light btn-sm" href="list.php"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
  </div>

  <div class="app-card p-3 p-md-4">
    <form method="post" class="row g-3">
      <div class="col-12">
        <label class="form-label">Name</label>
        <input class="form-control" type="text" name="name" value="<?= htmlspecialchars($category['name'] ?? '') ?>" required>
      </div>
      <div class="col-12 d-flex gap-2">
        <button class="btn btn-primary" type="submit"><i class="bi bi-check2-circle me-1"></i>Update</button>
        <a class="btn btn-outline-light" href="list.php">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php include __DIR__ . "/../common/footer.php"; ?>
