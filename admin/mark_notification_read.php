<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// تأكد ان id موجود
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $notif_id = intval($_POST['id']);

    // تحديث حالة التنبيه
    $stmt = $pdo->prepare("UPDATE notifications SET status = 'read' WHERE id = ?");
    $stmt->execute([$notif_id]);

    echo json_encode(['status' => 'success']);
    exit;
}

echo json_encode(['status' => 'error']);
exit;
?>
