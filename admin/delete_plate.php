<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once '../includes/session_protect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

// جلب رقم اللوحة قبل الحذف لتسجيله في السجل
$stmt = $pdo->prepare("SELECT plate_number FROM plates WHERE id = ?");
$stmt->execute([$id]);
$plate = $stmt->fetch();
$plate_number = $plate['plate_number'] ?? '';

// تنفيذ الحذف
$stmt = $pdo->prepare("DELETE FROM plates WHERE id = ?");
$stmt->execute([$id]);

// تسجيل السجل
$log = $pdo->prepare("INSERT INTO activity_logs (user_id, action, plate_id, plate_number) VALUES (?, ?, ?, ?)");
$log->execute([$_SESSION['user_id'], 'حذف لوحة', $id, $plate_number]);

header("Location: dashboard.php");
exit();
?>
