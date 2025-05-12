<?php
session_start();
require_once dirname(__DIR__) . '/../../includes/db.php';
require_once dirname(__DIR__) . '/../../includes/session_protect.php';

$id = $_GET['id'];
$plate = $pdo->prepare("SELECT * FROM plates WHERE id = ?");
$plate->execute([$id]);
$plate = $plate->fetch();

$similar = $pdo->prepare("SELECT * FROM plates WHERE id != ? ORDER BY RAND() LIMIT 4");
$similar->execute([$id]);
$similarPlates = $similar->fetchAll();

$success = '';
if (isset($_SESSION['success'])) {
  $success = $_SESSION['success'];
  unset($_SESSION['success']);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['contact_submit'])) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $message = $_POST['message'];
  $stmt = $pdo->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
  if ($stmt->execute([$name, $email, $message])) {
    $_SESSION['success'] = "✅ تم إرسال رسالتك بنجاح، سيتم التواصل معك قريبًا.";
    header("Location: view_plate.php?id=" . $id);
    exit;
  } else {
    $error = "❌ حدث خطأ أثناء الإرسال. حاول لاحقًا.";
  }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تفاصيل اللوحة</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Tajawal', sans-serif;
      background-color: #f9f9f9;
      padding-top: 90px;
      background: linear-gradient(to right top, #f1f0ff, #faf6ff, #fff);
      background-attachment: fixed;
      background: url('../../../assets/images/Untitled-2.jpg') repeat;
      background-size: contain;
      background-attachment: fixed;

    }

    .back-btn {
      position: fixed;
      top: 85px;
      right: 25px;
      z-index: 999;
      background: #3a04e3;
      color: white;
      border-radius: 50px;
      padding: 8px 18px;
      text-decoration: none;
      font-size: 14px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.15);
      transition: background 0.3s ease;
    }

    .back-btn:hover {
      background:rgb(51, 4, 194);
    }

    .plate-details {
      background: #fff;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
      margin-bottom: 40px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
      }

    .plate-details img {
      border-radius: 12px;
      max-height: 300px;
      object-fit: cover;
    }

  .form-box {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: transform 0.3s ease;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);

}

    .similar-card img {
      height: 130px;
      object-fit: cover;
      border-radius: 8px 8px 0 0;
    }

    .similar-card .card-body {
      padding: 10px;
    }

  .form-control {
    background-color: rgba(255, 255, 255, 0.6);
    border: 1px solid #ddd;
    border-radius: 10px;
    transition: all 0.3s ease;
  }

  .form-control:focus {
    background-color: #fff;
    border-color: #3a04e3;
    box-shadow: 0 0 10px rgba(108, 43, 217, 0.2);
  }

    .section-title {
      color: #3a04e3;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .badge-status {
      padding: 8px 12px;
      font-size: 14px;
      border-radius: 30px;
    }

    .footer {
      background-color: #111;
      color: #ccc;
      padding: 20px 0;
      text-align: center;
      margin-top: 80px;
    }

  .similar-card {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .similar-card .card-body {
    padding: 15px;
}

  .similar-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
  }

  .similar-card img {
  height: 100px;
  object-fit: contain;
  width: 100%;
  padding: 10px;
  background-color: #fff;
}


  .btn-outline-primary:hover {
    background-color: rgb(51, 4, 194);
    color: white;
    border-color:rgb(51, 4, 194);
    transition: 0.3s;
}

  .btn-primary {
    background-color: #3a04e3;
    border: none;
    border-radius: 50px;
    padding: 10px 30px;
    font-weight: bold;
    transition: 0.3s;
  }

  .btn-primary:hover {
    background-color: rgb(51, 4, 194);
    transform: scale(1.05);
  }

  @media (max-width: 768px) {
  .plate-details {
    padding: 25px;
    margin-bottom: 30px;
  }

  .plate-details img {
    max-height: 250px;
    margin-bottom: 20px;
  }

  .section-title {
    font-size: 20px;
    text-align: center;
  }

  .form-box {
    padding: 25px;
    border-radius: 16px;
  }

  .btn-primary {
    width: 100%;
    font-size: 16px;
    padding: 10px 0;
  }

  .form-control {
    font-size: 15px;
    padding: 10px;
  }

  .similar-card img {
    height: 120px;
  }

  .similar-card .card-body {
    padding: 12px;
  }

  .badge-status {
    font-size: 13px;
  }

  .back-btn {
    font-size: 13px;
    padding: 6px 14px;
    right: 20px;
    top: 80px;
  }
}

@media (max-width: 768px) {
  .similar-card {
    margin-bottom: 20px;
  }

  .row.g-3 > [class*='col-'] {
    padding-left: 10px;
    padding-right: 10px;
    margin-bottom: 15px;
  }

  .form-box, .plate-details {
    margin-bottom: 35px;
  }

  .similar-card .card-body {
    padding: 16px;
  }

  .similar-card img {
    margin-bottom: 10px;
  }

  .form-control {
    margin-bottom: 10px;
  }
}

@media (max-width: 430px) {
  .back-btn {
    top: 70px;
    right: 15px;
    padding: 6px 14px;
    font-size: 13px;
  }

  .plate-details {
    padding: 20px;
  }

  .plate-details img {
    max-height: 200px;
  }

  .form-box {
    padding: 20px;
  }

  .form-control {
    font-size: 14px;
  }

  .btn-primary {
    width: 100%;
    padding: 10px;
    font-size: 15px;
  }

  .similar-card .card-body {
    padding: 10px;
  }

  .similar-card img {
    height: 100px;
  }
    .plate-card img {
      width: 250px;
      height: 100px;
      object-fit: contain;
      display: block;
      margin-bottom: 10px;
    }
  .section-title {
    font-size: 18px;
    text-align: center;
  }

  .badge-status {
    font-size: 12px;
    padding: 6px 10px;
  }
}
  .btn-outline-custom {
    color: #3a04e3;
    border: 2px solid #3a04e3;
    background-color: transparent;
    border-radius: 50px;
    font-weight: 600;
    transition: 0.3s ease-in-out;
  }

  .btn-outline-custom:hover {
    background-color: #3a04e3;
    color: #fff;
    box-shadow: 0 0 10px rgba(58, 4, 227, 0.2);
  }


  </style>
</head>
<body>

<?php include '../../../includes/navbar.php'; ?>

<!-- زر العودة -->
<a href="../public/index.php" class="back-btn"><i class="fas fa-arrow-right"></i> رجوع</a>

<div class="container">
  <div class="plate-details mt-3">
    <div class="row align-items-center">
    <div class="col-12 col-md-6 text-center mb-3 mb-md-0">
    <img src="../../../uploads/<?= $plate['image']; ?>" alt="لوحة <?= $plate['plate_number']; ?>" class="img-fluid">
      </div>
      <div class="col-12 col-md-6">
      <h3 class="section-title">تفاصيل اللوحة</h3>
        <p><strong>رقم اللوحة</strong> <?= $plate['plate_number']; ?></p>
        <?php if ($plate['final_price']): ?>
          <p><strong>السعر </strong>  على السوم</p>
          <p class="d-flex align-items-center  gap-1 mb-1">
            <span>الحد: <?= intval($plate['final_price']) ?> </span>
            <img src="../../../assets/images/Saudi_Riyal_Symbol-2.svg" alt="عملة" style="width: 15px; height: 16px; vertical-align: middle; position: relative; top: 4px;" >
          </p>
        <?php elseif ($plate['bid_price']): ?>
          <p class="d-flex align-items-center  gap-1 mb-1">
            <strong>السوم </strong> <?= intval($plate['bid_price']); ?> 
            <img src="../../../assets/images/Saudi_Riyal_Symbol-2.svg" alt="عملة" style="width: 15px; height: 16px; vertical-align: middle; position: relative; " >
          </p>
        <?php else: ?>
          <p><strong>السعر </strong>على السوم</p>
        <?php endif; ?>
        <p>
          <?php if ($plate['status'] == 'تم البيع'): ?>
            <span class="badge bg-danger badge-status"><i class="fas fa-times-circle"></i> تم البيع</span>
          <?php elseif ($plate['status'] == 'تحت المزايدة'): ?>
            <span class="badge bg-warning text-dark badge-status"><i class="fas fa-gavel"></i> تحت المزايدة</span>
          <?php else: ?>
            <span class="badge bg-success badge-status"><i class="fas fa-check-circle"></i> متاحة</span>
          <?php endif; ?>
        </p>
      </div>
    </div>
  </div>

  <!-- نموذج التواصل -->
  <div class="form-box mb-5">
    <h5 class="section-title"><i class="fas fa-paper-plane me-2"></i> تواصل معنا</h5>
    <?php if ($success): ?>
      <div class="alert alert-success"><?= $success; ?></div>
    <?php elseif (isset($error)): ?>
      <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>
    <form method="POST" class="mt-3">
      <div class="row g-3">
        <div class="col-12 col-md-6">
          <input type="text" name="name" class="form-control" placeholder="الاسم" required>
        </div>
        <div class="col-12 col-md-6">
          <input type="email" name="email" class="form-control" placeholder="البريد الإلكتروني" required>
        </div>
        <div class="col-12">
          <textarea name="message" class="form-control" rows="4" placeholder="اكتب رسالتك هنا..." required></textarea>
        </div>
        <div class="col-12 text-end">
          <button name="contact_submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> إرسال</button>
        </div>
      </div>
    </form>
  </div>

  <!-- لوحات مشابهة -->
  <div>
    <h5 class="section-title"><i class="fas fa-th-large me-2"></i> لوحات مشابهة</h5>
    <div class="row g-3">
      <?php foreach ($similarPlates as $sim): ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3" data-aos="fade-up">
        <div class="card similar-card" data-aos="zoom-in">
          <img src="../../../uploads/<?= $sim['image']; ?>" class="card-img-top" alt="لوحة <?= $sim['plate_number']; ?>">
          <div class="card-body text-center">
            <h6 class="card-title mb-1"><?= $sim['plate_number']; ?></h6>
            <?php if ($plate['final_price']): ?>
          <p><strong>السعر </strong>  على السوم</p>
          <p class=" d-flex justify-content-center align-items-center gap-1 mb-1">
            <span>الحد <?= intval($plate['final_price']) ?> </span>
            <img src="../../../assets/images/Saudi_Riyal_Symbol-2.svg" alt="عملة" style="width: 14px; height: 14px; position: relative; top: 2px;" >
          </p>
        <?php elseif ($plate['bid_price']): ?>
          <p class=" d-flex justify-content-center align-items-center gap-1 mb-1">
            <strong>السوم </strong> <?= intval($plate['bid_price']); ?> 
            <img src="../../../assets/images/Saudi_Riyal_Symbol-2.svg" alt="عملة" style="width: 14px; height: 14px; position: relative; top: 2px; " >
          </p>
        <?php else: ?>
          <p><strong>السعر </strong>على السوم</p>
        <?php endif; ?>
            <a href="view_plate.php?id=<?= $plate['id']; ?>" class="btn btn-sm rounded-pill btn-outline-custom">
              <i class="fas fa-eye"></i> عرض
            </a>          
          </div>
        </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<script>
  AOS.init({
    duration: 700,
    once: true
  });
</script>
<?php include '../../../includes/footer.php'; ?>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
