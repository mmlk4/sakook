<?php
session_start();
require_once dirname(path: __DIR__) . '/../../includes/db.php';

$success = '';
if (isset($_SESSION['success'])) {
  $success = $_SESSION['success'];
  unset($_SESSION['success']);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['contact_submit'])) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $message = $_POST['message'];

  $stmt = $pdo->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
  if ($stmt->execute([$name, $email, $message])) {
    $_SESSION['success'] = "✅ تم إرسال رسالتك بنجاح، سنقوم بالرد قريبًا.";
    header("Location: contact.php");
    exit;
  } else {
    $error = "❌ حدث خطأ أثناء الإرسال. حاول لاحقًا.";
  }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>اتصل بنا - صكوك الحديثة</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    html, body {
      height: 100%;
      margin: 0;
    }

    body {
      font-family: 'Tajawal', sans-serif;
      background-color: #f9f9f9;
      padding-top: 80px;
    }

    .page-wrapper {
      display: flex;
      flex-direction: column;
      min-height: 91.5vh;
    }

    .page-content {
      flex: 1;
    }

    .navbar {
      background-color: rgba(255,255,255,0.95);
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .contact-section {
      background: #fff;
      padding: 60px 30px;
      border-radius: 12px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }

    .footer {
      background: #111;
      color: #ccc;
      padding: 30px 0;
      text-align: center;
    }

    h1 {
      color: #3a04e3;
      font-weight: bold;
    }

    .btn-primary {
    background-color: #3a04e3;
    border: none;
    border-radius: 50px;
    padding: 10px 30px;
    font-weight: bold;
    transition: 0.3s;
  }

  .btn-primary:hover {
    background-color:rgb(51, 4, 194);
    transform: scale(1.05);
  }
  </style>
</head>
<body>

<div class="page-wrapper">
  <!-- Navbar -->
  <?php include '../../../includes/navbar.php'; ?>

  <!-- Main Content -->
  <div class="container page-content">
    <div class="contact-section mt-5">
      <h1 class="mb-4"><i class="fas fa-phone"></i> اتصل بنا</h1>

      <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= $success; ?></div>
      <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <input type="text" name="name" class="form-control" placeholder="الاسم" required>
        </div>
        <div class="mb-3">
          <input type="email" name="email" class="form-control" placeholder="البريد الإلكتروني" required>
        </div>
        <div class="mb-3">
          <textarea name="message" class="form-control" rows="4" placeholder="اكتب رسالتك هنا..." required></textarea>
        </div>
        <button name="contact_submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> إرسال</button>

      </form>
    </div>
  </div>

  <!-- Footer -->
  <?php include '../../../includes/footer.php'; ?>
</div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
