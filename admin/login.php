<?php
session_start();
require_once dirname(__DIR__) . '../includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->execute([$username]);
  $user = $stmt->fetch();

  if ($user && password_verify($password, $user['password'])) {
    if ($user['active'] != 1) {
      $error = "❌ حسابك غير مفعل. يرجى التواصل مع الإدارة.";
    } else {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['role'] = $user['role'];

      $update = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
      $update->execute([$user['id']]);

      header("Location: dashboard.php");
      exit;
    }
  } else {
    $error = 'اسم المستخدم أو كلمة المرور غير صحيحة.';
  }
}
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تسجيل الدخول</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap & Font Awesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
  <link rel="icon" href="/../../assets/images/favicon.png" type="image/png">


  <style>
    body {
      background: linear-gradient(to right, #3a04e3,rgb(30, 22, 47));
      font-family: 'Tajawal', sans-serif;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-box {
      background: #ffffffc4;
      padding: 40px;
      border-radius: 23px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }

    .login-box h2 {
      color: #3a04e3;
      margin-bottom: 25px;
      font-weight: bold;
      text-align: center;
    }

    .form-control {
      border-radius: 20px;
      margin-bottom: 15px;
      background-color: #ffffff63 !important;
    }

    .btn-login {
      background-color: #3a04e3;
      color: white;
      width: 100%;
      border-radius: 8px;
      font-weight: bold;
    }

    .btn-login:hover {
      background-color: rgb(51, 4, 194);
    }

    .login-icon {
      font-size: 40px;
      color: #3a04e3;
      display: block;
      text-align: center;
      margin-bottom: 10px;
    }

    .error-message {
      color: red;
      font-size: 14px;
      text-align: center;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

<div class="login-box">
  <i class="fas fa-user-shield login-icon"></i>
  <h2>تسجيل الدخول</h2>
  
  <?php if (isset($_GET['timeout'])): ?>
    <p class="error-message" style="color:rgb(214, 3, 3);">تم تسجيل الخروج تلقائيًا بسبب عدم النشاط.</p>
  <?php elseif ($error): ?>
    <p class="error-message"><?php echo $error; ?></p>
  <?php endif; ?>

  <form method="POST">
    <input type="text" name="username" class="form-control" placeholder="اسم المستخدم" required>
    <input type="password" name="password" class="form-control" placeholder="كلمة المرور" required>
    <button type="submit" class="btn btn-login mt-2">دخول</button>
  </form>
</div>

</body>
</html>
