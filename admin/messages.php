<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once '../includes/session_protect.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// حذف رسالة
if (isset($_GET['delete'])) {
  $deleteId = $_GET['delete'];
  $pdo->prepare("DELETE FROM messages WHERE id = ?")->execute([$deleteId]);
  header("Location: messages.php");
  exit;
}

$messages = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>سجل الرسائل</title>
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

    .table-container {
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }

  .table th {
  background-color: #3a04e3;
  color: white;
  text-align: center;
}

.table td {
  vertical-align: middle;
  text-align: center;
}

/* 💡 إضافات لتصميم متجاوب */
@media (max-width: 768px) {
  .table thead {
    display: none;
  }

  .table tbody, .table tr, .table td {
    display: block;
    width: 100%;
  }

  .table tr {
    margin-bottom: 15px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 10px;
  }

  .table td {
    text-align: right;
    padding-right: 50%;
    position: relative;
  }

  .table td::before {
    content: attr(data-label);
    position: absolute;
    right: 0;
    width: 45%;
    padding-right: 15px;
    font-weight: bold;
    white-space: nowrap;
  }

  .btn-export {
    width: 100%;
    margin-top: 10px;
  }
}


    .table td {
      vertical-align: middle;
    }

    .btn-delete {
      transition: 0.3s ease;
    }

    .btn-delete:hover {
      transform: scale(1.05);
    }

    .btn-export {
      background-color: #28a745;
      color: white;
    }

    .btn-export:hover {
      background-color: #218838;
      color: white;
    }

    @media (max-width: 430px) {
  .table-container {
    padding: 15px;
  }

  .btn {
    font-size: 14px;
    padding: 6px 12px;
  }

  h2 {
    font-size: 20px;
  }

  .table td {
    padding: 8px;
    font-size: 14px;
  }

  .table td::before {
    font-size: 13px;
  }

  .btn-delete {
    display: inline-block;
    margin-top: 10px;
  }

  .btn-export {
    width: 100%;
    font-size: 14px;
    padding: 10px;
  }

  .table td {
    word-break: break-word;
    white-space: normal;
  }
}
.glass-card {
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border-radius: 20px;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
  transition: 0.3s ease;
  cursor: pointer;
  border: 1px solid rgba(255, 255, 255, 0.25);
}

.glass-card:hover {
  transform: scale(1.02);
  box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
}
h2 {
  color: #3a04e3;
}
  </style>
</head>
<body>

<div class="container table-container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <a href="dashboard.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-right"></i> العودة إلى لوحة التحكم</a>
    <a href="logout.php" class="btn btn-outline-danger"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
  </div>

  <h2 class="mb-4 text-center "><i class="fas fa-envelope-open-text"></i> سجل الرسائل</h2>

  <?php if (count($messages)): ?>
<div class="row g-3">
  <?php foreach ($messages as $msg): ?>
    <div class="col-12 col-md-6">
      <div class="glass-card p-3" onclick="window.location.href='view_message.php?id=<?= $msg['id']; ?>'">
        <h6 class="fw-bold"><i class="fas fa-user me-1 text-secondary"></i> <?= htmlspecialchars($msg['name']); ?></h6>
        <p class="mb-1"><i class="fas fa-envelope me-1 text-secondary"></i> <?= htmlspecialchars($msg['email']); ?></p>
        <p class="mb-2"><?= nl2br(htmlspecialchars($msg['message'])); ?></p>
        <div class="d-flex justify-content-between align-items-center mt-3">
          <small class="text-muted"><?= $msg['created_at']; ?></small>
          <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $msg['id']; ?>" onclick="event.stopPropagation();">
            <i class="fas fa-trash"></i> حذف
          </a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<div class="d-flex justify-content-center mt-4">
  <a href="export_messages.php" class="btn btn-export w-100 text-center" style="max-width: 400px;">
    <i class="fas fa-file-excel me-1"></i> تصدير إلى Excel
  </a>
</div>
  <?php else: ?>
    <p class="text-center text-muted">لا توجد رسائل حالياً.</p>
  <?php endif; ?>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i> تأكيد الحذف</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        هل أنت متأكد أنك تريد حذف هذه الرسالة؟ <br> لا يمكن التراجع بعد الحذف.
      </div>
      <div class="modal-footer justify-content-center">
        <a href="#" id="confirmDeleteBtn" class="btn btn-danger px-4">نعم، حذف</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const modal = document.getElementById('deleteModal');
  modal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const messageId = button.getAttribute('data-id');
    const confirmBtn = modal.querySelector('#confirmDeleteBtn');
    confirmBtn.href = 'messages.php?delete=' + messageId;
  });
</script>

</body>
</html>
