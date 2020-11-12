<?php
// Файлы phpmailer
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

// Переменные, которые отправляет пользователь
$name = $_POST['user_name'];
$company = $_POST['user_company'];
$userTitle = $_POST['user_title'];
$email = $_POST['user_email'];
$usedata = $_POST['user_data'];

// Формирование самого письма
$title = "[New Lead]Inquiry from Moldex3D Russia";
$body = "
<br><h2>Here is a new lead from your website!</h2><br>
<b>Name:</b> $name<br>
<b>Company:</b> $company<br>
<b>Title:</b> $userTitle<br>
<b>E-mail:</b> $email<br><br>
<b>Can we use this data:</b> $usedata<br><br>

";

// Настройки PHPMailer
$mail = new PHPMailer\PHPMailer\PHPMailer();
try {
    $mail->isSMTP();   
    $mail->CharSet = "UTF-8";
    $mail->SMTPAuth   = true;
    //$mail->SMTPDebug = 2;
    $mail->Debugoutput = function($str, $level) {$GLOBALS['status'][] = $str;};

    // Настройки вашей почты
    $mail->Host       = 'smtp.mail.ru'; // SMTP сервера вашей почты
    $mail->Username   = 'moldex3drussia'; // Логин на почте
    $mail->Password   = '$rAotp3YsTA3'; // Пароль на почте
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;
    $mail->setFrom('moldex3drussia@mail.ru', 'Moldex3DRussia'); // Адрес самой почты и имя отправителя

    // Получатель письма
    $mail->addAddress('olgaandriychenko@moldex3d.com');  

    // Прикрипление файлов к письму
if (!empty($file['name'][0])) {
    for ($ct = 0; $ct < count($file['tmp_name']); $ct++) {
        $uploadfile = tempnam(sys_get_temp_dir(), sha1($file['name'][$ct]));
        $filename = $file['name'][$ct];
        if (move_uploaded_file($file['tmp_name'][$ct], $uploadfile)) {
            $mail->addAttachment($uploadfile, $filename);
            $rfile[] = "Файл $filename прикреплён";
        } else {
            $rfile[] = "Не удалось прикрепить файл $filename";
        }
    }   
}
// Отправка сообщения
$mail->isHTML(true);
$mail->Subject = $title;
$mail->Body = $body;    

// Проверяем отравленность сообщения
if ($mail->send()) {header ('location: thankpage/thankyou.html');} 
else {echo 'Сообщение не было отправлено! Пожалуйста, свяжитесь с нами напрямую ';}

} catch (Exception $e) {
    $result = "error";
    $status = "Сообщение не было отправлено. Причина ошибки: {$mail->ErrorInfo}";
}

