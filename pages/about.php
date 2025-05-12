<?php
session_start();
  require_once __DIR__ . '/../../includes/db.php';

// جلب المحتوى من الجدول
$stmt = $pdo->query("SELECT * FROM about_page ORDER BY id ASC");
$sections = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>من نحن - صكوك الحديثة</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="/../../assets/images/favicon.png" type="image/png">

  <style>
    body {
      font-family: 'Tajawal', sans-serif;
      background-color: #f9f9f9;
      padding-top: 80px;
    }
    
    .navbar {
            BORDER-BOTTOM: 1PX SOLID #3b04e5 !important;
    }

    .about-section {
      background: #fff;
      padding: 60px 30px;
      border-radius: 12px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.05);
      margin-bottom: 60px;
    }

    h3 i {
      color: #3a04e3;
    }

    h1 {
      color: #3a04e3;
      font-weight: bold;
    }

    .lead {
      font-size: 1.1rem;
      color: #555;
    }

    ul {
      list-style: inside;
    }
  </style>
        <!-- Google tag (gtag.js) -->
     <script async src="https://www.googletagmanager.com/gtag/js?id=G-WN0R1JCWMQ"></script>
     <script>
       window.dataLayer = window.dataLayer || [];
       function gtag(){dataLayer.push(arguments);}
       gtag('js', new Date());

       gtag('config', 'G-WN0R1JCWMQ');
     </script>
</head>
<body>

<?php include __DIR__ . '/../../includes/navbar.php'; ?>

<div class="container">
  <div class="about-section mt-4">
    <!-- <h1 class="mb-5 text-center"><i class="fas fa-info-circle me-2"></i> من نحن</h1> -->

    <?php
    // خريطة الأيقونات لكل عنوان قسم
    $icons = [
      'من نحن' => 'fas fa-users',
      'رؤيتنا' => 'fas fa-eye',
      'رسالتنا' => 'fas fa-envelope-open-text',
      'أهدافنا' => 'fas fa-bullseye',
      'لماذا نحن' => 'fas fa-star',
    ];

    foreach ($sections as $row):
      $icon = isset($icons[$row['section']]) ? $icons[$row['section']] : 'fas fa-circle';
    ?>
      <h3 class="mt-5"><i class="<?= $icon ?> me-2"></i> <?= htmlspecialchars($row['section']) ?></h3>
      <div class="mb-3"><?= htmlspecialchars_decode($row['content']) ?></div>
    <?php endforeach; ?>
  </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
