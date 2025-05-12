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
    .card-preview {
      border-radius: 16px;
      padding: 25px;
      color: white;
      margin-bottom: 30px;
      background: linear-gradient(135deg, #1f1247, #3a04e3);
      box-shadow: 0 6px 20px rgba(0,0,0,0.15);
      position: relative;
      overflow: hidden;
      transition: all 0.5s ease;
    }
    .card-preview img {
      width: 70px;
      position: absolute;
      top: 20px;
      left: 20px;
      object-fit: contain;
    }
    .card-number {
      font-size: 22px;
      letter-spacing: 4px;
      margin-top: 60px;
      margin-bottom: 15px;
    }
    .card-holder {
      font-size: 16px;
      opacity: 0.9;
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

```
<form action="payment_process.php" method="POST">

  <div class="form-section-title">بيانات العميل</div>
  <div class="row">
    <div class="col-md-6 mb-3">
      <input type="text" name="customer_name" class="form-control" placeholder="الاسم الكامل" required>
    </div>
    <div class="col-md-6 mb-3">
      <input type="text" name="customer_phone" class="form-control" placeholder="رقم الجوال" required>
    </div>
    <div class="col-12 mb-3">
      <input type="email" name="customer_email" class="form-control" placeholder="البريد الإلكتروني" required>
    </div>
  </div>

  <div class="mb-3">
    <label class="form-label">طريقة الدفع</label>
    <select name="payment_method" class="form-select" id="payment_method" required>
      <option value="">اختر طريقة الدفع</option>
      <option value="بطاقة ائتمانية">بطاقة ائتمانية</option>
      <option value="تحويل بنكي">تحويل بنكي</option>
      <option value="STC Pay">STC Pay</option>
    </select>
  </div>

  <!-- نموذج البطاقة -->
  <div id="card-section" class="hidden">
    <div class="card-preview" id="card-preview">
      <img id="card-logo" src="assets/images/cards/default-card.png" alt="بطاقة">
      <div class="card-number" id="card-number-display">•••• •••• •••• ••••</div>
      <div class="card-holder" id="card-holder-display">اسم حامل البطاقة</div>
    </div>

    <div class="mb-3">
      <input type="text" class="form-control" maxlength="17" name="card_number" id="card_number" placeholder="رقم البطاقة •••• •••• •••• ••••">
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <input type="text" class="form-control" maxlength="4" name="expiry_date" placeholder="تاريخ الانتهاء MM/YY">
      </div>
      <div class="col-md-6 mb-3">
        <input type="text" class="form-control" maxlength="3" name="cvv" placeholder="CVV">
      </div>
    </div>

    <div class="mb-3">
      <input type="text" class="form-control" name="card_holder" id="card_holder" placeholder="اسم حامل البطاقة">
    </div>
  </div>

  <!-- بيانات التحويل البنكي -->
  <div id="bank-section" class="hidden mt-3">
    <div class="alert alert-info">
      <p><strong>تفاصيل الحساب البنكي:</strong></p>
      <p>البنك الأهلي - رقم الحساب: 1234567890</p>
      <p>البنك الراجحي - رقم الحساب: 9876543210</p>
      <p>ملاحظة: يرجى إرسال صورة الإيصال بعد التحويل عبر الدعم.</p>
    </div>
  </div>

  <!-- تعليمات STC Pay -->
  <div id="stcpay-section" class="hidden mt-3">
    <div class="alert alert-success">
      <p><strong>رقم STC Pay:</strong> 0555555555</p>
      <p>يرجى إرسال صورة الإيصال بعد التحويل عبر الدعم.</p>
    </div>
  </div>

  <button type="submit" class="btn btn-submit w-100 mt-4">
    <i class="fas fa-lock me-2"></i> تأكيد الطلب والدفع
  </button>
</form>
```

  </div>
</div>

<script>
  const cardNumberInput = document.getElementById('card_number');
  const cardHolderInput = document.getElementById('card_holder');
  const cardNumberDisplay = document.getElementById('card-number-display');
  const cardHolderDisplay = document.getElementById('card-holder-display');
  const cardLogo = document.getElementById('card-logo');
  const cardPreview = document.getElementById('card-preview');

  const paymentMethodSelect = document.getElementById('payment_method');
  const cardSection = document.getElementById('card-section');
  const bankSection = document.getElementById('bank-section');
  const stcpaySection = document.getElementById('stcpay-section');

  paymentMethodSelect.addEventListener('change', function() {
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

  cardNumberInput.addEventListener('input', function() {
    let value = cardNumberInput.value.replace(/\D/g, '').substring(0,16);
    let formattedValue = '';
    for (let i = 0; i < value.length; i += 4) {
      formattedValue += value.substring(i, i+4) + ' ';
    }
    cardNumberInput.value = formattedValue.trim();
    cardNumberDisplay.textContent = formattedValue.trim() || '•••• •••• •••• ••••';

    if (value.startsWith('4')) {
      cardLogo.src = 'assets/images/cards/visa.png';
      cardPreview.style.background = 'linear-gradient(135deg, #0056b3, #003580)';
    } else if (value.startsWith('5')) {
      cardLogo.src = 'assets/images/cards/mastercard.png';
      cardPreview.style.background = 'linear-gradient(135deg, #000, #e63946)';
    } else if (value.startsWith('3')) {
      cardLogo.src = 'assets/images/cards/amex.png';
      cardPreview.style.background = 'linear-gradient(135deg, #00b4d8, #0077b6)';
    } else {
      cardLogo.src = 'assets/images/cards/default-card.png';
      cardPreview.style.background = 'linear-gradient(135deg, #aaa, #888)';
    }
  });

  cardHolderInput.addEventListener('input', function() {
    cardHolderDisplay.textContent = cardHolderInput.value || 'اسم حامل البطاقة';
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById('checkout-form').addEventListener('submit', function (e) {
  e.preventDefault();
  var formData = new FormData(this);
  fetch('process_checkout.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    // إغلاق نافذة الدفع
    var checkoutModal = bootstrap.Modal.getInstance(document.getElementById('checkoutModal'));
    checkoutModal.hide();

    // عرض صفحة التأكيد في نافذة منبثقة
    document.getElementById('success-content').innerHTML = data;
    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
  })
  .catch(error => {
    console.error('حدث خطأ أثناء معالجة الدفع:', error);
  });
});
</script>

</body>
</html>
