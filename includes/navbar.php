<style>
  .dropdown-menu a.dropdown-item {
    text-align: right !important;
  }

  /* تحسين شكل زر السلة */
  .cart-btn {
    position: relative;
    border: 2px solid #3a04e3;
    color: #3a04e3;
    background-color: transparent;
    border-radius: 50%;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
  }

  .cart-btn:hover {
    background-color: #3a04e3;
    color: white;
    box-shadow: 0 0 10px rgba(58, 4, 227, 0.3);
  }

  .cart-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    font-size: 13px;
    padding: 4px 7px;
  }
</style>

<?php
$current_page = basename($_SERVER['PHP_SELF']);
$show_categories = $current_page === 'index.php';

// احتساب عدد المنتجات في السلة
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

$categories = [
  'لوحه أحرف وأرقام متشابهة' => 'أحرف وأرقام متشابهة',
  'لوحه مميزه الرقم واحد' => 'الرقم واحد',
  'لوحه سيارة الرقم فردي' => 'الرقم فردي',
  'لوحه سيارة رقمين' => 'رقمين',
  'لوحه سيارة ثلاث أرقام' => 'ثلاث أرقام',
  'لوحه سيارة أربع أرقام' => 'أربع أرقام',
  'لوحه مميزه نقل خاص' => 'نقل خاص',
  'لوحه مميزه دراجة ناريه' => 'دراجة نارية',
  'لوحة مميزة' => 'مميزة',
  'لوحة نادرة' => 'نادرة'
];
?>

<nav class="navbar navbar-expand-lg fixed-top shadow-sm"
    style="backdrop-filter: blur(5px); background-color: rgba(255, 255, 255, 0.85); border-bottom: 1px solid #e0e0e0;">
  <div class="container d-flex justify-content-between align-items-center">

    <div class="collapse navbar-collapse order-2 order-lg-1" id="navbarMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 text-end">
        <li class="nav-item">
          <a class="nav-link text-dark fw-semibold px-3" href="../index.php">الرئيسية</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark fw-semibold px-3" href="../about.php">من نحن</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark fw-semibold px-3" href="../contact.php">اتصل بنا</a>
        </li>

        <?php if ($show_categories): ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-dark fw-semibold px-3" href="#" id="categoryDropdown"
            role="button" data-bs-toggle="dropdown" aria-expanded="false">
            التصنيفات
          </a>
          <ul class="dropdown-menu dropdown-menu-end text-end" dir="rtl" aria-labelledby="categoryDropdown">
            <li><a class="dropdown-item" href="index.php">الكل</a></li>
            <?php foreach ($categories as $value => $label): ?>
              <li><a class="dropdown-item" href="index.php?category=<?= urlencode($value); ?>"><?= $label; ?></a></li>
            <?php endforeach; ?>
          </ul>
        </li>
        <?php endif; ?>
      </ul>
    </div>

    <?php if ($current_page === 'services.php'): ?>
      <!-- زر السلة يظهر فقط في صفحة الخدمات -->
      <button type="button" class="cart-btn me-3" data-bs-toggle="modal" data-bs-target="#cartModal" aria-label="سلة المشتريات">
        <i class="fas fa-shopping-cart"></i>
        <?php if ($cart_count > 0): ?>
          <span class="badge bg-danger rounded-pill cart-badge" id="cart-count"><?= $cart_count; ?></span>
        <?php else: ?>
          <span class="badge bg-danger rounded-pill cart-badge" id="cart-count">0</span>
        <?php endif; ?>
      </button>
    <?php endif; ?>

    <a class="navbar-brand fw-bold text-dark order-1 order-lg-2 ms-lg-auto" 
      href="../index.php" style="font-size: 18px;">
      <img src="../assets/images/logo.png" alt="صكوك الحديثة" style="height: 40px;"
          onerror="this.style.display='none'; this.insertAdjacentHTML('afterend', '<span class=\'text-dark fw-bold\'>صكوك الحديثة</span>');">
    </a>

    <button class="navbar-toggler border-0 order-3" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

  </div>
</nav>
