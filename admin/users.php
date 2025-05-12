<?php
session_start();
require_once dirname(__DIR__) . '../includes/db.php';
require_once '../includes/session_protect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.php");
  exit;
}

$generated_password = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
  $new_username = $_POST['username'];
  $new_role = $_POST['role'];
  $generated_password = bin2hex(random_bytes(4));
  $hashed_password = password_hash($generated_password, PASSWORD_DEFAULT);

  $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
  $stmt->execute([$new_username, $hashed_password, $new_role]);
}

if (isset($_GET['delete'])) {
  $delete_id = $_GET['delete'];
  $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
  $stmt->execute([$delete_id]);
}

$users = $pdo->query("SELECT * FROM users")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إدارة المستخدمين</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link rel="icon" href="/../../assets/images/favicon.png" type="image/png">

  <style>
    body {
      font-family: 'Tajawal', sans-serif;
      background-color: #f4f4f4;
      padding-top: 80px;
    }

    .container {
      max-width: 900px;
    }

    .form-box {
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }

    h2 {
      color: #3a04e3;
      margin-bottom: 25px;
    }

    .btn-purple {
      background-color: #3a04e3;
      color: white;
    }

    .btn-purple:hover {
      background-color: rgb(51, 4, 194);
      color: #fff;
    }

    .password-box {
      background: #eee;
      padding: 10px;
      border-radius: 6px;
      font-weight: bold;
      margin: 15px 0;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .table td, .table th {
      vertical-align: middle;
    }

    .table td a:hover {
      transform: scale(1.05);
      transition: all 0.2s ease-in-out;
    }
    @media (max-width: 768px) {
      .form-box {
        padding: 15px;
      }
      .row.g-3 {
        flex-direction: column;
      }
      .row.g-3 > div {
        width: 100% !important;
      }
      .d-flex.justify-content-between {
        flex-direction: column;
        gap: 10px;
        text-align: center;
      }
    }
    @media (max-width: 430px) {
  .form-box {
    padding: 15px;
  }

  .row.g-3 {
    flex-direction: column;
    gap: 10px;
  }

  .row.g-3 > div {
    width: 100% !important;
  }

  .d-flex.justify-content-between.align-items-center.mb-4 {
    flex-direction: column;
    gap: 10px;
    text-align: center;
  }

  .btn, .form-control, .form-select {
    font-size: 14px;
    padding: 10px 12px;
  }

  .table-responsive {
    overflow-x: auto;
  }

  .password-box {
    flex-direction: column;
    align-items: stretch;
    text-align: center;
  }

  .password-box input {
    width: 100%;
  }

  .table td,
  .table th {
    font-size: 13px;
  }
}

  </style>
</head>
<body>

<div class="container">
  <div class="form-box mt-4">
    <h2><i class="fas fa-users-cog me-2"></i>إدارة المستخدمين</h2>

    <!-- أزرار التنقل -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <a href="dashboard.php" class="btn btn-outline-dark">
        <i class="fas fa-arrow-right"></i> العودة إلى لوحة التحكم
      </a>
      <a href="logout.php" class="btn btn-outline-danger">
        <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
      </a>
    </div>

    <form method="POST" class="row g-3">
      <div class="col-md-5">
        <input type="text" name="username" class="form-control" placeholder="اسم المستخدم" required>
      </div>
      <div class="col-md-4">
        <select name="role" class="form-select" required>
          <option value="employee">موظف</option>
          <option value="admin">مدير</option>
        </select>
      </div>
      <div class="col-md-3 d-grid">
        <button type="submit" name="add_user" class="btn btn-purple">إضافة مستخدم</button>
      </div>
    </form>

    <?php if ($generated_password): ?>
      <div class="password-box mt-3">
        ✅ تم إنشاء المستخدم! كلمة المرور:
        <input type="text" class="form-control w-auto" id="generatedPassword" value="<?= $generated_password; ?>" readonly>
        <button onclick="copyPassword()" class="btn btn-outline-secondary btn-sm">📋 نسخ</button>
      </div>
      <script>
        function copyPassword() {
          var copyText = document.getElementById("generatedPassword");
          copyText.select();
          copyText.setSelectionRange(0, 99999);
          document.execCommand("copy");
          alert("✔ تم نسخ كلمة المرور!");
        }
      </script>
    <?php endif; ?>

    <hr class="my-4">

    <div class="table-responsive">
      <table class="table table-bordered table-hover text-center align-middle bg-white">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>اسم المستخدم</th>
            <th>الدور</th>
            <th>آخر تسجيل دخول</th>
            <th>الإجراء</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
            <tr>
              <td><?= $user['id']; ?></td>
              <td><?= htmlspecialchars($user['username']); ?></td>
              <td>
                <span class="badge <?= $user['role'] === 'admin' ? 'bg-primary' : 'bg-secondary'; ?>">
                  <?= $user['role'] === 'admin' ? 'مدير' : 'موظف'; ?>
                </span>
              </td>
              <td><?= $user['last_login'] ?: '<span class="text-muted">لم يسجل بعد</span>'; ?></td>
              <td>
                <?php if ($user['username'] !== 'admin'): ?>
                  <a href="edit_user.php?id=<?= $user['id']; ?>" class="btn btn-sm btn btn-purple me-2">
                    <i class="fas fa-edit"></i> تعديل
                  </a>
                  <a href="?delete=<?= $user['id']; ?>" onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-trash-alt"></i> حذف
                  </a>
                <?php else: ?>
                  🔒
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
