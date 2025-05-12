<?php
session_start();
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>الدفع</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      font-family: 'Tajawal', sans-serif;
      background: linear-gradient(135deg, #f0f2f5, #fff);
      padding-top: 60px;
      min-height: 100vh;
    }
    .checkout-container {
      max-width: 700px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }
    .checkout-title {
      text-align: center;
      color: #3a04e3;
      font-weight: bold;
      margin-bottom: 30px;
      font-size: 30px;
    }
    .form-control, .form-select {
      border-radius: 12px;
    }
    .btn-submit {
      background-color: #3a04e3;
      color: white;
      font-weight: bold;
      border-radius: 30px;
      padding: 12px 0;
      font-size: 18px;
    }
    .btn-submit:hover {
      background-color: rgb(51, 4, 194);
    }
    .form-section-title {
      font-weight: bold;
      margin-bottom: 15px;
      color: #3a04e3;
    }
    .hidden {
      display: none;
    }
  </style>
</head>

<body>

<div class="container">
  <div class="checkout-container">
    <h2 class="checkout-title"><i class="fas fa-credit-card"></i> الدفع الآمن</h2>

    <form action="payment_process.php" method="POST" id="checkout-form">

      <div class="form-section-title">بيانات العميل</div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <input type="text" name="customer_name" class="form-control" placeholder="الاسم الكامل" required>
        </div>
        <div class="col-md-6 mb-3">
          <input type="text" name="customer_phone" class="form-control" placeholder="5xxxxxxx" required>
        </div>
        <div class="col-12 mb-3">
          <input type="email" name="customer_email" class="form-control" placeholder="البريد الإلكتروني" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">طريقة الدفع</label>
        <select name="payment_method" class="form-select" id="payment_method" required>
          <option value="">اختر طريقة الدفع</option>
          <option value="بطاقة ائتمانية">بطاقة مدى أو ائتمانية</option>
          <option value="تحويل بنكي">تحويل بنكي</option>
          <option value="STC Pay">STC Pay</option>
        </select>
      </div>

      <!-- أقسام طرق الدفع -->
      <div id="card-section" class="hidden">
        <div class="alert alert-info">
          سيتم التوجيه لإدخال بيانات البطاقة بأمان عبر بوابة Tap.
        </div>
      </div>

      <div id="bank-section" class="hidden mt-3">
        <div class="alert alert-info">
          <p><strong>تفاصيل الحساب البنكي:</strong></p>
          <p>البنك الأهلي - رقم الحساب: 1234567890</p>
          <p>البنك الراجحي - رقم الحساب: 9876543210</p>
          <p>يرجى إرسال صورة الإيصال بعد التحويل عبر الدعم.</p>
        </div>
      </div>

      <div id="stcpay-section" class="hidden mt-3">
        <div class="alert alert-success">
          <p><strong>الدفع عبر STC Pay:</strong></p>
          <p>سيتم توجيهك لتأكيد الدفع داخل تطبيق STC Pay.</p>
        </div>
      </div>

      <button type="submit" class="btn btn-submit w-100 mt-4">
        <i class="fas fa-lock me-2"></i> تأكيد الطلب والدفع
      </button>
    </form>

  </div>
</div>

<script>
  const paymentMethodSelect = document.getElementById('payment_method');
  const cardSection = document.getElementById('card-section');
  const bankSection = document.getElementById('bank-section');
  const stcpaySection = document.getElementById('stcpay-section');

  paymentMethodSelect.addEventListener('change', function () {
    const selected = this.value;
    cardSection.classList.add('hidden');
    bankSection.classList.add('hidden');
    stcpaySection.classList.add('hidden');

    if (selected === 'بطاقة ائتمانية') {
      cardSection.classList.remove('hidden');
    } else if (selected === 'تحويل بنكي') {
      bankSection.classList.remove('hidden');
    } else if (selected === 'STC Pay') {
      stcpaySection.classList.remove('hidden');
    }
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
<script>
function togglePaymentSections() {
  const method = document.getElementById('payment_method');
  const card = document.getElementById('card-section');
  const bank = document.getElementById('bank-section');
  const stc = document.getElementById('stcpay-section');

  if (!method) return;

  const selected = method.value;
  card?.classList.add('hidden');
  bank?.classList.add('hidden');
  stc?.classList.add('hidden');

  if (selected === 'بطاقة ائتمانية') {
    card?.classList.remove('hidden');
  } else if (selected === 'تحويل بنكي') {
    bank?.classList.remove('hidden');
  } else if (selected === 'STC Pay') {
    stc?.classList.remove('hidden');
  }
}

document.addEventListener('change', function (e) {
  if (e.target.id === 'payment_method') {
    togglePaymentSections();
  }
});

// في حال تم تحميل المحتوى داخل مودال
document.addEventListener('DOMContentLoaded', togglePaymentSections);

// إذا كنت تستخدم Bootstrap modal
const modal = document.getElementById('checkoutModal');
if (modal) {
  modal.addEventListener('shown.bs.modal', togglePaymentSections);
}
</script>
