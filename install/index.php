<?php
  session_id('setup');
  session_start();

  define('URL', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
  define('HOST', (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] .str_replace('/install/', '', URL));
  define('ROOT', __DIR__);
  define('TPL', __DIR__ . "/tpl/");
  define('SQL', __DIR__ . "/sql/");

  switch($_GET['step']) {
    case "ready":
      // write database
      $sql = new mysqli($_SESSION['db']['host'], $_SESSION['db']['user'], $_SESSION['db']['pass'], $_SESSION['db']['base']);
      $query = file_get_contents(SQL . 'clean.sql');
      $sql->multi_query($query);
      $sql->close();
      // write config
      $config = ROOT . "/../core/ConfigInstall.php";
      $cfg = file_get_contents($config);
      $cfg = strtr($cfg, array(
        '#DATABASE_HOST#' => $_SESSION['db']['host'],
        '#DATABASE_USER#' => $_SESSION['db']['user'],
        '#DATABASE_PASS#' => $_SESSION['db']['pass'],
        '#DATABASE_BASE#' => $_SESSION['db']['base'],
      ));
      file_put_contents($config, $cfg);
      // save secret key
      file_put_contents(ROOT . "/../core/MasterKey.php", '<?php define("MASTER_KEY", "'.$_SESSION['secret'].'");');
      header('Location: '.URL.'?step=done');
      die;
      break;
    case "done":
      $tpl = "ready.tpl";
      break;
    case "secret":
      if (count($_POST) > 0)
        $_SESSION['db'] = $_POST;
      //if (count($_POST) > 0)
      //  $_SESSION['migrate'] = $_POST;
      $secret = '';
      $parts = explode(".", $_SERVER['REMOTE_ADDR']);
      $count = count($parts);
      for($i = 0; $i < $count; $i++)
        $secret .= base64_encode(base_convert(substr((time() . uniqid() . $parts[$i]), rand(0,8), rand(16,24)), 16,24));
      $_SESSION['secret'] = $secret;
      $tpl = "secret.tpl";
      break;
    case "migrate":
      if (count($_POST) > 0)
        $_SESSION['db'] = $_POST;
      $tpl = "migrate.tpl";
      break;
    case "database":
      $tpl = "database.tpl";
      break;
    default:
      $tpl = "welcome.tpl";
      break;
  }
  if (isset($tpl)) {
    ob_start();
    require TPL . $tpl;
    $content = ob_get_contents();
    ob_end_clean();
  }

  require TPL . "index.tpl";