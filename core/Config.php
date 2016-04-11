<?php

  // filesystem
  define('DS', DIRECTORY_SEPARATOR);
  define('PATH_DESIGN', ROOT . DS . "design" . DS);
  define('PATH_CORE', ROOT . DS . "core" . DS);
  define('PATH_CACHE', ROOT . DS . "cache" . DS);
  define('PATH_ROUTE', ROOT . DS . "core". DS . "routes" . DS);

  // url
  define('URL_ROOT', (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']  .str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
  define('URL_ADMIN', URL_ROOT . "admin/");
  define('URL_CSS', URL_ROOT . "design/css/");
  define('URL_JS', URL_ROOT . "design/js/");

  // database config
  define('DB_HOST', '#DATABASE_HOST#');
  define('DB_USER', '#DATABASE_USER#');
  define('DB_PASS', '#DATABASE_PASS#');
  define('DB_NAME', '#DATABASE_BASE#');

  // images
  define('IMAGE_STORAGE', ROOT . DS . 'data' );
  define('IMAGE_CACHE_PATH', PATH_CACHE . 'images' . DS);


  // api config
  define('API_KEY', md5(rtrim(str_replace('http://', '', URL_ROOT), '/') . "Artjoker"));

  //error_reporting(E_ERROR);
