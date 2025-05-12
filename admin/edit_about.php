<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once '../includes/session_protect.php';

// السماح فقط للمسؤول
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.php");
  exit();
}

// تحديث البيانات
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  foreach ($_POST['about'] as $id => $data) {
    $section = $data['section'];
    $content = $data['content'];
    $stmt = $pdo->prepare("UPDATE about_page SET section = ?, content = ? WHERE id = ?");
    $stmt->execute([$section, $content, $id]);
  }
  $success = "✅ تم تحديث جميع الأقسام بنجاح.";
}

// جلب كل الأقسام
$stmt = $pdo->query("SELECT * FROM about_page ORDER BY id");
$rows = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تعديل صفحة من نحن</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link rel="icon" href="/../../assets/images/favicon.png" type="image/png">

<script src="/../../assets/js/tinymce/tinymce.min.js"></script>

  <style>
    body {
      font-family: 'Tajawal', sans-serif;
      padding: 40px;
      background-color: #f4f6f9;
    }

    .container {
      max-width: 950px;
      background: #fff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.07);
    }

    .section-box {
      border: 1px solid #eee;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 30px;
      background-color: #fdfdfd;
    }

    textarea.form-control {
      min-height: 250px;
      resize: vertical;
    }

    .btn-primary {
      background-color: #3a04e3;
      border: none;
    }

    .btn-primary:hover {
      background-color: rgb(51, 4, 194);
      color: white;
    }
  </style>
</head>
<body>

<div class="container">
  <h2 class="text-center text-primary mb-4"><i class="fas fa-edit"></i> تعديل محتوى صفحة "من نحن"</h2>

  <?php if (isset($success)): ?>
    <div class="alert alert-success text-center"><?= $success ?></div>
  <?php endif; ?>

  <form method="POST">
    <?php foreach ($rows as $row): ?>
      <div class="section-box">
        <h5 class="mb-3 text-secondary"><i class="fas fa-tag me-1"></i> <?= htmlspecialchars($row['section']) ?></h5>

        <label class="form-label">عنوان القسم:</label>
        <input type="text" name="about[<?= $row['id'] ?>][section]" class="form-control mb-3" value="<?= htmlspecialchars($row['section']) ?>" required>

        <label class="form-label">المحتوى:</label>
        <textarea name="about[<?= $row['id'] ?>][content]" class="form-control tinymce"><?= htmlspecialchars($row['content']) ?></textarea>
      </div>
    <?php endforeach; ?>

    <div class="text-end">
      <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> حفظ التغييرات</button>
    </div>
  </form>

  <div class="mt-4 text-center">
    <a href="dashboard.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-right"></i> العودة إلى لوحة التحكم</a>
  </div>
</div>

<script>
  tinymce.init({
    selector: '.tinymce',
    directionality: 'rtl',
    language: 'ar',
    height: 300,
    plugins: 'lists link image preview',
    toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image | preview',
    menubar: false
  });
</script>
</body>
</html>
