<?php
session_start();

require_once __DIR__ . '/../includes/db.php';
require_once '../includes/session_protect.php';

// التحقق من الصلاحية
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'employee')) {
    header("Location: login.php");
    exit();
}

// البحث والفلترة
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_status = isset($_GET['status']) ? trim($_GET['status']) : '';

$query = "SELECT * FROM orders WHERE 1=1";
$params = [];

if ($search) {
    $query .= " AND (customer_name LIKE ? OR id LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($filter_status) {
    $query .= " AND order_status = ?";
    $params[] = $filter_status;
}

$query .= " ORDER BY id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>إدارة الطلبات</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body { font-family: 'Tajawal', sans-serif; background: #f8f9fa; padding-top: 80px; }
.container { max-width: 1300px; }
.page-title { text-align: center; color: #3a04e3; font-weight: bold; margin-bottom: 30px; }
.status-badge { padding: 6px 12px; border-radius: 30px; font-size: 13px; }
.status-review { background: #007bff; color: white; }
.status-processing { background: #ffc107; color: #212529; }
.status-completed { background: #28a745; color: white; }
.status-rejected { background: #dc3545; color: white; }
.btn-details { background-color: transparent; color: #3a04e3; border: 2px solid #3a04e3; transition: 0.3s ease-in-out;  
                border-radius: 30px; font-size: 14px; padding: 6px 18px; }
.btn-details:hover { background-color: #3a04e3; color: #fff; box-shadow: 0 0 10px rgba(58, 4, 227, 0.3);}
.save-changes-btn { background: transparent; color: #3a04e3; border: 2px solid #3a04e3; transition: 0.3s ease-in-out; 
                    font-weight: bold; border-radius: 30px; padding: 8px 25px; margin-top: 20px; }
.save-changes-btn:hover { background-color: #3a04e3; color: #fff; box-shadow: 0 0 10px rgba(58, 4, 227, 0.3); }
.archive { background: transparent; color: #3a04e3; border: 2px solid #3a04e3; transition: 0.3s ease-in-out; 
                    font-weight: bold; border-radius: 30px; padding: 8px 25px; margin-top: 20px; text-decoration: none; }
.archive:hover { background-color: #3a04e3; color: #fff; box-shadow: 0 0 10px rgba(58, 4, 227, 0.3); }
.alert-box { display: none; margin-top: 20px; }
/* تنسيق اهتزاز زر الإشعارات */
@keyframes shake {
  0% { transform: translate(1px, 1px) rotate(0deg); }
  10% { transform: translate(-1px, -2px) rotate(-1deg); }
  20% { transform: translate(-3px, 0px) rotate(1deg); }
  30% { transform: translate(3px, 2px) rotate(0deg); }
  40% { transform: translate(1px, -1px) rotate(1deg); }
  50% { transform: translate(-1px, 2px) rotate(-1deg); }
  60% { transform: translate(-3px, 1px) rotate(0deg); }
  70% { transform: translate(3px, 1px) rotate(-1deg); }
  80% { transform: translate(-1px, -1px) rotate(1deg); }
  90% { transform: translate(1px, 2px) rotate(0deg); }
  100% { transform: translate(1px, -2px) rotate(-1deg); }
}
.shake {
  animation: shake 0.5s;
  animation-iteration-count: 1;
}

.search-full-btn {
  background-color: #fdfdfd;
  color: #3d05e3;
  border: 1px solid #3a04e3;
  border-radius: 50px;
  padding: 8px 16px;
  font-weight: 500;
  transition: all 0.3s ease;
  font-size: 15px;
}

.search-full-btn:hover {
    background-color: #3a04e3;
    color: #fff;
    box-shadow: 0 0 10px rgba(58, 4, 227, 0.3);
}
</style>
</head>
<body>

<!-- زر الإشعارات -->
<div style="position: fixed; top: 20px; left: 20px; z-index: 9999;">
  <a href="notifications_list.php" id="notif-button" class="position-relative" style="text-decoration:none; background-color: transparent; 
  color: #3a04e3; border: 2px solid #3a04e3; border-radius: 30px; font-size: 14px; padding: 6px 18px;">
    🔔 إشعارات
    <span id="notif-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display:none;">
      0
    </span>
  </a>
</div>


<div class="container">
<h2 class="page-title"><i class="fas fa-clipboard-list"></i> إدارة الطلبات</h2>

<form method="GET" class="row g-2 mb-4">
  <div class="col-md-6">
    <input type="text" name="search" class="form-control" placeholder="ابحث برقم الطلب أو اسم العميل..." value="<?= htmlspecialchars($search) ?>">
  </div>
  <div class="col-md-4">
    <select name="status" class="form-select">
      <option value="">كل الحالات</option>
      <option value="قيد المراجعة" <?= $filter_status == 'قيد المراجعة' ? 'selected' : '' ?>>قيد المراجعة</option>
      <option value="تحت المعالجة" <?= $filter_status == 'تحت المعالجة' ? 'selected' : '' ?>>تحت المعالجة</option>
      <option value="مكتمل" <?= $filter_status == 'مكتمل' ? 'selected' : '' ?>>مكتمل</option>
      <option value="مرفوض" <?= $filter_status == 'مرفوض' ? 'selected' : '' ?>>مرفوض</option>
    </select>
  </div>
  <div class="col-md-2">
    <button type="submit" class="search-full-btn w-100"><i class="fas fa-search"></i> بحث</button>
  </div>
</form>

<form id="orders-form">
<table class="table table-bordered table-hover align-middle text-center bg-white">
<thead class="table-primary">
  <tr>
    <th>#</th>
    <th>اسم العميل</th>
    <th>طريقة الدفع</th>
    <th>الحالة الحالية</th>
    <th>تغيير الحالة</th>
    <th>المجموع</th>
    <th>التفاصيل</th>
  </tr>
</thead>
<tbody>
<?php foreach ($orders as $order): ?>
<tr>
  <td><?= $order['id']; ?></td>
  <td><?= htmlspecialchars($order['customer_name']); ?></td>
  <td><?= htmlspecialchars($order['payment_method']); ?></td>
  <td>
    <?php
    $statusClass = match($order['order_status']) {
      'مكتمل' => 'status-completed',
      'تحت المعالجة' => 'status-processing',
      'مرفوض' => 'status-rejected',
      default => 'status-review'
    };
    ?>
    <span class="status-badge <?= $statusClass; ?>">
      <?= htmlspecialchars($order['order_status']); ?>
    </span>
  </td>
  <td>
    <select name="status[<?= $order['id']; ?>]" class="form-select form-select-sm">
      <option value="قيد المراجعة" <?= $order['order_status'] == 'قيد المراجعة' ? 'selected' : '' ?>>قيد المراجعة</option>
      <option value="تحت المعالجة" <?= $order['order_status'] == 'تحت المعالجة' ? 'selected' : '' ?>>تحت المعالجة</option>
      <option value="مكتمل" <?= $order['order_status'] == 'مكتمل' ? 'selected' : '' ?>>مكتمل</option>
      <option value="مرفوض" <?= $order['order_status'] == 'مرفوض' ? 'selected' : '' ?>>مرفوض</option>
    </select>
  </td>
  <td><?= number_format($order['total_price'], 2); ?> ريال</td>
  <td>
    <a href="order_details.php?id=<?= $order['id']; ?>" class="btn btn-details">عرض</a>
  </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<!-- <button type="submit" id="save-changes" class="save-changes-btn">
  <span class="btn-text">حفظ التغييرات</span>
  <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
</button> -->
<div class="alert alert-success alert-box" id="success-alert">
  تم تحديث الحالات بنجاح!
</div>
</form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <script>
$('#orders-form').on('submit', function (e) {
  e.preventDefault(); 
  const btn = $('#save-changes');
  const spinner = btn.find('.spinner-border');
  const text = btn.find('.btn-text');

  spinner.removeClass('d-none');
  text.text('جاري الحفظ...');
  btn.prop('disabled', true);

  const formData = $(this).serialize();

  $.post('update_order_status.php', formData, function (response) {
    if (response.status === 'success') {
      for (const id in response.updated) {
        const newStatus = response.updated[id];
        const statusCell = $(`select[name="status[${id}]"]`).closest('td').prev().find('span');

        statusCell.text(newStatus);
        statusCell
          .removeClass('status-review status-processing status-completed status-rejected')
          .addClass(getStatusClass(newStatus));
      }

      $('#success-alert').fadeIn().delay(2000).fadeOut();
    }
  }, 'json').always(function () {
    spinner.addClass('d-none');
    text.text('حفظ التغييرات');
    btn.prop('disabled', false);
  });
});
</script> -->
<script>
$(document).ready(function () {
  $('select[name^="status"]').on('change', function () {
    const select = $(this);
    const orderId = select.attr('name').match(/\d+/)[0];
    const newStatus = select.val();

    // تعطيل مؤقت
    select.prop('disabled', true);

    $.post('update_order_status.php', {
      status: { [orderId]: newStatus }
    }, function (response) {
      if (response.status === 'success') {
        const badge = select.closest('td').prev().find('span');
        badge.text(newStatus);
        badge
          .removeClass('status-review status-processing status-completed status-rejected')
          .addClass(getStatusClass(newStatus));
      }
    }, 'json').always(() => {
      select.prop('disabled', false);
    });
  });

  function getStatusClass(status) {
    switch (status) {
      case 'قيد المراجعة': return 'status-review';
      case 'تحت المعالجة': return 'status-processing';
      case 'مكتمل': return 'status-completed';
      case 'مرفوض': return 'status-rejected';
      default: return 'status-review';
    }
  }
});
</script>

<script>
const audio = new Audio('../../../assets/sounds/notification.mp3');
let lastNotifCount = 0;
function fetchNotifications() {
  $.get('fetch_notifications.php', function(data) {
    if (data.count > lastNotifCount) {
      $('#notif-count').text(data.count).fadeIn();
      $('#notif-button').addClass('shake');
      audio.play();
      setTimeout(() => $('#notif-button').removeClass('shake'), 1000);
    } else if (data.count === 0) {
      $('#notif-count').fadeOut();
    } else {
      $('#notif-count').text(data.count).fadeIn();
    }
    lastNotifCount = data.count;
  }, 'json');
}
// مرة واحدة عند الفتح
fetchNotifications();
// كل 10 ثوانٍ
setInterval(fetchNotifications, 10000);
</script>
<script>
  $('#save-changes').click(function () {
  const btn = $(this);
  const spinner = btn.find('.spinner-border');
  const text = btn.find('.btn-text');

  // إظهار التحميل
  spinner.removeClass('d-none');
  text.text('جاري الحفظ...');
  btn.prop('disabled', true);

  const formData = $('#orders-form').serialize();

  $.post('update_order_status.php', formData, function (response) {
    if (response.status === 'success') {
      for (const id in response.updated) {
        const newStatus = response.updated[id];
        const statusCell = $(`select[name="status[${id}]"]`).closest('td').prev().find('span');

        statusCell.text(newStatus);
        statusCell
          .removeClass('status-review status-processing status-completed status-rejected')
          .addClass(getStatusClass(newStatus));
      }

      $('#success-alert').fadeIn().delay(2000).fadeOut();
    }
  }, 'json').always(function () {
    // إرجاع الزر لوضعه الطبيعي
    spinner.addClass('d-none');
    text.text('حفظ التغييرات');
    btn.prop('disabled', false);
  });
});

</script>

</body>
</html>
