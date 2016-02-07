<?php
  /**
   * Created by PhpStorm.
   * User: c0da
   * Date: 03.02.2016
   * Time: 20:34
   */

  define('ROOT', __DIR__);

  include ROOT . "/core/Init.php";


  require PATH_CORE . "routes/api.php";
  require PATH_CORE . "routes/admin.php";

  $app->run();
