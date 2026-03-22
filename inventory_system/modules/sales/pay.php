<?php
session_start();
require_once "../../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $sale_id = $_POST['sale_id'];
  $amount = $_POST['amount'];
  $customer_id = $_POST['customer_id'];

  $stmt = $pdo->prepare("INSERT INTO payments (sale_id, amount) VALUES (?, ?)");
  $stmt->execute([$sale_id, $amount]);

  header("Location: bill.php?customer_id=" . $customer_id);
  exit;
}
