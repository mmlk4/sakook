<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// مدة عدم النشاط (ثواني) - مثلاً 20 دقائق = 1200 ثانية
$inactivity_limit = 1200;

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactivity_limit)) {
    session_unset();
    session_destroy();
    header("Location: ../admin/login.php?timeout=1"); // عدل المسار حسب مجلدك
    exit;
}

$_SESSION['last_activity'] = time(); // تحديث وقت آخر نشاط
