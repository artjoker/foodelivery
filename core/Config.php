<?php

  // filesystem
  define('DS', DIRECTORY_SEPARATOR);
  define('PATH_DESIGN', ROOT . DS . "design" . DS);
  define('PATH_CORE', ROOT . DS . "core" . DS);
  define('PATH_CACHE', ROOT . DS . "cache" . DS);
  define('PATH_ROUTE', ROOT . DS . "core". DS . "routes" . DS);

  // url
  define('URL_ROOT', (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/');
  define('URL_ADMIN', URL_ROOT . "admin/");
  define('URL_CSS', URL_ROOT . "design/css/");
  define('URL_JS', URL_ROOT . "design/js/");

  // database config
  define('DB_HOST', 'localhost');
  define('DB_USER', 'root');
  define('DB_PASS', '');
  define('DB_NAME', 'foodnew');

  // email config
  define('MAIL_HOST', 'localhost');
  define('MAIL_PORT', 25);
  define('MAIL_USER', '');
  define('MAIL_PASS', '');

  // images
  define('IMAGE_QUALITY_DEFAULT', 85);
  define('IMAGE_STORAGE', ROOT . DS . 'data' );
  define('IMAGE_CACHE_PATH', PATH_CACHE . 'images' . DS);

  // pagination
  define('LIMIT', 20);

  // api config
  define('API_KEY', md5($_SERVER['SERVER_NAME'] . "Artjoker"));
  define('GMAP_KEY', 'AIzaSyDT3XP98xXW3a8y12c56qVMRPOGGG5dmR8');

  // language
  define('LANG', 'en');

  // branding
  define('BRAND', 'Foodelivery');
