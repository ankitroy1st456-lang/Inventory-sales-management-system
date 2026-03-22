<?php
session_start();
require_once "../../config/db.php";
if(!isset($_SESSION['user'])) { 
    header("Location: ../../public/index.php"); 
    exit; 
}

$customers = $pdo->query("SELECT * FROM customers")->fetchAll();
$products  = $pdo->query("SELECT * FROM products")->fetchAll();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("INSERT INTO sales (customer_id, total) VALUES (?,?)");
    $stmt->execute([$_POST['customer_id'], $_POST['total']]);
    $sale_id = $pdo->lastInsertId();

    foreach($_POST['items'] as $item){
        $stmt = $pdo->prepare("INSERT INTO sale_items (sale_id, product_id, qty, sell_price) VALUES (?,?,?,?)");
        $stmt->execute([$sale_id, $item['product_id'], $item['qty'], $item['sell_price']]);

        // update stock (decrease)
        $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id=?");
        $stmt->execute([$item['qty'], $item['product_id']]);
    }
    $pdo->commit();
    header("Location: list.php");
    exit;
}
?>
<?php
$pageTitle = "New Sale";
include __DIR__ . "/../common/head.php";
include __DIR__ . "/../common/nav.php";
?>

<div class="container py-3">
  <div class="app-page-header">
    <div>
      <h1 class="app-title mb-1">New Sale</h1>
      <div class="text-muted">Record a sale and update stock.</div>
    </div>
    <div class="app-actions">
      <a class="btn btn-outline-light btn-sm" href="list.php"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
  </div>

  <div class="app-card p-3 p-md-4">
    <form method="post" class="row g-3">
      <div class="col-12 col-md-6">
        <label class="form-label">Customer</label>
        <select class="form-select" name="customer_id" required>
          <?php foreach($customers as $c): ?>
            <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-12">
        <div class="app-card p-3">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="fw-semibold">Item</div>
            <span class="text-muted small">Select product and quantity</span>
          </div>
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="form-label">Product</label>
              <select class="form-select" id="productSelect" name="items[0][product_id]" required>
                <?php foreach($products as $p): ?>
                  <option value="<?= (int)$p['id'] ?>" 
                          data-stock="<?= (int)$p['stock'] ?>" 
                          data-price="<?= (float)$p['sell_price'] ?>">
                    <?= htmlspecialchars($p['name']) ?> (Stock: <?= (int)$p['stock'] ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-6 col-md-3">
              <label class="form-label">Qty</label>
              <input placeholder="Qty" class="form-control" type="number" id="qtyInput" 
                     name="items[0][qty]" min="0" value="1">
            </div>

            <div class="col-6 col-md-3">
              <label class="form-label">Sell Price</label>
              <input class="form-control" type="number" step="0.01" id="priceInput" 
                     name="items[0][sell_price]" readonly>
            </div>
          </div>

          <div class="mt-2">
            <strong>Calculated Line Total: </strong>
            <span id="calcTotal">0.00</span>
          </div>
        </div>
      </div>

      <div class="col-12">
        <label class="form-label">Total (enter manually)</label>
        <input class="form-control" type="number" step="0.01" id="totalInput" 
               name="total" min="0" value="0">
      </div>

      <div class="col-12 d-flex gap-2">
        <button class="btn btn-primary" type="submit"><i class="bi bi-check2-circle me-1"></i>Save</button>
        <a class="btn btn-outline-light" href="list.php">Cancel</a>
      </div>
    </form>
  </div>
</div>

<script>
  const productSelect = document.getElementById('productSelect');
  const qtyInput = document.getElementById('qtyInput');
  const priceInput = document.getElementById('priceInput');
  const calcTotal = document.getElementById('calcTotal');

  function updateFields() {
    const selected = productSelect.options[productSelect.selectedIndex];
    const stock = selected.dataset.stock;
    const price = selected.dataset.price;

    // Set max qty based on stock
    qtyInput.max = stock;
    qtyInput.disabled = (stock == 0);

    // Update price field
    priceInput.value = price;

    // Show calculated line total (qty × price)
    const lineTotal = qtyInput.value * price;
    calcTotal.textContent = lineTotal.toFixed(2);
  }

  // Initial setup
  updateFields();

  // Update when product changes
  productSelect.addEventListener('change', updateFields);

  // Update when qty changes
  qtyInput.addEventListener('input', updateFields);
</script>

<?php include __DIR__ . "/../common/footer.php"; ?>
