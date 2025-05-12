<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

// التحقق من وجود ID صحيح
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: services.php");
    exit;
}

$id = intval($_GET['id']);

// جلب بيانات الخدمة
$stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
$stmt->execute([$id]);
$service = $stmt->fetch();

if (!$service) {
    header("Location: services.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($service['name']); ?> - تفاصيل الخدمة</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Tajawal', sans-serif;
      background-color: #f7f8fc;
      padding: 40px 0;
    }
    .service-container {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
      padding: 30px;
      max-width: 800px;
      margin: auto;
    }
    .service-container img {
      width: 100%;
      height: auto;
      max-height: 100%;
      border-radius: 12px;
      margin-bottom: 20px;
      background: #1f1247;
      padding: 10px;
    }
    .service-name {
      font-size: 26px;
      color: #3a04e3;
      font-weight: bold;
      margin-bottom: 15px;
      text-align: center;
    }
    .service-description {
      font-size: 16px;
      color: #666;
      margin-bottom: 25px;
      text-align: center;
    }
    .service-price {
      font-size: 22px;
      color: #3a04e3;
      font-weight: bold;
      text-align: center;
      margin-bottom: 30px;
    }
    .btn-buy {
      background-color: #3a04e3;
      color: white;
      font-weight: bold;
      padding: 10px 30px;
      border-radius: 30px;
      display: block;
      margin: 0 auto;
      transition: 0.3s;
    }
    .btn-buy:hover {
      background-color: rgb(51, 4, 194);
      color: #fff;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="service-container">
  <img src="<?= '../../uploads/services/' . htmlspecialchars($service['image']); ?>" 
    alt="<?= htmlspecialchars($service['name']); ?>" 
    class="img-fluid"
    style="max-width: 100%; height: auto; border-radius: 12px; background: #1f1247; padding: 10px;">
    <h1 class="service-name"><?= htmlspecialchars($service['name']); ?></h1>
    <p class="service-description"><?= nl2br(htmlspecialchars($service['description'])); ?></p>
    <div class="service-price"><?= number_format($service['price'], 2); ?> ريال</div>
    <a href="checkout.php?id=<?= $service['id']; ?>" class="btn btn-buy"><i class="fas fa-shopping-cart"></i> شراء الخدمة</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
