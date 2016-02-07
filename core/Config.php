<?php

  // filesystem
  define('PATH_DESIGN', ROOT . "/design/");
  define('PATH_CORE', ROOT . "/core/");
  define('PATH_CACHE', ROOT . "/cache/");
  define('PATH_ROUTE', ROOT . "/core/routes/");

  // url
  define('URL_ROOT', (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/');
  define('URL_ADMIN', URL_ROOT . "admin/");
  define('URL_CSS', URL_ROOT . "design/css/");
  define('URL_JS', URL_ROOT . "design/js/");

  // database config
  define('DB_HOST', 'localhost');
  define('DB_USER', 'root');
  define('DB_PASS', '');
  define('DB_NAME', 'food');

  // email config
  define('MAIL_HOST', 'localhost');
  define('MAIL_PORT', 25);
  define('MAIL_USER', '');
  define('MAIL_PASS', '');

  // images
  define('IMAGE_QUALITY_DEFAULT', 85);
  define('IMAGE_STORAGE', ROOT . 'data/images/');
  define('IMAGE_CACHE_PATH', PATH_CACHE . 'images/');

  // pagination
  define('LIMIT', 20);

  // api config
  define('API_KEY', md5($_SERVER['SERVER_NAME'] . "Artjoker"));

  // language
  define('LANG', 'en');

  // branding
  define('BRAND', 'vovkaSlim');
