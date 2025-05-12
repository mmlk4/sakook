  <?php include __DIR__ . '/../../includes/navbar.php'; ?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>404 - الصفحة غير موجودة</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link rel="icon" href="/../../assets/images/favicon.png" type="image/png">

  <style>
    body {
      font-family: 'Tajawal', sans-serif;
      background-color: #f8f9fa;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .error-container {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 40px 20px;
    }

    .error-box {
      background-color: #fff;
      padding: 40px 30px;
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.1);
      max-width: 600px;
      width: 100%;
    }

    .glitch {
      font-size: 100px;
      font-weight: bold;
      color: #3a04e3;
      position: relative;
      display: inline-block;
      width: fit-content;
      margin: auto;
    }

    .glitch::before,
    .glitch::after {
      content: '404';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      opacity: 0.8;
      color: #3a04e3;
    }

    .glitch::before {
      color: #ff00c8;
      z-index: -1;
      animation: glitchTop 1s infinite linear alternate-reverse;
    }

    .glitch::after {
      color: #00ffff;
      z-index: -1;
      animation: glitchBottom 1s infinite linear alternate-reverse;
    }

    @keyframes glitchTop {
      0% { transform: translate(0, 0); }
      20% { transform: translate(-2px, -2px); }
      40% { transform: translate(2px, 2px); }
      60% { transform: translate(-1px, 1px); }
      80% { transform: translate(1px, -1px); }
      100% { transform: translate(0, 0); }
    }

    @keyframes glitchBottom {
      0% { transform: translate(0, 0); }
      20% { transform: translate(2px, 2px); }
      40% { transform: translate(-2px, -2px); }
      60% { transform: translate(1px, -1px); }
      80% { transform: translate(-1px, 1px); }
      100% { transform: translate(0, 0); }
    }

    .error-message {
      font-size: 20px;
      margin: 20px 0;
      color: #333;
    }

    .btn-home {
      background-color: #3a04e3;
      color: white;
      border-radius: 50px;
      padding: 10px 25px;
      text-decoration: none;
      font-weight: bold;
      transition: 0.3s ease;
    }

    .btn-home:hover {
      background-color:rgb(51, 4, 194);
    }

    @media (max-width: 576px) {
      .glitch {
        font-size: 70px;
      }

      .error-message {
        font-size: 18px;
      }
    }
  </style>
</head>
<body>

<div class="error-container">
  <div class="error-box">
    <div class="glitch">400</div>
    <div class="error-message">طلب غير صالح. يرجى التحقق والمحاولة مرة أخرى.</div>
    <a href="index.php" class="btn-home"><i class="fas fa-home me-2"></i> العودة إلى الرئيسية</a>
  </div>
</div>

  <?php include __DIR__ . '/../../includes/footer.php'; ?>
</body>
</html>
