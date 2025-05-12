  <?php
require_once dirname(__DIR__) . '/../../includes/db.php';
$plates = $pdo->query("SELECT * FROM plates")->fetchAll();
  ?>

  <?php
  include '../../../includes/db.php';
  $plates = $pdo->query("SELECT * FROM plates")->fetchAll();
  $totalPlates = count($plates);
  $available = $pdo->query("SELECT COUNT(*) FROM plates WHERE status = 'متاحة'")->fetchColumn();
  $sold = $pdo->query("SELECT COUNT(*) FROM plates WHERE status = 'تم البيع'")->fetchColumn();
  $bidding = $pdo->query("SELECT COUNT(*) FROM plates WHERE status = 'تحت المزايدة'")->fetchColumn();
  ?>

  <!DOCTYPE html>
  <html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8">
    <title>لوحات السيارات</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- CountUp.js (لتحريك الأرقام) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/2.0.7/countUp.umd.js"></script>
    <!-- AOS (للأنميشن عند الظهور) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

    <style>
      
      html, body {
      height: 100%;
      margin: 0;
      display: flex;
      flex-direction: column;
    }

      .content-wrapper {
        flex: 1;
      }

      body {
        font-family: 'Tajawal', sans-serif;
        background: linear-gradient(to right, #f8f8f8, #ffffff);
        padding-top: 80px;
        min-height: 100vh; position: relative; padding-bottom: 80px;
        background: url('../../../assets/images/Untitled-2.jpg') repeat;
        background-size: contain;
        background-attachment: fixed;
      }

      .navbar {
        background-color: rgba(255,255,255,0.9);
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      }

      .plate-card {
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 15px 10px;
      min-height: 230px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 8px 20px rgba(0,0,0,0.05);
      transition: transform 0.3s ease;
    }
    .plate-card:hover {
      transform: scale(1.03);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    .plate-card img {
      width: 250px;
      height: 100px;
      object-fit: contain;
      display: block;
      margin-bottom: 10px;
    }

    .status-icon {
      font-size: 18px;
      margin-left: 5px;
      }

/* زر التمرير لأعلى */
.scroll-top {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 45px;
    height: 45px;
    background-color: #3a04e3;
    color: #fff;
    border-radius: 50%;
    display: none;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    z-index: 99;
    opacity: 0;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.scroll-top:hover {
    background-color:rgb(43, 18, 118);
    transform: translateY(-3px);
}

/* زر الواتساب */
.whatsapp-float {
    position: fixed;
    bottom: 30px;
    left: 30px;
    width: 70px;
    height: 70px;
    background-color: #25d366;
    color: white;
    border-radius: 50px;
    text-align: center;
    font-size: 35px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: center;
}

.whatsapp-float:hover {
    background-color: #128C7E;
    color: white;
}

.whatsapp-tooltip {
    position: absolute;
    left: 70px;
    background-color: #333;
    color: white;
    padding: 5px 12px;
    border-radius: 4px;
    font-size: 14px;
    white-space: nowrap;
    visibility: hidden;
    opacity: 0;
    transition: all 0.3s ease;
}

.whatsapp-float:hover .whatsapp-tooltip {
    visibility: visible;
    opacity: 1;
}

.pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
    }
    
    70% {
        transform: scale(1.1);
        box-shadow: 0 0 0 15px rgba(37, 211, 102, 0);
    }
    
    100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
    }
}

    .footer {
      background-color: #212529;
      color: #ccc;
      padding: 10px 0;
      text-align: center;
      width: 100%;
      bottom: 0;
      left: 0;
    }

    .filter-btn {
      margin: 0 5px 15px;
    }
    .filter-btn {
    transition: all 0.2s ease-in-out;
    }

    .filter-btn:hover {
        transform: translateY(-2px);
        background-color:rgb(184, 185, 185);
      }


    .hide {
          display: none !important;
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

  <!-- NAVBAR -->
  <?php include '../../../includes/navbar.php'; ?>
  <!-- FILTER BUTTONS -->
    <div class="container text-center mt-4 d-flex flex-wrap justify-content-center gap-2">
      <button class="btn btn-light border rounded-pill px-3 py-1 filter-btn shadow-sm" data-filter="all">
        <i class="fas fa-th-large text-muted me-1"></i> الكل
        <span class="badge bg-dark ms-2"><?= $totalPlates; ?></span>
      </button>
      <button class="btn btn-success bg-opacity-10 border-success text-light rounded-pill px-3 py-1 filter-btn shadow-sm" data-filter="متاحة">
        <i class="fas fa-check-circle me-1"></i> متاحة
        <span class="badge bg-success ms-2"><?= $available; ?></span>
      </button>
      <button class="btn btn-warning bg-opacity-10 border-warning text-light rounded-pill px-3 py-1 filter-btn shadow-sm" data-filter="تحت المزايدة">
        <i class="fas fa-gavel me-1"></i> مزايدة
        <span class="badge bg-warning text-dark ms-2"><?= $bidding; ?></span>
      </button>
      <button class="btn btn-danger bg-opacity-10 border-danger text-light rounded-pill px-3 py-1 filter-btn shadow-sm" data-filter="تم البيع">
        <i class="fas fa-times-circle me-1"></i> مباعة
        <span class="badge bg-danger ms-2"><?= $sold; ?></span>
      </button>
    </div>


  <!-- PLATES GRID -->
<div class="container">
  <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">
    <?php foreach ($plates as $plate): ?>
      <div class="col plate-item" data-status="<?= $plate['status']; ?>">
          <div class="plate-card text-center position-relative" data-aos="fade-up">
            <img src="../../../uploads/<?= $plate['image']; ?>" alt="لوحة <?= $plate['plate_number']; ?>" class="img-fluid mb-2">
            <p class="mb-1"> <strong><?= $plate['plate_number']; ?></strong> </p>
            <?php if ($plate['final_price']): ?>
            <p class="d-flex align-items-center justify-content-center gap-1 mb-1">
              <span>الحد: <?= intval($plate['final_price']) ?> </span>
              <img src="../../../assets/images/Saudi_Riyal_Symbol-2.svg" alt="عملة" style="width: 15px; height: 16px; vertical-align: middle; position: relative; top: 4px;" >
            </p>              
              <p class="text-muted "> السعر على السوم</p>
            <?php elseif ($plate['bid_price']): ?>
              <p class="d-flex align-items-center justify-content-center gap-1 mb-1"> السوم : <?= intval($plate['bid_price']) ?> 
              <img src="../../../assets/images/Saudi_Riyal_Symbol-2.svg" alt="عملة" style="width: 15px; height: 16px; vertical-align: middle; position: relative; top: 4px;" >
              </p>
            <?php else: ?>
              <p class="text-muted">السعر على السوم</p>
            <?php endif; ?>
            <p>
              <?php if ($plate['status'] == 'تم البيع'): ?>
                <span class="text-danger"><i class="fas fa-times-circle status-icon"></i> تم البيع</span>
              <?php elseif ($plate['status'] == 'تحت المزايدة'): ?>
                <span class="text-warning"><i class="fas fa-gavel status-icon"></i> تحت المزايدة</span>
              <?php else: ?>
                <span class="text-success"><i class="fas fa-check-circle status-icon"></i> متاحة</span>
              <?php endif; ?>
            </p>
            <a href="view_plate.php?id=<?= $plate['id']; ?>" class="btn btn-sm rounded-pill btn-outline-custom">
              <i class="fas fa-eye"></i> عرض
            </a>          
          </div>
      </div>
    <?php endforeach;?>
  </div>
</div>

  <!-- Scroll to top button -->
  <a href="#" class="scroll-top">
    <i class="fas fa-arrow-up"></i>
  </a>

  <!-- WhatsApp Button -->
  <a href="https://wa.me/966574977777" target="_blank" class="whatsapp-float pulse" title="تواصل معنا">
    <i class="fab fa-whatsapp"></i>
    <span class="whatsapp-tooltip">تواصل معنا</span>
  </a>
<br><br>
<footer class="footer mt-auto">
  <?php include '../../../includes/footer.php'; ?>
</footer>

  <!-- Filter Script -->
  <script>
    const filterButtons = document.querySelectorAll('.filter-btn');
    const items = document.querySelectorAll('.plate-item');
    filterButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        const filter = btn.getAttribute('data-filter');
        items.forEach(item => {
          item.classList.remove('hide');
          if (filter !== 'all' && item.getAttribute('data-status') !== filter) {
            item.classList.add('hide');
          }
        });
      });
    });
  </script>
  <script>
    AOS.init();
    // أرقام الإحصائيات من PHP
    const stats = {
      total: <?= $totalPlates; ?>,
      available: <?= $available; ?>,
      bidding: <?= $bidding; ?>,
      sold: <?= $sold; ?>
    };
    // استخدم CountUp
    const options = { duration: 2 };
    new countUp.CountUp('count-total', stats.total, options).start();
    new countUp.CountUp('count-available', stats.available, options).start();
    new countUp.CountUp('count-bidding', stats.bidding, options).start();
    new countUp.CountUp('count-sold', stats.sold, options).start();
  </script>
    <!-- زر التمرير لأعلى -->
  <script>  
  const scrollTop = document.querySelector('.scroll-top');
  
  if (scrollTop) {
    window.addEventListener('scroll', function() {
      if (window.pageYOffset > 300) {
        scrollTop.style.display = 'flex';
        setTimeout(() => {
          scrollTop.style.opacity = '1';
        }, 100);
      } else {
        scrollTop.style.opacity = '0';
        setTimeout(() => {
          scrollTop.style.display = 'none';
        }, 300);
      }
    });
    
    scrollTop.addEventListener('click', function(e) {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }
  </script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
  </html>
