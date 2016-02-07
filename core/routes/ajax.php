<?php

  $app->post('/admin/ajax/:get', 'protect', function($get) use ($app) {
    if (!$app->request->isAjax()) die('Bad AJAX request');
    switch ($get) {
      case "product_images":
        $images = glob(IMAGE_STORAGE . (int)$app->request->post('product_id') . DS . "*");
        if (is_array($images))
          foreach ($images as $image)
            $app->render("image.tpl", array("app" => $app, "image" => $image));
        break;
    }

    $app->stop();
  });