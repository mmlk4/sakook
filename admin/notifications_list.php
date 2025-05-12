<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once '../includes/session_protect.php';

// التحقق من الصلاحيات
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'employee')) {
    header("Location: login.php");
    exit();
}

// جلب التنبيهات الغير مقروءة
$stmt = $pdo->query("SELECT * FROM notifications WHERE status = 'unread' ORDER BY created_at DESC");
$notifications = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>التنبيهات الجديدة</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  
  <style>
    body {
      font-family: 'Tajawal', sans-serif;
      background: #f8f9fa;
      padding-top: 80px;
      min-height: 100vh;
    }
    .container {
      max-width: 1000px;
    }
    .page-title {
      text-align: center;
      color: #3a04e3;
      font-weight: bold;
      margin-bottom: 30px;
    }
    .notification-card {
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      margin-bottom: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.05);
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: all 0.3s ease-in-out;
    }
    .notification-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    .notification-info {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .notification-info i {
      font-size: 30px;
      color: #3a04e3;
    }
    .notification-text {
      display: flex;
      flex-direction: column;
    }
    .notification-text strong {
      font-size: 18px;
    }
    .notification-text small {
      color: #888;
    }
    .mark-read-btn {
      background-color: #28a745;
      color: white;
      font-weight: bold;
      padding: 8px 20px;
      border: none;
      border-radius: 30px;
      transition: background 0.3s;
    }
    .mark-read-btn:hover {
      background-color: #218838;
    }
    .no-notifications {
      text-align: center;
      margin-top: 50px;
      font-size: 18px;
      color: #888;
    }
  </style>
</head>

<body>

<div class="container">
  <h2 class="page-title"><i class="fas fa-bell"></i> التنبيهات الجديدة</h2>

  <?php if ($notifications): ?>
    <?php foreach ($notifications as $notif): ?>
      <div class="notification-card" id="notif-<?= $notif['id']; ?>">
        <div class="notification-info">
          <i class="fas fa-bell"></i>
          <div class="notification-text">
          <strong><?= htmlspecialchars($notif['message']); ?></strong>
          <small><?= date('Y-m-d H:i', strtotime($notif['created_at'])); ?></small>
          </div>
        </div>
        <button class="mark-read-btn" onclick="markAsRead(<?= $notif['id']; ?>)">تم الاطلاع ✅</button>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="no-notifications">
      لا توجد تنبيهات جديدة 📭
    </div>
  <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function markAsRead(id) {
  $.post('mark_notification_read.php', { id: id }, function(response) {
    if (response.status === 'success') {
      $('#notif-' + id).fadeOut(500, function() {
        $(this).remove();

        // نقص العدد الظاهر
        var notifCount = parseInt($('#notif-count').text());
        if (!isNaN(notifCount) && notifCount > 0) {
          notifCount--;
          if (notifCount > 0) {
            $('#notif-count').text(notifCount);
          } else {
            $('#notif-count').fadeOut();
          }
        }

        // لو ما بقي أي تنبيه
        if ($('.notification-card').length === 0) {
          $('.container').append('<div class="no-notifications">لا توجد تنبيهات جديدة 📭</div>');
        }
      });
    }
  }, 'json');
}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
