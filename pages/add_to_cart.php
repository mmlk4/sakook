<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['service_id'])) {
    $service_id = $_POST['service_id'];

    // استرجاع تفاصيل الخدمة من قاعدة البيانات
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch();

    if ($service) {
        // إضافة الخدمة إلى السلة
        $_SESSION['cart'][$service_id] = [
            'id' => $service['id'],
            'name' => $service['name'],
            'price' => $service['price'],
            'image' => $service['image']
        ];

        $cart_count = count($_SESSION['cart']);

        echo json_encode(['status' => 'success', 'message' => 'تمت إضافة الخدمة إلى السلة!', 'cart_count' => $cart_count]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'الخدمة غير موجودة.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'طلب غير صالح.']);
}
?>
