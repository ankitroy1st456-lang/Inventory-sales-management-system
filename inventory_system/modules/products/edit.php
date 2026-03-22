<?php
session_start();
require_once "../../config/db.php";
if(!isset($_SESSION['user'])) { header("Location: ../../public/index.php"); exit; }

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $stmt = $pdo->prepare("UPDATE products SET name=?, category_id=?, cost_price=?, sell_price=?, stock=? WHERE id=?");
    $stmt->execute([$_POST['name'], $_POST['category_id'], $_POST['cost_price'], $_POST['sell_price'], $_POST['stock'], $id]);
    header("Location: list.php");
    exit;
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<?php
$pageTitle = "Edit Product";
include __DIR__ . "/../common/head.php";
include __DIR__ . "/../common/nav.php";
?>

<div class="container py-3">
  <div class="app-page-header">
    <div>
      <h1 class="app-title mb-1">Edit Product</h1>
      <div class="text-muted">Update product details.</div>
    </div>
    <div class="app-actions">
      <a class="btn btn-outline-light btn-sm" href="list.php"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
  </div>

  <div class="app-card p-3 p-md-4">
    <form method="post" class="row g-3">
      <div class="col-12">
        <label class="form-label">Name</label>
        <input class="form-control" type="text" name="name" value="<?= htmlspecialchars($product['name'] ?? '') ?>" required>
      </div>
      <div class="col-12 col-md-6">
        <label class="form-label">Category</label>
        <select class="form-select" name="category_id" required>
          <?php foreach($categories as $c): ?>
            <option value="<?= (int)$c['id'] ?>" <?= ((int)$c['id']=== (int)($product['category_id'] ?? 0))?'selected':'' ?>>
              <?= htmlspecialchars($c['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-12 col-md-6">
        <label class="form-label">Stock</label>
        <input class="form-control" type="number" name="stock" min="0" value="<?= htmlspecialchars($product['stock'] ?? 0) ?>">
      </div>
      <div class="col-12 col-md-6">
        <label class="form-label">Cost price</label>
        <input class="form-control" type="number" step="0.01" name="cost_price" min="0" value="<?= htmlspecialchars($product['cost_price'] ?? 0) ?>">
      </div>
      <div class="col-12 col-md-6">
        <label class="form-label">Sell price</label>
        <input class="form-control" type="number" step="0.01" name="sell_price" min="0" value="<?= htmlspecialchars($product['sell_price'] ?? 0) ?>">
      </div>
      <div class="col-12 d-flex gap-2">
        <button class="btn btn-primary" type="submit"><i class="bi bi-check2-circle me-1"></i>Update</button>
        <a class="btn btn-outline-light" href="list.php">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php include __DIR__ . "/../common/footer.php"; ?>
