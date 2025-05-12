<?php
require_once __DIR__ . '/../includes/db.php';
require_once '../includes/session_protect.php';

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=messages_" . date('Ymd') . ".xls");

echo "<table border='1'>";
echo "<tr>
        <th>الاسم</th>
        <th>البريد الإلكتروني</th>
        <th>الرسالة</th>
        <th>تاريخ الإرسال</th>
      </tr>";

$messages = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC")->fetchAll();

foreach ($messages as $msg) {
  echo "<tr>
          <td>" . htmlspecialchars($msg['name']) . "</td>
          <td>" . htmlspecialchars($msg['email']) . "</td>
          <td>" . nl2br(htmlspecialchars($msg['message'])) . "</td>
          <td>" . $msg['created_at'] . "</td>
        </tr>";
}

echo "</table>";
?>
