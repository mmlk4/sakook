<?php
require_once __DIR__ . '/../../includes/db.php';
require_once 'tcpdf/tcpdf.php';       // مكتبة TCPDF

if (!isset($_GET['order_id'])) {
    die("رقم الطلب غير موجود.");
}

$order_id = intval($_GET['order_id']);

// جلب بيانات الطلب
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    die("الطلب غير موجود.");
}

// جلب الخدمات المرتبطة بالطلب
$stmt_items = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt_items->execute([$order_id]);
$items = $stmt_items->fetchAll();

// إعداد اللغة والاتجاه
$lg = [
    'a_meta_charset' => 'UTF-8',
    'a_meta_dir' => 'rtl',
    'a_meta_language' => 'ar',
    'w_page' => 'صفحة'
];

// إنشاء مستند PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setLanguageArray($lg);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle("فاتورة الطلب رقم #$order_id");

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(15, 15, 15);
$pdf->SetFont('aealarabiya', '', 14);
$pdf->AddPage();

// شعار الشركة
$pdf->Image('../../assets/images/logo.png', 80, 10, 50);
$pdf->Ln(40);

// محتوى الفاتورة
$html = '<h2 style="text-align:center;">فاتورة الطلب</h2>';
$html .= '<p><strong>اسم العميل:</strong> ' . htmlspecialchars($order['customer_name']) . '</p>';
$html .= '<p><strong>رقم الجوال:</strong> ' . htmlspecialchars($order['customer_phone']) . '</p>';
$html .= '<p><strong>البريد الإلكتروني:</strong> ' . htmlspecialchars($order['customer_email']) . '</p>';
$html .= '<p><strong>رقم الطلب:</strong> ' . $order_id . '</p>';
$html .= '<br><table border="1" cellpadding="5"><thead><tr>
            <th><strong>الخدمة</strong></th>
            <th><strong>السعر (ر.س)</strong></th>
        </tr></thead><tbody>';

foreach ($items as $item) {
    $html .= '<tr><td>' . htmlspecialchars($item['service_name']) . '</td>
              <td>' . number_format($item['service_price'], 2) . '</td></tr>';
}

$html .= '<tr><td><strong>الإجمالي</strong></td>
          <td><strong>' . number_format($order['total_price'], 2) . '</strong></td></tr>';
$html .= '</tbody></table>';
$html .= '<br><p style="text-align:center;">شكراً لثقتكم بنا، نتمنى أن نكون عند حسن ظنكم </p>';

// طباعة المحتوى
$pdf->writeHTML($html, true, false, true, false, '');

// إخراج الملف للتحميل فقط إذا كان هناك طلب تحميل PDF
if (isset($_GET['download']) && $_GET['download'] === 'pdf') {
    $pdf->Output("invoice_$order_id.pdf", 'I');
    exit;
}
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>فاتورة الطلب #<?php echo $order_id; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      font-family: 'Tajawal', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 20px;
      color: #333;
    }

    .invoice {
      max-width: 700px;
      margin: auto;
      background-color: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.05);
    }

    .invoice h2 {
      text-align: center;
      color: #3a04e3;
    }

    .logo {
      text-align: center;
      margin-bottom: 20px;
    }

    .logo img {
      width: 100px;
    }

    .section {
      margin-bottom: 25px;
    }

    .section strong {
      color: #3a04e3;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: center;
    }

    th {
      background-color: #f1f1f1;
      color: #3a04e3;
    }

    .thank-you {
      margin-top: 30px;
      text-align: center;
      font-weight: bold;
      color: #3a04e3;
    }

    .download-btn {
      background-color: #3a04e3;
      color: white;
      text-decoration: none;
      padding: 10px 25px;
      font-size: 16px;
      border-radius: 8px;
      display: inline-block;
      margin-top: 15px;
    }
  </style>
</head>
<body>

<div class="invoice">
  <div class="logo">
    <img src="../../assets/images/logo.png" alt="شعار الشركة">
  </div>

  <h2>فاتورة الطلب #<?php echo $order_id; ?></h2>

  <div class="section">
    <p><strong>العميل:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
    <p><strong>الهاتف:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
    <p><strong>البريد الإلكتروني:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
    <p><strong>طريقة الدفع:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
  </div>

  <div class="section">
    <table>
      <thead>
        <tr>
          <th>الخدمة</th>
          <th>السعر (ر.س)</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
          <td><?php echo htmlspecialchars($item['service_name']); ?></td>
          <td><?php echo number_format($item['service_price'], 2); ?></td>
        </tr>
        <?php endforeach; ?>
        <tr>
          <td><strong>الإجمالي</strong></td>
          <td><strong><?php echo number_format($order['total_price'], 2); ?> ر.س</strong></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="thank-you">
    شكراً لثقتكم بنا، نتمنى أن نكون عند حسن ظنكم 
    <br><br>
    <a href="?order_id=<?php echo $order_id; ?>&download=pdf" class="download-btn"> تحميل الفاتورة </a>
  </div>
</div>

</body>
</html>
