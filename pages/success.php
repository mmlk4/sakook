<?php
session_start();
if (!isset($_GET['tap_id']) && !isset($_GET['order_id'])) {
echo "بيانات غير مكتملة.";
exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>تم الدفع بنجاح</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body {
    background-color: #06142e;
    color: white;
    font-family: 'Tajawal', sans-serif;
    text-align: center;
    padding-top: 50px;
}

.success-box {
    max-width: 500px;
    margin: auto;
    background-color:rgb(255, 255, 255);
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 0 30px rgba(0,0,0,0.3);
    color: black;
}

.success-box img {
    width: 180px;
    margin-bottom: 20px;
}

h2 {
    color: #3a04e3;
    margin-bottom: 10px;
}

.btn {
    display: inline-block;
    margin-top: 15px;
    padding: 12px 30px;
    font-size: 16px;
    border-radius: 30px;
    font-weight: bold;
    text-decoration: none;
}

.btn-primary {
    background-color: #3a04e3;
    color:rgb(255, 255, 255);
}

.btn-outline {
    border: 2px solid #3a04e3;
    color: #3a04e3;
    background: none;
    margin-top: 10px;
}

.countdown {
    margin-top: 20px;
    font-size: 14px;
    color: #aaa;
}
</style>

<script>
let seconds = 10;
function countdown() {
    const timer = document.getElementById("timer");
    if (seconds > 0) {
    seconds--;
    timer.innerText = seconds;
    setTimeout(countdown, 1000);
    } else {
    window.location.href = 'services.php';
    }
}
window.onload = countdown;
</script>
</head>

<body>
<div class="success-box">
<img src="../../assets/images/payment-success.png" alt="نجاح الدفع"> <!-- غيّر الصورة حسب هويتك -->
<h2>تهانينا</h2>
<p>لقد تم استلام الدفع الخاص بك<br>شكرًا لك على الدفع</p>

<a href="services.php" class="btn btn-primary">العودة للصفحة الرئيسية</a><br>
<a href="invoice.php?order_id=<?php echo $_GET['order_id'] ?? ''; ?>" class="btn btn-outline">عرض الفاتورة</a>
<div class="countdown">
سيتم تحويلك تلقائيًا خلال <span id="timer">15</span> ثوانٍ...
</div>
</div>
<script>
let seconds = 15;
const timer = document.getElementById("timer");
const countdown = setInterval(() => {
if (seconds > 1) {
    seconds--;
    timer.innerText = seconds;
} else {
    clearInterval(countdown);
    window.location.href = 'services.php';
}
}, 1000);
</script>

</body>
</html>
