<?php

  namespace Slim;


  class Mailer
  {

    function __construct()
    {
      require PATH_CORE . "PHPMailer/class.phpmailer.php";
    }

    function send($to, $subject, $body)
    {
      $mail          = new \PHPMailer();
      $mail->charSet = "UTF-8";
      $mail->IsSMTP();
      $mail->SMTPDebug = 0;
      $mail->SMTPAuth  = true;
      $mail->Host      = MAIL_HOST;
      $mail->Port      = MAIL_PORT;
      $mail->Username  = MAIL_USER;
      $mail->Password  = MAIL_PASS;
      $mail->Subject   = $subject;
      $mail->SetFrom(MAIL_USER);

      $mail->AddAddress($to);
      $mail->MsgHTML($body);
      if (!$mail->Send())
        echo $mail->ErrorInfo;
    }
  }