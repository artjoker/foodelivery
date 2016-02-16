<?php

  /**
   * Sign in frontend
   */
  $app->get('/', function () use ($app) {
    if (isset($_SESSION['admin']))
      $app->response->redirect('/admin');
    else {
      $app->render('auth.tpl', array('app' => $app));
      $app->stop();
    }
  });

  /**
   * Sign in backend
   */
  $app->post('/', function () use ($app) {
    var_dump($app->request->post('auth'));
    if (2 != count($app->request->post('auth')))
      $app->response->redirect('/admin');
    else {
      // TODO check admin credentials
      // TODO enter for debug
      $app->render('auth.tpl', array('app' => $app));
      $app->stop();
    }
  });