<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

// البحث عن الخدمات
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search) {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE name LIKE ? OR description LIKE ? ORDER BY id DESC");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM services ORDER BY id DESC");
}
$services = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>خدماتنا</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Tajawal', sans-serif;
      background-color: #f7f8fc;
      padding: 50px 0;
    }
    .service-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
    transition: transform 0.4s ease, box-shadow 0.4s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
  }
  .service-card:hover {
    transform: translateY(-8px) scale(1.03);
    box-shadow: 0 12px 25px rgba(58, 4, 227, 0.2);
  }
    .service-card img {
      width: 100%;
      height: 130px;
      object-fit: contain;
      padding: 10px;
      background-color: #1f1247;
    }
    .service-body {
      padding: 15px;
      flex: 1;
    }
    .service-title {
    font-size: 16px;
    font-weight: bold;
    color: #333;
    margin: 8px 0;
  }
    .service-description {
      color: #666;
      font-size: 13px;
      text-align: center;
      margin-bottom: 10px;
      height: 40px;
      overflow: hidden;
    }
    .service-price {
    font-size: 14px;
    font-weight: bold;
    color: #3a04e3;
  }
    .btn-details {
    background-color: transparent;
    border: 2px solid #3a04e3;
    color: #3a04e3;
    border-radius: 30px;
    padding: 6px 18px;
    font-size: 14px;
    transition: 0.3s;
  }

  .btn-details:hover {
    background-color: #3a04e3;
    color: #fff;
  }
    .services-title {
      text-align: center;
      font-size: 28px;
      font-weight: bold;
      color: #3a04e3;
      margin-bottom: 30px;
    }
    .search-box {
      margin-bottom: 30px;
      text-align: center;
    }
    .search-box input[type="text"] {
      border-radius: 30px;
      width: 300px;
      padding: 8px 20px;
      border: 1px solid #ddd;
    }
    .search-box button {
      background-color: #3a04e3;
      color: white;
      border: none;
      padding: 3px 9px;
      border-radius: 30px;
      margin-right: 10px;
      font-weight: bold;
    }
    @media (min-width: 1200px) {
      .col-xl-2-4 {
        flex: 0 0 auto;
        width: 20%;
      }
    }
    .footer {
      background-color: #212529;
      color: #ccc;
      padding: 10px 0;
      text-align: center;
      width: 100%;
      bottom: -44px !important;
      left: 0;
      position: relative;
      top: 60px;
    }
  </style>
</head>
<body>
  <!-- NAVBAR -->
  <?php include __DIR__ . '/../../includes/navbar.php'; ?>
<!-- Toast تنبيه إضافة للسلة -->
<div class="position-fixed top-0 end-0 p-2" style="z-index: 9999;">
  <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        تمت إضافة الخدمة إلى السلة! 🎯
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="إغلاق"></button>
    </div>
  </div>
</div>


<br>
<div class="container">
  <!-- <h2 class="services-title"><i class="fas fa-cogs me-2"></i> خدماتنا</h2> -->
  <div class="container mb-4 sticky-top" style="top: 90px; z-index: 999;">
  <form method="GET" action="services.php" class="position-relative   rounded-pill ">
    <input type="text" name="search" class="form-control rounded-pill ps-5" placeholder="ابحث عن خدمة..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    <button type="submit" class="btn btn-primary position-absolute top-50 start-0 translate-middle-y rounded-pill me-2" style="padding: 6px 15px; background-color: #3a04e3; border-color: #3a04e3;">
      <i class="fas fa-search"></i>
    </button>
  </form>
  </div>
  <br>
  <div class="row g-3">
  <?php foreach ($services as $service): ?>
    <div class="col-6 col-md-4 col-lg-2">
      <div class="service-card h-100 text-center p-2 d-flex flex-column align-items-center justify-content-between shadow-sm" style="transition: transform 0.3s ease, box-shadow 0.3s ease;">
        <img src="<?= '../../uploads/services/' . htmlspecialchars($service['image']); ?>" alt="<?= htmlspecialchars($service['name']); ?>" class="img-fluid" style="max-height: 120px; object-fit: contain;">
        <div class="service-body mt-3">
          <div class="service-title fs-6"><?= htmlspecialchars($service['name']); ?></div>
          <div class="service-price text-primary fw-bold"><?= number_format($service['price'], 2); ?> ريال</div>
          <button class="btn btn-sm btn-outline-primary rounded-pill mt-2 add-to-cart-btn" data-id="<?= $service['id']; ?>">
                  <i class="fas fa-cart-plus"></i> أضف للسلة
          </button>

        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
</div>

<!-- نافذة سلة المشتريات -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cartModalLabel">سلة المشتريات</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>
      <div class="modal-body">
        <!-- محتوى السلة سيتم تحميله هنا -->
        <div id="cart-content">
          <!-- يمكن تحميل محتوى السلة باستخدام AJAX أو تضمينه مباشرة -->
          <p>السلة فارغة حاليًا.</p>
        </div>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-primary" id="openCheckout">إتمام الطلب</button>
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
      </div>
    </div>
  </div>
</div>

<!-- نافذة منبثقة لصفحة الدفع -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="checkoutModalLabel">إتمام الطلب</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>
      <div class="modal-body" id="checkout-content">
        <!-- سيتم تحميل محتوى checkout.php هنا -->
      </div>
    </div>
  </div>
</div>

<!-- نافذة منبثقة لصفحة التأكيد -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="successModalLabel">تأكيد الطلب</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>
      <div class="modal-body" id="success-content">
        <!-- سيتم تحميل محتوى success.php هنا -->
      </div>
    </div>
  </div>
</div>


<footer class="footer mt-auto">
  <?php include __DIR__ . '/../../includes/footer.php'; ?>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.querySelectorAll('.add-to-cart-btn').forEach(button => {
    button.addEventListener('click', function () {
        const serviceId = this.getAttribute('data-id');

        fetch('add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `service_id=${serviceId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showToast(data.message, 'success');
                updateCartCount();
            } else {
                showToast(data.message, 'danger');
            }
        })
        .catch(error => {
            showToast('حدث خطأ أثناء الإضافة ❌', 'danger');
        });
    });
});

// توست التنبيه
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-bg-${type} border-0 position-fixed top-0 end-0 m-4`;
    toast.style.zIndex = '9999';
    toast.role = 'alert';
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;

    document.body.appendChild(toast);

    const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
    bsToast.show();

    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const cartModal = document.getElementById('cartModal');
    cartModal.addEventListener('show.bs.modal', function () {
      fetch('cart_content.php')
        .then(response => response.text())
        .then(data => {
          document.getElementById('cart-content').innerHTML = data;
        })
        .catch(error => {
          document.getElementById('cart-content').innerHTML = '<p>حدث خطأ أثناء تحميل السلة.</p>';
        });
    });
  });
</script>
<script>
function refreshCartModal() {
  $.get('cart_content.php', function (data) {
    $('#cart-content').html(data);
  });
}

$(document).on('click', '.remove-item', function () {
    const serviceId = $(this).data('id');

    $.post('remove_from_cart.php', { id: serviceId }, function (response) {
        if (response.status === 'success') {
            refreshCartModal();
            updateCartCount(response.cart_count);
        }
    }, 'json');
});

</script>
<script>
$('#cartModal').on('show.bs.modal', function () {
  refreshCartModal();
});
</script>
<script>
document.getElementById('openCheckout').addEventListener('click', function () {
  fetch('checkout.php')
    .then(response => response.text())
    .then(data => {
      document.getElementById('checkout-content').innerHTML = data;
      var checkoutModal = new bootstrap.Modal(document.getElementById('checkoutModal'));
      checkoutModal.show();
    })
    .catch(error => {
      console.error('حدث خطأ أثناء تحميل صفحة الدفع:', error);
    });
});
</script>

<script>
document.getElementById('checkoutModal').addEventListener('shown.bs.modal', function () {
  // تحميل السكربت من داخل checkout.php بعد عرضه
  const method = document.getElementById('payment_method');
  if (!method) return;

  const card = document.getElementById('card-section');
  const bank = document.getElementById('bank-section');
  const stc = document.getElementById('stcpay-section');

  const toggleSections = () => {
    card?.classList.add('hidden');
    bank?.classList.add('hidden');
    stc?.classList.add('hidden');

    const selected = method.value;
    if (selected === 'بطاقة ائتمانية') card?.classList.remove('hidden');
    else if (selected === 'تحويل بنكي') bank?.classList.remove('hidden');
    else if (selected === 'STC Pay') stc?.classList.remove('hidden');
  };

  method.addEventListener('change', toggleSections);
  toggleSections(); // ← تفعيل العرض حسب القيمة الحالية
});
</script>

<script>
// تحديث عداد السلة
function updateCartCount() {
  fetch('cart_count.php')
    .then(res => res.text())
    .then(count => {
      const cartCount = document.getElementById('cart-count');
      if (cartCount) {
        cartCount.textContent = count;
      }
    });
}
</script>

</body>
</html>
