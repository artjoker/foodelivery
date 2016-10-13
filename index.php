<?php

  define('ROOT', __DIR__);

  include ROOT . "/core/Init.php";


  require PATH_CORE . "routes/api.php";
  require PATH_CORE . "routes/ajax.php";
  require PATH_CORE . "routes/debug.php";
  require PATH_CORE . "routes/admin.php";

  $app->run();
