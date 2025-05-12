<?php
session_start();
require_once dirname(__DIR__) . '../includes/db.php';
require_once '../includes/session_protect.php';

// تحقق من الصلاحيات
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.php");
  exit;
}

// جلب بيانات المستخدم
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
  die("المستخدم غير موجود");
}

$generated_password = '';

// تحديث البيانات
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
  $username = $_POST['username'];
  $role = $_POST['role'];
  $active = isset($_POST['active']) ? 1 : 0;

  $update = $pdo->prepare("UPDATE users SET username = ?, role = ?, active = ? WHERE id = ?");
  $update->execute([$username, $role, $active, $id]);

  header("Location: users.php");
  exit;
}

// إعادة ضبط كلمة المرور
if (isset($_POST['reset_password'])) {
  $generated_password = bin2hex(random_bytes(4));
  $hashed = password_hash($generated_password, PASSWORD_DEFAULT);
  $pdo->prepare("UPDATE users SET password = ? WHERE id = ?")->execute([$hashed, $id]);
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تعديل المستخدم</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link rel="icon" href="../assets/images/favicon.png" type="image/png">

  <style>
    body {
      font-family: 'Tajawal', sans-serif;
      background-color: #f4f4f4;
      padding-top: 80px;
    }

    .container {
      max-width: 600px;
    }

    .box {
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.05);
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
  </style>
</head>
<body>

<div class="container">
  <div class="box mt-4">
    <h3><i class="fas fa-user-edit me-2"></i>تعديل بيانات المستخدم</h3>

    <form method="POST" class="mt-3">
      <div class="mb-3">
        <label>اسم المستخدم</label>
        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']); ?>" required>
      </div>

      <div class="mb-3">
        <label>الدور</label>
        <select name="role" class="form-select">
          <option value="employee" <?= $user['role'] === 'employee' ? 'selected' : '' ?>>موظف</option>
          <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>مدير</option>
        </select>
      </div>

      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="active" id="active" <?= $user['active'] ? 'checked' : '' ?>>
        <label class="form-check-label" for="active">مستخدم مفعل</label>
      </div>

      <div class="d-grid gap-2">
        <button name="update_user" class="btn btn-purple"><i class="fas fa-save me-1"></i> حفظ التغييرات</button>
      </div>
    </form>

    <hr class="my-4">

    <form method="POST">
      <button name="reset_password" class="btn btn-outline-danger"><i class="fas fa-key me-1"></i> إعادة ضبط كلمة المرور</button>
    </form>

    <?php if ($generated_password): ?>
      <div class="password-box mt-3">
        كلمة المرور الجديدة:
        <input type="text" class="form-control w-auto" id="generatedPassword" value="<?= $generated_password; ?>" readonly>
        <button onclick="copyPassword()" class="btn btn-outline-secondary btn-sm">📋 نسخ</button>
      </div>

      <script>
        function copyPassword() {
          const input = document.getElementById("generatedPassword");
          input.select();
          input.setSelectionRange(0, 99999);
          document.execCommand("copy");
          alert("✔ تم نسخ كلمة المرور!");
        }
      </script>
    <?php endif; ?>

    <div class="text-end mt-3">
      <a href="users.php" class="btn btn-sm btn-secondary">⬅ العودة</a>
    </div>
  </div>
</div>

</body>
</html>
