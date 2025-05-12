<?php
session_start();
require_once dirname(__DIR__) . '../includes/db.php';
require_once '../includes/session_protect.php';

// التحقق من أن المستخدم مسجل دخوله ويملك صلاحية "admin"
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// جلب بيانات السجل مع ربطها بأسماء المستخدمين وأرقام اللوحات
$stmt = $pdo->prepare("
    SELECT logs.*, users.username, plates.plate_number
    FROM activity_logs logs
    LEFT JOIN users ON logs.user_id = users.id
    LEFT JOIN plates ON logs.plate_id = plates.id
    ORDER BY logs.timestamp DESC
");
$stmt->execute();
$logs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>سجل النشاطات</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Tajawal', sans-serif;
      background-color: #f8f9fa;
      padding-top: 60px;
    }
    .container {
      max-width: 1000px;
      margin: auto;
    }
    h2 {
      color: #3a04e3;
      margin-bottom: 30px;
      text-align: center;
    }
    table th {
      background-color: #3a04e3;
      color: white;
      text-align: center;
    }
    table td {
      text-align: center;
      vertical-align: middle;
    }
  </style>
</head>
<body>

<div class="container">
  <h2><i class="fas fa-history me-2"></i> سجل النشاطات</h2>

  <table class="table table-bordered table-hover align-middle">
    <thead>
      <tr>
        <th>المستخدم</th>
        <th>الإجراء</th>
        <th>رقم اللوحة</th>
        <th>التاريخ والوقت</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($logs as $log): ?>
        <tr>
          <td><?= htmlspecialchars($log['username'] ?? 'غير معروف'); ?></td>
          <td><?= htmlspecialchars($log['action']); ?></td>
          <td><?= htmlspecialchars($log['plate_number'] ?? '—'); ?></td>
          <td><?= htmlspecialchars($log['timestamp']); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
