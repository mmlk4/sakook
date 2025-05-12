<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once '../includes/session_protect.php';
// التحقق من الصلاحية
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'employee')) {
    header("Location: login.php");
    exit();
}

// جلب الطلبات المكتملة أو المرفوضة فقط
$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_status IN ('مكتمل', 'مرفوض') ORDER BY id DESC");
$stmt->execute();
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>أرشيف الطلبات</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Tajawal', sans-serif; background: #f8f9fa; padding-top: 60px; }
    .container { max-width: 1200px; }
    .page-title { color: #3a04e3; text-align: center; font-weight: bold; margin-bottom: 30px; }
    .status-badge { padding: 6px 12px; border-radius: 30px; font-size: 14px; }
    .status-completed { background: #28a745; color: white; }
    .status-rejected { background: #dc3545; color: white; }
    .btn-back { background-color: #3a04e3; color: white; border-radius: 30px; padding: 10px 30px; font-weight: bold; }
    .btn-back:hover { background-color: #2b03bd; }
  </style>
</head>
<body>

<div class="container">
  <h2 class="page-title"><i class="fas fa-archive"></i> أرشيف الطلبات (المكتملة / المرفوضة)</h2>

  <table class="table table-bordered table-hover text-center bg-white">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>اسم العميل</th>
        <th>طريقة الدفع</th>
        <th>الحالة</th>
        <th>المجموع</th>
        <th>التفاصيل</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($orders as $order): ?>
      <tr>
        <td><?= $order['id']; ?></td>
        <td><?= htmlspecialchars($order['customer_name']); ?></td>
        <td><?= htmlspecialchars($order['payment_method']); ?></td>
        <td>
          <span class="status-badge <?= $order['order_status'] === 'مكتمل' ? 'status-completed' : 'status-rejected'; ?>">
            <?= $order['order_status']; ?>
          </span>
        </td>
        <td><?= number_format($order['total_price'], 2); ?> ريال</td>
        <td>
          <a href="order_details.php?id=<?= $order['id']; ?>" class="btn btn-sm btn-outline-primary">عرض</a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

  <div class="text-center">
    <a href="manage_orders.php" class="btn btn-back"><i class="fas fa-arrow-right"></i> الرجوع لإدارة الطلبات</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
