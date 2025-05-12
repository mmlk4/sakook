<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>سلة المشتريات</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Tajawal', sans-serif;
      background-color: #f7f8fc;
      padding: 60px 0;
    }
    .cart-item {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.05);
      padding: 20px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .cart-item img {
      width: 100px;
      height: 100px;
      object-fit: contain;
      background-color: #f1f1f1;
      padding: 10px;
      border-radius: 12px;
    }
    .cart-info {
      flex: 1;
    }
    .cart-title {
      font-weight: bold;
      color: #333;
      margin-bottom: 8px;
    }
    .cart-price {
      color: #3a04e3;
      font-weight: bold;
    }
    .cart-actions button {
      border: none;
      background: none;
      color: red;
      font-size: 20px;
    }
    .cart-total {
      font-size: 22px;
      font-weight: bold;
      color: #3a04e3;
      text-align: center;
      margin-top: 30px;
    }
    .checkout-btn {
      background-color: #3a04e3;
      color: white;
      border-radius: 50px;
      font-weight: bold;
      padding: 10px 30px;
      display: block;
      margin: 20px auto;
      transition: 0.3s;
    }
    .checkout-btn:hover {
      background-color: rgb(51, 4, 194);
    }
  </style>
</head>
<body>

<div class="container">
  <h2 class="text-center mb-4"><i class="fas fa-shopping-cart"></i> سلة المشتريات</h2>

  <?php if (count($cart) > 0): ?>
    <?php foreach ($cart as $id => $item): ?>
      <?php $total += $item['price'] * $item['quantity']; ?>
      <div class="cart-item">
        <img src="../../uploads/services/<?= htmlspecialchars($item['image']); ?>" alt="<?= htmlspecialchars($item['name']); ?>">
        <div class="cart-info">
          <div class="cart-title"><?= htmlspecialchars($item['name']); ?></div>
          <div class="cart-price"><?= number_format($item['price'], 2); ?> ريال</div>
        </div>
        <div class="cart-actions">
          <button onclick="removeFromCart(<?= $id; ?>)"><i class="fas fa-trash"></i></button>
        </div>
      </div>
    <?php endforeach; ?>

    <div class="cart-total">
      الإجمالي: <?= number_format($total, 2); ?> ريال
    </div>

    <a href="checkout.php" class="btn checkout-btn">اتمام الطلب</a>
  <?php else: ?>
    <div class="alert alert-info text-center">
      سلة المشتريات فارغة 🛒
    </div>
  <?php endif; ?>
</div>

<script>
function removeFromCart(id) {
    fetch('remove_from_cart.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `id=${id}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        }
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
