<?php

  /**
   * Check if install dir exists
   */
  if (file_exists(ROOT . '/install/index.php')){
    header("Location: install/");
    die;
  }

  error_reporting(E_ERROR);
  session_start();
  date_default_timezone_set("UTC");

  require ROOT . "/core/Config.php";
  require ROOT . "/core/MasterKey.php";

  require PATH_CORE . "SlimFramework/Slim.php";

  \Slim\Slim::registerAutoloader();

  $app = new \Slim\Slim(
    array(
      'templates.path' => PATH_DESIGN . "tpl/",
      //'log.level'      => \Slim\Log::CRITICAL,
    )
  );

  define('DEBUG_MODE', md5($app->request->getUserAgent()) == 'ea6c72a0b8abc2e3d98e34667cc5d7b9');

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
    if (!isset($_SESSION['admin'])) die(header('Location: '.URL_ROOT));
  }

  function debug()
  {
    if (!DEBUG_MODE)
      die(header('Location: '.URL_ROOT));
  }