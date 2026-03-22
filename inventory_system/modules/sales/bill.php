<?php
session_start();
require_once "../../config/db.php";
if (!isset($_SESSION['user'])) {
  header("Location: ../../public/index.php");
  exit;
}

$customer_id = $_GET['customer_id'] ?? null;
if (!$customer_id) {
  die("Customer not specified.");
}

// Fetch customer info
$stmt = $pdo->prepare("SELECT * FROM customers WHERE id=?");
$stmt->execute([$customer_id]);
$customer = $stmt->fetch();

// Fetch all sales for this customer
$stmt = $pdo->prepare("SELECT id, date, total FROM sales WHERE customer_id=?");
$stmt->execute([$customer_id]);
$sales = $stmt->fetchAll();

$pageTitle = "Billing";
include __DIR__ . "/../common/head.php";
include __DIR__ . "/../common/nav.php";
?>

<div class="container py-3">
  <h2>Billing for <?= htmlspecialchars($customer['name']) ?></h2>

  <?php foreach ($sales as $sale): ?>
    <div class="card mb-3">
      <div class="card-header">
        Sale #<?= $sale['id'] ?> — <?= $sale['date'] ?>
      </div>
      <div class="card-body">
        <table class="table table-sm">
          <thead>
            <tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>
          </thead>
          <tbody>
          <?php
          // Fetch line items
          $stmt = $pdo->prepare("
            SELECT p.name, si.qty, si.sell_price, (si.qty * si.sell_price) AS subtotal
            FROM sale_items si
            JOIN products p ON si.product_id = p.id
            WHERE si.sale_id = ?
          ");
          $stmt->execute([$sale['id']]);
          $items = $stmt->fetchAll();
          foreach ($items as $item): ?>
            <tr>
              <td><?= htmlspecialchars($item['name']) ?></td>
              <td><?= $item['qty'] ?></td>
              <td><?= $item['sell_price'] ?></td>
              <td><?= $item['subtotal'] ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>

        <?php
        // Calculate payments
        $stmt = $pdo->prepare("SELECT SUM(amount) AS paid FROM payments WHERE sale_id=?");
        $stmt->execute([$sale['id']]);
        $paid = $stmt->fetchColumn() ?? 0;
        $due = $sale['total'] - $paid;
        ?>
        <p><strong>Total:</strong> <?= $sale['total'] ?></p>
        <p><strong>Paid:</strong> <?= $paid ?></p>
        <p><strong>Due:</strong> <?= $due ?></p>

        <!-- Payment form -->
        <?php if ($due > 0): ?>
          <form method="post" action="pay.php">
            <input type="hidden" name="sale_id" value="<?= $sale['id'] ?>">
            <input type="hidden" name="customer_id" value="<?= $customer_id ?>">
            <div class="input-group mb-2">
              <input type="number" step="0.01" name="amount" class="form-control" placeholder="Enter payment amount" min="0" max="<?php echo"$due" ?>" required>
              <button class="btn btn-success">Pay</button>
            </div>
          </form>
        <?php else: ?>
          <span class="badge bg-success">Fully Paid</span>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php include __DIR__ . "/../common/footer.php"; ?>
