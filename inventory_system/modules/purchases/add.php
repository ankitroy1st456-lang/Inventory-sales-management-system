<?php
session_start();
require_once "../../config/db.php";
if(!isset($_SESSION['user'])) { header("Location: ../../public/index.php"); exit; }

$suppliers = $pdo->query("SELECT * FROM suppliers")->fetchAll();
$products  = $pdo->query("SELECT * FROM products")->fetchAll();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("INSERT INTO purchases (supplier_id, total) VALUES (?,?)");
    $stmt->execute([$_POST['supplier_id'], $_POST['total']]);
    $purchase_id = $pdo->lastInsertId();

    foreach($_POST['items'] as $item){
        $stmt = $pdo->prepare("INSERT INTO purchase_items (purchase_id, product_id, qty, cost_price) VALUES (?,?,?,?)");
        $stmt->execute([$purchase_id, $item['product_id'], $item['qty'], $item['cost_price']]);

        // update stock
        $stmt = $pdo->prepare("UPDATE products SET stock = stock + ? WHERE id=?");
        $stmt->execute([$item['qty'], $item['product_id']]);
    }
    $pdo->commit();
    header("Location: list.php");
    exit;
}
?>
<?php
$pageTitle = "New Purchase";
include __DIR__ . "/../common/head.php";
include __DIR__ . "/../common/nav.php";
?>

<div class="container py-3">
  <div class="app-page-header">
    <div>
      <h1 class="app-title mb-1">New Purchase</h1>
      <div class="text-muted">Record a purchase and update stock.</div>
    </div>
    <div class="app-actions">
      <a class="btn btn-outline-light btn-sm" href="list.php"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
  </div>

  <div class="app-card p-3 p-md-4">
    <form method="post" class="row g-3">
      <div class="col-12 col-md-6">
        <label class="form-label">Supplier</label>
        <select class="form-select" name="supplier_id" required>
          <?php foreach($suppliers as $s): ?>
            <option value="<?= (int)$s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-12 col-md-6">
        <label class="form-label">Total</label>
        <input class="form-control" type="number" step="0.01" name="total" min="0" value="0">
      </div>

      <div class="col-12">
        <div class="app-card p-3">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="fw-semibold">Item</div>
            <span class="text-muted small">Simplified: one product entry</span>
          </div>
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="form-label">Product</label>
              <select class="form-select" name="items[0][product_id]" required>
                <?php foreach($products as $p): ?>
                  <option value="<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-6 col-md-3">
              <label class="form-label">Qty</label>
              <input class="form-control" type="number" name="items[0][qty]" min="0" value="1">
            </div>
            <div class="col-6 col-md-3">
              <label class="form-label">Cost price</label>
              <input class="form-control" type="number" step="0.01" name="items[0][cost_price]" min="0" value="0">
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 d-flex gap-2">
        <button class="btn btn-primary" type="submit"><i class="bi bi-check2-circle me-1"></i>Save</button>
        <a class="btn btn-outline-light" href="list.php">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php include __DIR__ . "/../common/footer.php"; ?>
