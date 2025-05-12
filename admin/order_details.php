<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once '../includes/session_protect.php';

// تحقق من الصلاحيات
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'employee')) {
    header("Location: login.php");
    exit();
}

// جلب الطلب
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    die('❌ الطلب غير موجود.');
}

// جلب عناصر الطلب
$stmt_items = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt_items->execute([$order_id]);
$items = $stmt_items->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>تفاصيل الطلب #<?= $order_id; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body {
  font-family: 'Tajawal', sans-serif;
  background: linear-gradient(to bottom, #f0f2f5, #fff);
  padding-top: 60px;
}
.container {
  max-width: 1000px;
}
.page-title {
  color: #3a04e3;
  font-weight: bold;
  text-align: center;
  margin-bottom: 30px;
}
.order-info, .order-items {
  background: #ffffffee;
  padding: 30px;
  border-radius: 20px;
  margin-bottom: 20px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
}
.order-items table {
  width: 100%;
}
.status-badge {
  padding: 8px 15px;
  border-radius: 30px;
  font-size: 15px;
}
.status-review { background: #0d6efd; color: #fff; }
.status-processing { background: #ffc107; color: #212529; }
.status-completed { background: #28a745; color: #fff; }
.status-rejected { background: #dc3545; color: #fff; }
.back-btn {
  background-color: transparent;
  color: #3a04e3;
  border-radius: 30px;
  padding: 4px 40px;
  display: block;
  margin: 25px auto 0;
  width: fit-content;
  font-size: 14px;
  font-weight: bold;
  border: 2px solid #3a04e3;
  transition: 0.3s ease-in-out;
}
.back-btn:hover {
  background-color: #3a04e3;
  color: #fff;
  box-shadow: 0 0 10px rgba(58, 4, 227, 0.3);
}
.table th {
  background-color: #3a04e3;
  color: white;
}
.table td {
  vertical-align: middle;
}
.contact-icon {
  margin-right: 8px;
  font-size: 18px;
}
.icon-email { background-color: #0d6efd; }
.icon-whatsapp { background-color: #25d366; }
.icon-email:hover { background-color: #0a58ca; }
.icon-whatsapp:hover { background-color: #1ebe5d; }

a {
  display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 30px;
    border-radius: 50%;
    font-size: 18px;
    color: white;
    text-decoration: none;
}
</style>
</head>
<body>

<div class="container">
<h2 class="page-title"><i class="fas fa-receipt me-2"></i> تفاصيل الطلب #<?= $order_id; ?></h2>

<div class="order-info">
  <h5 class="mb-3"><i class="fas fa-user me-2"></i> بيانات العميل</h5>
  <p><strong>👤 الاسم:</strong> <?= htmlspecialchars($order['customer_name']); ?></p>

  <p>
    <strong>📞 الجوال:</strong> <?= htmlspecialchars($order['customer_phone']); ?>
    <a href="https://wa.me/<?= '966' . ltrim($order['customer_phone'], '0') ?>?text=مرحباً <?= urlencode($order['customer_name']) ?>، بخصوص طلبك رقم #<?= $order_id ?>..."
      target="_blank" class="icon-whatsapp" title="تواصل عبر واتساب">
      <i class="fab fa-whatsapp"></i>
    </a>
  </p>
  <p>
    <strong>✉️ البريد الإلكتروني:</strong> <?= htmlspecialchars($order['customer_email']); ?>
    <a href="mailto:<?= $order['customer_email'] ?>?subject=متابعة طلبك رقم <?= $order_id ?>&body=مرحباً <?= $order['customer_name'] ?>،"
      class="icon-email" title="إرسال بريد">
      <i class="fas fa-envelope"></i>
    </a>
  </p>

  <p><strong>💳 طريقة الدفع:</strong> <?= htmlspecialchars($order['payment_method']); ?></p>

  <h5 class="mt-4 mb-3"><i class="fas fa-info-circle me-2"></i> حالة الطلب</h5>
  <?php
    $statusClass = match($order['order_status']) {
      'مكتمل' => 'status-completed',
      'تحت المعالجة' => 'status-processing',
      'مرفوض' => 'status-rejected',
      default => 'status-review'
    };
  ?>
  <span class="status-badge <?= $statusClass; ?>"><?= htmlspecialchars($order['order_status']); ?></span>

  <p class="mt-3"><strong>💰 الإجمالي:</strong> <?= number_format($order['total_price'], 2); ?> ريال</p>
</div>

<div class="order-items">
  <h5 class="mb-3"><i class="fas fa-box-open me-2"></i> تفاصيل الخدمات المطلوبة</h5>

  <table class="table table-bordered text-center">
    <thead>
      <tr>
        <th>اسم الخدمة</th>
        <th>السعر</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($items as $item): ?>
      <tr>
        <td><?= htmlspecialchars($item['service_name']); ?></td>
        <td><?= number_format($item['service_price'], 2); ?> ريال</td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<a href="manage_orders.php" class="btn back-btn"><i class="fas fa-arrow-right"></i> الرجوع لإدارة الطلبات</a>
<br>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
