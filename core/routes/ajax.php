<?php

  $app->post('/admin/ajax/:get', 'protect', function($get) use ($app) {
    if (!$app->request->isAjax()) die('Bad AJAX request');
    switch ($get) {
      case "images":
        echo "Product ".$app->request->post('product_id')." images";
        break;
    }

    $app->stop();
  });