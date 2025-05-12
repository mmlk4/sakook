<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once '../includes/session_protect.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM plates WHERE id = ?");
$stmt->execute([$id]);
$plate = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $plate_number = $_POST['plate_number'];
  $bid_price = !empty($_POST['bid_price']) ? $_POST['bid_price'] : null;
  $final_price = !empty($_POST['final_price']) ? $_POST['final_price'] : null;
  $status = $_POST['status'];

  $stmt = $pdo->prepare("UPDATE plates SET plate_number=?, bid_price=?, final_price=?, status=? WHERE id=?");
  $stmt->execute([$plate_number, $bid_price, $final_price, $status, $id]);

  $log = $pdo->prepare("INSERT INTO activity_logs (user_id, action, plate_id, plate_number) VALUES (?, ?, ?, ?)");
  $log->execute([$_SESSION['user_id'], 'تعديل لوحة', $id, $plate_number]);
  

  header("Location: dashboard.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تعديل لوحة</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap + Font Awesome + Google Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Tajawal', sans-serif;
      background-color: #f8f9fa;
      padding: 40px;
    }

    .form-container {
      max-width: 600px;
      margin: auto;
      background: #ffffff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .form-title {
      color: #3a04e3;
      text-align: center;
      margin-bottom: 25px;
      font-weight: bold;
    }

    .btn-submit {
      background-color: #3a04e3;
      color: white;
    }

    .btn-submit:hover {
      background-color: rgb(51, 4, 194);
    }

    label {
      font-weight: bold;
      margin-top: 10px;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2 class="form-title"><i class="fas fa-edit"></i> تعديل بيانات اللوحة</h2>

  <form method="POST">
    <div class="mb-3">
      <label for="plate_number">رقم اللوحة:</label>
      <input type="text" class="form-control" name="plate_number" id="plate_number" value="<?= $plate['plate_number']; ?>" required>
    </div>

    <div class="mb-3">
      <label for="bid_price">السوم :</label>
      <input type="number" class="form-control" name="bid_price" id="bid_price" value="<?= $plate['bid_price']; ?>">
    </div>

    <div class="mb-3">
      <label for="final_price"> الحد :</label>
      <input type="number" class="form-control" name="final_price" id="final_price" value="<?= $plate['final_price']; ?>">
    </div>

    <div class="mb-3">
      <label for="status">الحالة:</label>
      <select class="form-select" name="status" id="status" required>
        <option value="متاحة" <?= $plate['status'] == 'متاحة' ? 'selected' : ''; ?>>متاحة</option>
        <option value="تم البيع" <?= $plate['status'] == 'تم البيع' ? 'selected' : ''; ?>>تم البيع</option>
        <option value="تحت المزايدة" <?= $plate['status'] == 'تحت المزايدة' ? 'selected' : ''; ?>>تحت المزايدة</option>
      </select>
    </div>

    <div class="d-grid">
      <button type="submit" class="btn btn-submit"><i class="fas fa-save"></i> حفظ التعديلات</button>
    </div>
  </form>
</div>

</body>
</html>
