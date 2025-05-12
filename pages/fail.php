<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>فشل الدفع</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      font-family: 'Tajawal', sans-serif;
      background: linear-gradient(135deg, #f0f2f5, #ffffff);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 30px;
    }
    .fail-box {
      background: #fff;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
      text-align: center;
      max-width: 500px;
      width: 100%;
    }
    .fail-box i {
      font-size: 80px;
      color: #dc3545;
      margin-bottom: 20px;
    }
    .fail-box h2 {
      color: #dc3545;
      font-weight: bold;
      margin-bottom: 15px;
    }
    .fail-details {
      margin-top: 20px;
      font-size: 16px;
      color: #555;
    }
    .btn-try-again {
      background-color: #3a04e3;
      color: white;
      border-radius: 30px;
      padding: 10px 30px;
      margin-top: 25px;
      font-weight: bold;
    }
    .btn-try-again:hover {
      background-color: rgb(51, 4, 194);
    }
  </style>
</head>
<body>

<div class="fail-box">
  <i class="fas fa-times-circle"></i>
  <h2>فشل الدفع ❌</h2>
  <p>عذراً، حدثت مشكلة أثناء معالجة الدفع الخاص بك.</p>

  <div class="fail-details">
    <p>يرجى التحقق من بيانات البطاقة أو اختيار طريقة دفع أخرى.</p>
  </div>

  <a href="checkout.php" class="btn btn-try-again">إعادة المحاولة</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
