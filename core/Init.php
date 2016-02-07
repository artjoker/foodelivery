<?php
  session_start();
  error_reporting(E_ALL);
  date_default_timezone_set("UTC");

  require ROOT . "/core/Config.php";

  require PATH_CORE . "SlimFramework/Slim.php";

  \Slim\Slim::registerAutoloader();

  $app = new \Slim\Slim(
    array(
      'templates.path' => PATH_DESIGN . "tpl/",
      //'log.level'      => \Slim\Log::CRITICAL,
    )
  );
  // Lang
  $app->container->singleton('lang', function () {
    return new \Slim\Lang();
  });

  // Database MySQLi API
  $app->container->singleton('db', function () {
    return new \Slim\Database();
  });

  // PHPMailer API
  $app->container->singleton('mail', function () {
    return new \Slim\Mailer();
  });

  // Image
  $app->container->singleton('image', function () {
    return new \Slim\Image();
  });


  function protect()
  {
    //    if (!isset($_SESSION['admin'])) die(header('Location: /'));
  }