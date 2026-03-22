<?php
session_start();
require_once "../../config/db.php";
if($_SESSION['user']['role'] !== 'admin'){ die("Access denied"); }

$id = $_GET['id'];
$stmt = $pdo->prepare("UPDATE users SET role='admin' WHERE id=?");
$stmt->execute([$id]);

header("Location: list.php");
exit;
