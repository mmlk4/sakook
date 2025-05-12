<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once '../includes/session_protect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $names = $_POST['name'];
  $prices = $_POST['price'];
  $descriptions = $_POST['description'];
  $images = $_FILES['image'];

  $upload_dir = '../../../uploads/services/';
  if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
  }

  for ($i = 0; $i < count($names); $i++) {
    $name = trim($names[$i]);
    $price = floatval($prices[$i]);
    $description = trim($descriptions[$i]);

    // التحقق من رفع صورة صالحة
    if (!empty($images['name'][$i])) {
      $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'];
      $mime_type = mime_content_type($images['tmp_name'][$i]);

      if (in_array($mime_type, $allowed_types)) {
        $ext = pathinfo($images['name'][$i], PATHINFO_EXTENSION);
        $safe_filename = uniqid('service_', true) . '.' . $ext;
        $target_path = $upload_dir . $safe_filename;

        if (move_uploaded_file($images['tmp_name'][$i], $target_path)) {
          $stmt = $pdo->prepare("INSERT INTO services (name, price, description, image) VALUES (?, ?, ?, ?)");
          $stmt->execute([$name, $price, $description, $safe_filename]);
        }
      }
    }
  }

  echo "<script>alert('✅ تم إضافة الخدمات بنجاح'); window.location.href = 'services_list.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إضافة خدمات جديدة</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Tajawal', sans-serif;
      background-color: #f8f9fa;
      padding-top: 60px;
    }
    .form-container {
      max-width: 900px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .btn-add {
      background-color: #eee;
      border-radius: 12px;
      margin-top: 20px;
      padding: 8px 16px;
      font-weight: bold;
    }
    .btn-submit {
      background-color: #3a04e3;
      color: white;
      border-radius: 50px;
      font-weight: bold;
      margin-top: 20px;
    }
    .btn-submit:hover {
      background-color: rgb(51, 4, 194);
    }
    .remove-service-btn {
      cursor: pointer;
      color: red;
      font-size: 18px;
      position: absolute;
      top: 10px;
      left: 10px;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2 class="text-center mb-4"><i class="fas fa-concierge-bell me-2"></i> إضافة خدمات</h2>

  <form method="POST" enctype="multipart/form-data" id="servicesForm">
    <div id="services-container">
      <div class="service-entry border p-3 mb-4 rounded-4 shadow-sm position-relative">
        <div class="mb-3">
          <label>اسم الخدمة:</label>
          <input type="text" class="form-control" name="name[]" required>
        </div>
        <div class="mb-3">
          <label>سعر الخدمة:</label>
          <input type="number" step="0.01" class="form-control" name="price[]" required>
        </div>
        <div class="mb-3">
          <label>وصف الخدمة:</label>
          <textarea class="form-control" name="description[]" rows="3" required></textarea>
        </div>
        <div class="mb-3">
          <label>صورة الخدمة:</label>
          <input type="file" class="form-control" name="image[]" accept=".jpg,.jpeg,.png,.webp,.svg" required>
        </div>
      </div>
    </div>

    <div class="text-center">
      <button type="button" id="add-service-btn" class="btn btn-add">
        <i class="fas fa-plus"></i> إضافة خدمة أخرى
      </button>
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-submit">
        <i class="fas fa-save me-2"></i> حفظ الخدمات
      </button>
    </div>
  </form>
</div>

<script>
  const container = document.getElementById('services-container');
  const baseEntry = container.querySelector('.service-entry');

  document.getElementById('add-service-btn').addEventListener('click', function () {
    const newEntry = baseEntry.cloneNode(true);

    newEntry.querySelectorAll('input, textarea').forEach(el => el.value = '');
    newEntry.querySelector('.remove-service-btn')?.remove();

    const removeBtn = document.createElement('span');
    removeBtn.className = 'remove-service-btn';
    removeBtn.innerHTML = '<i class="fas fa-times-circle"></i>';
    removeBtn.addEventListener('click', function () {
      newEntry.remove();
    });

    newEntry.appendChild(removeBtn);
    container.appendChild(newEntry);
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
