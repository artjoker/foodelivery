<?php

  /**
   * Sign in frontend
   */
  $app->get('/', function () use ($app) {
    if (isset($_SESSION['admin']))
      $app->response->redirect(URL_ROOT.'admin/');
    else {
      $app->render('auth.tpl', array('app' => $app));
      $app->stop();
    }
  });

  /**
   * Sign in backend
   */
  $app->post('/', function () use ($app) {
    if (2 != count($app->request->post('auth')))
      $app->response->redirect(URL_ROOT.'admin/');
    else {
      $auth = $app->request->post('auth');
      $manager = $app->db->getOne('SELECT * FROM `managers` WHERE manager_email = "'.$app->db->esc($auth['email']).'"');
      if ($manager['manager_email'] == '') {
        $app->flash("error", $app->lang->get('Manager not found'));
        $app->redirect(URL_ROOT .'/');
      }
      if ($manager['manager_pass'] != md5($auth['pass'])) {
        $app->flash("email", filter_var($auth['email'], FILTER_SANITIZE_EMAIL));
        $app->flash("error", $app->lang->get('Invalid password'));
        $app->redirect(URL_ROOT .'/');
      }
      $_SESSION['admin'] = $manager;
      $app->response->redirect(URL_ROOT.'admin/');
    }
    $app->stop();
  });