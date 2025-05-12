<?php
session_start();
require_once dirname(__DIR__) . '../includes/db.php';
require_once '../includes/session_protect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $plate_numbers = $_POST['plate_number'];
  $prices = $_POST['price'];
  $final_prices = $_POST['final_price'];
  $statuses = $_POST['status'];
  $categories = $_POST['category'];
  $images = $_FILES['image'];

  $upload_dir = '../../../uploads/';
  if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
  }

  for ($i = 0; $i < count($plate_numbers); $i++) {
    $plate_number = $plate_numbers[$i];
    $price = !empty($prices[$i]) ? $prices[$i] : null;
    $final_price = !empty($final_prices[$i]) ? $final_prices[$i] : null;
    $status = $statuses[$i];
    $category = $categories[$i];

    $image_name = time() . '_' . basename($images['name'][$i]);
    $target_path = $upload_dir . $image_name;

    if (move_uploaded_file($images['tmp_name'][$i], $target_path)) {
      $stmt = $pdo->prepare("INSERT INTO plates (plate_number, bid_price, final_price, status, image, category) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->execute([$plate_number, $price, $final_price, $status, $image_name, $category]);
      
      $plateId = $pdo->lastInsertId();
    
      // 🟢 تسجيل السجل مع رقم اللوحة
      $log = $pdo->prepare("INSERT INTO activity_logs (user_id, action, plate_id, plate_number) VALUES (?, ?, ?, ?)");
      $log->execute([$_SESSION['user_id'], 'إضافة لوحة جديدة', $plateId, $plate_number]);
    }
    
  }

  echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
      var successModal = new bootstrap.Modal(document.getElementById('successModal'));
      successModal.show();
    });
  </script>";
}
?>



<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إضافة لوحة جديدة</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Tajawal', sans-serif;
      background-color: #f8f9fa;
      padding-top: 60px;
    }
    .form-container {
      max-width: 800px;
      margin: auto;
      background: #fff;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    h2.form-title {
      text-align: center;
      color: #3a04e3;
      font-weight: bold;
      margin-bottom: 30px;
    }
    .form-label {
      font-weight: bold;
    }
    .form-control, .form-select {
      border-radius: 12px;
    }
    .btn-submit {
      background-color:#3a04e3;
      color: white;
      padding: 10px 30px;
      font-weight: bold;
      border-radius: 50px;
    }
    .btn-submit:hover {
      background-color: rgb(51, 4, 194);
    }
    .btn-add {
      background-color: #eee;
      border-radius: 12px;
      padding: 6px 16px;
      font-weight: bold;
      margin-top: 15px;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="form-container">
    <h2 class="form-title"><i class="fas fa-plus-circle me-2"></i> إضافة لوحات</h2>

    <form method="POST" enctype="multipart/form-data">
      <div id="plates-container">
        <div class="plate-entry border p-3 rounded-4 shadow-sm mb-4 position-relative">
          <div class="mb-3">
            <label class="form-label">رقم اللوحة:</label>
            <input type="text" class="form-control" name="plate_number[]" required>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">السوم:</label>
              <input type="number" class="form-control" name="price[]" >
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label"> الحد :</label>
              <input type="number" class="form-control" name="final_price[]" step="0.01">
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">الحالة:</label>
              <select class="form-select" name="status[]" required>
                <option value="متاحة">متاحة</option>
                <option value="تم البيع">تم البيع</option>
                <option value="تحت المزايدة">تحت المزايدة</option>
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">التصنيف:</label>
            <select class="form-select" name="category[]" required>
              <option value="">اختر تصنيف اللوحة</option>
              <option value="لوحه أحرف وأرقام متشابهة">أحرف وأرقام متشابهة</option>
              <option value="لوحه مميزه الرقم واحد">الرقم واحد</option>
              <option value="لوحه سيارة الرقم فردي">الرقم فردي</option>
              <option value="لوحه سيارة رقمين">رقمين</option>
              <option value="لوحه سيارة ثلاث أرقام">ثلاث أرقام</option>
              <option value="لوحه سيارة أربع أرقام">أربع أرقام</option>
              <option value="لوحه مميزه نقل خاص">نقل خاص</option>
              <option value="لوحه مميزه دراجة ناريه">دراجة نارية</option>
              <option value="لوحة مميزة">مميزة</option>
              <option value="لوحة نادرة">نادرة</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">صورة اللوحة:</label>
            <input type="file" class="form-control image-input" name="image[]" accept="image/*" required>
            <img src="" class="img-preview mt-2 d-none" style="max-height: 120px; border-radius: 8px;">
          </div>
          <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-plate-btn d-none" aria-label="Close"></button>
        </div>
      </div>

      <div class="text-center">
        <button type="button" class="btn btn-add" id="add-plate-btn"><i class="fas fa-plus"></i> إضافة لوحة أخرى</button>
      </div>

      <div class="text-center mt-4">
        <button type="submit" class="btn btn-submit"><i class="fas fa-save me-2"></i> رفع اللوحات</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal للنجاح -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center">
      <div class="modal-header border-0">
        <h5 class="modal-title w-100 text-success" id="successModalLabel">✅ تمت الإضافة بنجاح</h5>
      </div>
      <div class="modal-body">
        <p>تم رفع اللوحات بنجاح! 🎉</p>
        <a href="dashboard.php" class="btn btn-primary">العودة للوحة التحكم</a>
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById('add-plate-btn').addEventListener('click', function () {
    const container = document.getElementById('plates-container');
    const entry = container.querySelector('.plate-entry');
    const newEntry = entry.cloneNode(true);

    newEntry.querySelectorAll('input, select').forEach(el => {
      el.value = '';
    });

    container.appendChild(newEntry);
  });
</script>
<script>
  const container = document.getElementById('plates-container');
  const baseEntry = container.querySelector('.plate-entry');

  document.getElementById('add-plate-btn').addEventListener('click', function () {
    const newEntry = baseEntry.cloneNode(true);

    // تفريغ جميع الحقول
    newEntry.querySelectorAll('input[type="text"], input[type="number"]').forEach(input => input.value = '');
    newEntry.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
    newEntry.querySelector('input[type="file"]').value = '';

    // إخفاء المعاينة
    const previewImg = newEntry.querySelector('.img-preview');
    previewImg.classList.add('d-none');
    previewImg.src = '';

    // إظهار زر الحذف للمدخل الجديد فقط
    newEntry.querySelector('.remove-plate-btn').classList.remove('d-none');

    // إضافة النموذج الجديد
    container.appendChild(newEntry);
  });

  // معاينة الصورة
  document.addEventListener('change', function (e) {
    if (e.target.classList.contains('image-input')) {
      const fileInput = e.target;
      const previewImg = fileInput.closest('.mb-3').querySelector('.img-preview');
      const file = fileInput.files[0];
      if (file && previewImg) {
        previewImg.src = URL.createObjectURL(file);
        previewImg.classList.remove('d-none');
      }
    }
  });

  // حذف النموذج
  document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-plate-btn')) {
      const plateEntries = document.querySelectorAll('.plate-entry');
      if (plateEntries.length > 1) {
        e.target.closest('.plate-entry').remove();
      }
    }
  });
</script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
