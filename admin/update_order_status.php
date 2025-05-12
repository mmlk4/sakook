    <?php
    session_start();
    file_put_contents('email_log.txt', "✅ وصل للملف\n", FILE_APPEND);

    require_once dirname(__DIR__) . '/../includes/db.php';
    require_once '/../includes/session_protect.php';
    require_once '/../includes/mailer_config.php';
    require_once '../admin/core_945x.php';

    function sendWhatsAppNotification($phoneNumber, $message) {
    file_put_contents('whatsapp_log.txt', "Message to $phoneNumber: $message\n", FILE_APPEND);
    }

    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'employee')) {
    echo json_encode(['status' => 'unauthorized']);
    exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status']) && is_array($_POST['status'])) {
    try {
    $updated_orders = [];

    foreach ($_POST['status'] as $order_id => $new_status) {
    $order_id = intval($order_id);
    $allowed_statuses = ['قيد المراجعة', 'تحت المعالجة', 'مكتمل', 'مرفوض'];

    if (!in_array($new_status, $allowed_statuses)) continue;

    $stmt_info = $pdo->prepare("SELECT customer_name, customer_email, customer_phone FROM orders WHERE id = ?");
    $stmt_info->execute([$order_id]);
    $order_info = $stmt_info->fetch();

    if ($order_info) {
    $customer_name = $order_info['customer_name'];
    $customer_email = $order_info['customer_email'];
    $customer_phone = $order_info['customer_phone'];

    file_put_contents('email_log.txt', "🟡 جاري معالجة طلب رقم $order_id لحالة $new_status\n", FILE_APPEND);

    $stmt = $pdo->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
    $stmt->execute([$new_status, $order_id]);

    // تنبيه داخلي
    $message = "تم تحديث حالة الطلب رقم #$order_id إلى ($new_status)";
    $link = "admin/order_details.php?id=$order_id";
    $notif = $pdo->prepare("INSERT INTO notifications (message, link, status, created_at) VALUES (?, ?, 'unread', NOW())");
    $notif->execute([$message, $link]);

    $email_sent = sendEmail($customer_email, "تحديث طلبك", $customer_name, $order_id, $new_status);
    file_put_contents('email_log.txt', "Email sent to $customer_email about $order_id - $new_status: " . ($email_sent ? 'Success' : 'Failure') . "\n", FILE_APPEND);
    
    sendWhatsAppNotification($customer_phone, "مرحباً $customer_name، تم تحديث حالة طلبك رقم #$order_id إلى ($new_status).");

    // أضف الطلب للمصفوفة
    $updated_orders[$order_id] = $new_status;
    }
    }

    echo json_encode(['status' => 'success', 'updated' => $updated_orders]);
    exit;

    } catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    
    exit;
    }
    }

    echo json_encode(['status' => 'invalid_request']);
    exit;
    ?>
