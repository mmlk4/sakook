<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo '<p class="text-center text-muted my-3">السلة فارغة حالياً 🛒</p>';
    exit;
}

$total = 0;
?>

<ul class="list-group">
  <?php foreach ($_SESSION['cart'] as $id => $item): ?>
    <li class="list-group-item d-flex justify-content-between align-items-center">
      <div>
        <strong><?= htmlspecialchars($item['name']); ?></strong><br>
        <small class="text-muted"><?= number_format($item['price'], 2); ?> ريال</small>
      </div>
      <button class="btn btn-sm btn-outline-danger remove-item" data-id="<?= $id; ?>">
        <i class="fas fa-trash"></i>
      </button>
    </li>
    <?php $total += $item['price']; ?>
  <?php endforeach; ?>
</ul>

<hr>
<div class="d-flex justify-content-between mt-2">
  <strong>الإجمالي:</strong>
  <strong><?= number_format($total, 2); ?> ريال</strong>
</div>
