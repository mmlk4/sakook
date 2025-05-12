<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../admin/core_945x.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';
require_once __DIR__ . '/PHPMailer/Exception.php';

function sendEmail($to, $subject, $customer_name, $order_id, $new_status) {
    // تحقق من صحة البريد الإلكتروني
    if (!filter_var($to, FILTER_VALIDATE_EMAIL) || preg_match('/[^\x00-\x7F]/', $to)) {
        file_put_contents('email_log.txt', " عنوان غير صالح أو يحتوي على Unicode: $to\n", FILE_APPEND);
        return false;
    }

    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 0;
    $mail->Debugoutput = function($str, $level) {
        file_put_contents('email_debug_log.txt', "Debug ($level): $str\n", FILE_APPEND);
    };

    try {
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->CharSet   = 'UTF-8';
        $mail->SMTPUtf8  = false;
        $mail->Subject   = mb_encode_mimeheader($subject, "UTF-8", "B");
        $mail->Body      = "
            <div style='font-family: Tajawal, sans-serif; font-size: 16px; color: #333; background: #f9f9f9; padding: 20px; border-radius: 8px;'>
                <div style='background: #3a04e3; color: white; padding: 12px 20px; border-radius: 8px 8px 0 0;'>
                    <h2 style='margin: 0;'>تحديث طلبك</h2>
                </div>
                <div style='padding: 20px; background: white; border: 1px solid #eee; border-top: none;'>
                    <p>مرحباً <strong>{$customer_name}</strong>،</p>
                    <p>تم تحديث حالة طلبك رقم <strong>#{$order_id}</strong> إلى <strong>({$new_status})</strong>.</p>
                    <p>يمكنك متابعة تفاصيل طلبك من خلال لوحة الطلبات الخاصة بك.</p>
                    <hr>
                    <p style='font-size: 13px; color: #888;'>هذا البريد تم إرساله تلقائياً من نظام الطلبات، لا ترد عليه.</p>
                </div>
            </div>";

        $mail->send();
        file_put_contents('email_log.txt', " تم إرسال الإيميل إلى: $to\n", FILE_APPEND);
        return true;
    } catch (Exception $e) {
        file_put_contents('email_log.txt', " فشل الإرسال إلى $to: " . $mail->ErrorInfo . "\n", FILE_APPEND);
        return false;
    }
}
