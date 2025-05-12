<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once '../includes/session_protect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "معرف الرسالة غير موجود.";
    exit();
}

$message_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM messages WHERE id = ?");
$stmt->execute([$message_id]);
$message = $stmt->fetch();

if (!$message) {
    echo "لم يتم العثور على الرسالة.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تفاصيل الرسالة</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link rel="icon" href="/../../assets/images/favicon.png" type="image/png">

  <style>
    body {
      font-family: 'Tajawal', sans-serif;
      background-color: #f4f6f9;
      padding: 40px;
    }

    .message-box {
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
      max-width: 800px;
      margin: auto;
      transition: 0.3s ease;
    }

    .message-box:hover {
      transform: translateY(-3px);
    }
    .message-header h2 {
      color: #3a04e3;
    }

    .message-info i {
      color: #3a04e3;
      margin-left: 5px;
    }

    .message-content {
      background: rgba(255, 255, 255, 0.4);
      backdrop-filter: blur(5px);
      -webkit-backdrop-filter: blur(5px);
      padding: 20px;
      border-radius: 12px;
      margin-top: 20px;
      white-space: pre-wrap;
      box-shadow: inset 0 0 8px rgba(0,0,0,0.05);

    }

    .btn-purple {
      background-color: #3a04e3;
      color: white;
    }

    .btn-purple:hover {
      background-color: rgb(51, 4, 194);
      color: white;
    }

    .btn-outline-purple {
      border-color: #3a04e3;
      color: #3a04e3;
    }

    .btn-outline-purple:hover {
      background-color: rgb(51, 4, 194);
      color: white;
    }

    @media (max-width: 768px) {
  .message-box {
    padding: 20px;
  }

  .message-info p {
    font-size: 15px;
  }

  .btn {
    font-size: 14px;
    padding: 8px 16px;
  }
}

@media (max-width: 430px) {
  .message-box {
    padding: 15px;
  }

  .message-header h2 {
    font-size: 20px;
  }

  .message-info p {
    font-size: 14px;
  }

  .message-content {
    font-size: 14px;
    padding: 15px;
  }

  .d-flex.justify-content-between {
    flex-direction: column;
    gap: 12px;
  }

  .btn {
    width: 100%;
  }
}

  </style>
</head>
<body>

<div class="message-box">
  <div class="message-header mb-4 text-center">
    <h2><i class="fas fa-envelope-open-text"></i> تفاصيل الرسالة</h2>
  </div>

  <div class="message-info mb-3">
    <p><i class="fas fa-user"></i> <strong>المرسل:</strong> <?= htmlspecialchars($message['name']); ?></p>
    <p><i class="fas fa-envelope"></i> <strong>البريد:</strong> <a href="mailto:<?= htmlspecialchars($message['email']); ?>"><?= htmlspecialchars($message['email']); ?></a></p>
    <p><i class="fas fa-clock"></i> <strong>التاريخ:</strong> <?= $message['created_at']; ?></p>
  </div>

  <div class="message-content">
    <?= nl2br(htmlspecialchars($message['message'])); ?>
  </div>

  <div class="d-flex justify-content-between mt-4">
    <a href="messages.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-right"></i> العودة لسجل الرسائل</a>
    <a href="mailto:<?= htmlspecialchars($message['email']); ?>" class="btn btn-purple"><i class="fas fa-reply"></i> الرد على الرسالة</a>
  </div>
</div>

</body>
</html>
