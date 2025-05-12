<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once '../includes/session_protect.php';

// السماح فقط للمدير أو الموظف
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'employee')) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

// تحديد نوع الاستجابة
header('Content-Type: application/json');

// جلب عدد التنبيهات الغير مقروءة
$stmt = $pdo->query("SELECT COUNT(*) FROM notifications WHERE status = 'unread'");
$count = (int)$stmt->fetchColumn();

echo json_encode(['count' => $count]);
exit();
?>
