<?php
session_start();
require_once dirname(__DIR__) . '../includes/db.php';
require_once '../includes/session_protect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$newMessagesCount = $pdo->query("SELECT COUNT(*) FROM messages WHERE status = 'غير مقروء'")->fetchColumn();
$plates = $pdo->query("SELECT * FROM plates")->fetchAll();

$current_user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$current_user_id]);
$userData = $stmt->fetch();
$username = $userData['username'] ?? 'مدير النظام';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>لوحة التحكم - صكوك الحديثة</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Tajawal', sans-serif;
      background-color: #f0f0f5;
      padding-top: 70px;
    }
    .navbar {
      background-color:#3a04e3;
    }
    .navbar .navbar-brand,
    .navbar .nav-link {
      color: white;
      font-weight: bold;
    }
    .navbar .centered-user {
      position: absolute;
      right: 50%;
      transform: translateX(50%);
      color: white;
      font-weight: bold;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .dashboard-container {
      background-color: #fff;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
    .action-icons a {
      margin: 0 12px;
      color: #3a04e3;
      font-size: 16px;
    }
    .action-icons a:hover {
      color: rgb(51, 4, 194);
    }
    .badge-status {
      font-size: 14px;
      padding: 6px 12px;
      border-radius: 30px;
    }

    .modal-confirm {
      color: #636363;
    }
    .modal-confirm .modal-content {
      border-radius: 8px;
      border: none;
    }
    .modal-confirm .modal-header {
      border-bottom: none;
      position: relative;
      justify-content: center;
      background-color: #3a04e3;
      color: white;
    }
    .modal-confirm .modal-body {
      text-align: center;
      font-size: 16px;
      padding: 30px 20px;
    }
    .modal-confirm .modal-footer {
      justify-content: center;
      border: none;
      padding-bottom: 30px;
    }

    .btn-hover {
      transition: all 0.3s ease;
    }

    .btn-hover:hover {
      background-color: rgb(51, 4, 194) !important;
      color: #fff !important;
      border-color: rgb(51, 4, 194) !important;
    }

    .badge-notification {
      position: absolute;
      top: 5px;
      right: -5px;
      background-color: red;
      color: white;
      font-size: 12px;
      padding: 5px 10px;
      border-radius: 50%;
    }

    @media (max-width: 576px) {
      .dashboard-container {
        padding: 15px;
      }
      .navbar .centered-user {
        position: static;
        transform: none;
        margin-top: 10px;
      }
    }

    @media (max-width: 768px) {
      table {
        font-size: 11px;
      }
      .action-icons a {
        font-size: 14px;
        margin: 0 6px;
      }
      .modal-confirm .modal-body {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
  <div class="container-fluid px-4">
    <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt me-1"></i>خروج</a>
    <div class="centered-user">
      <i class="fas fa-user-circle"></i> <?= htmlspecialchars($username); ?>
    </div>

    <a href="messages.php" class="nav-link position-relative">
      <i class="fas fa-envelope"></i> سجل الرسائل
      <?php if ($newMessagesCount > 0): ?>
        <span class="badge-notification"><?= $newMessagesCount; ?></span>
      <?php endif; ?>
    </a>
  </div>
</nav>

<!-- Content -->
<div class="container mt-4">
  <div class="dashboard-container">
    <h1 class="text-center mb-4"><i class="fas fa-th-large me-2"></i> إدارة اللوحات</h1>

    <div class="row mb-3">
      <div class="col-12 col-md-6 mb-2 mb-md-0">
        <a href="add_plate.php" class="btn btn-outline-dark w-100 btn-hover rounded-pill">
          <i class="fas fa-plus me-2"></i> إضافة لوحة جديدة
        </a>
      </div>
      <br>
      <?php if ($_SESSION['role'] == 'admin'): ?>
        <div class="col-12 col-md-6 mb-2 mb-md-0">
          <a href="users.php" class="btn btn-outline-dark w-100 btn-hover rounded-pill">
            <i class="fas fa-users-cog me-2"></i> إدارة المستخدمين
          </a>
        </div>
        <div class="col-12 col-md-6 mt-3">
          <a href="activity_log.php" class="btn btn-outline-dark w-100 btn-hover rounded-pill">
            <i class="fas fa-history me-2"></i> سجل النشاطات
          </a>
        </div>
        <div class="col-12 col-md-6 mt-3">
          <a href="edit_about.php" class="btn btn-outline-dark w-100 btn-hover rounded-pill">
            <i class="fas fa-file-alt me-2"></i> تعديل صفحة من نحن
          </a>
        </div>
      <?php endif; ?>
        <div class="col-12 col-md-6 mt-3">
          <a href="add_service.php" class="btn btn-outline-dark w-100 btn-hover rounded-pill">
          <i class="fa-regular fa-circle-user"></i>  اضافة خدمة جديدة
          </a>
        </div>
        <div class="col-12 col-md-6 mt-3">
          <a href="manage_orders.php" class="btn btn-outline-dark w-100 btn-hover rounded-pill">
          <i class="fa-brands fa-first-order"></i>  إدارة الطلبات
          </a>
        </div>
        <div class="col-12 col-md-6 mt-3">
          <a href="archived_orders.php" class="archive btn btn-outline-dark w-100 btn-hover rounded-pill">
            <i class="fas fa-archive"></i> أرشيف الطلبات
          </a>
        </div>
    </div>

    <table class="table table-bordered table-hover align-middle">
      <thead>
        <tr>
          <th>رقم اللوحة</th>
          <th>السعر</th>
          <th>الحالة</th>
          <th>إجراءات</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($plates as $plate): ?>
          <tr>
            <td><?= $plate['plate_number']; ?></td>
            <td>
              <?php if ($plate['bid_price']): ?>
                💰 <?= intval($plate['bid_price']); ?> ريال<br>
                <?php if ($plate['final_price']): ?>
                  <small class="text-muted">الحد: <?= intval($plate['final_price']); ?> ريال</small>
                <?php endif; ?>
              <?php elseif ($plate['final_price']): ?>
                🔨 الحد: <?= intval($plate['final_price']); ?> ريال
              <?php else: ?>
                <span class="text-muted">لا يوجد سوم</span>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($plate['status'] === 'تم البيع'): ?>
                <span class="badge bg-danger badge-status"><i class="fas fa-times-circle"></i> تم البيع</span>
              <?php elseif ($plate['status'] === 'تحت المزايدة'): ?>
                <span class="badge bg-warning text-dark badge-status"><i class="fas fa-gavel"></i> مزايدة</span>
              <?php else: ?>
                <span class="badge bg-success badge-status"><i class="fas fa-check-circle"></i> متاحة</span>
              <?php endif; ?>
            </td>
            <td class="action-icons">
              <a href="edit_plate.php?id=<?= $plate['id']; ?>" title="تعديل"><i class="fas fa-edit"></i></a>
              <a href="#" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-id="<?= $plate['id']; ?>" class="text-danger delete-btn" title="حذف"><i class="fas fa-trash-alt"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-confirm">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>تأكيد الحذف</h5>
      </div>
      <div class="modal-body">
        هل أنت متأكد أنك تريد حذف هذه اللوحة؟ لا يمكن التراجع بعد الحذف.
      </div>
      <div class="modal-footer">
        <a href="#" class="btn btn-danger" id="confirmDeleteBtn">نعم، احذف</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const modal = document.getElementById('confirmDeleteModal');
  modal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const plateId = button.getAttribute('data-id');
    const confirmBtn = modal.querySelector('#confirmDeleteBtn');
    confirmBtn.href = 'delete_plate.php?id=' + plateId;
  });
</script>

</body>
</html>
