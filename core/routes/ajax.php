<?php

  $app->post('/admin/ajax/:get', 'protect', function($get) use ($app) {
    //    if (!$app->request->isAjax()) die('Bad AJAX request');
    switch ($get) {
      case "product_images":
        $images = glob(IMAGE_STORAGE . DS . "products" . DS . (int)$app->request->post('product_id') . DS . "*");
        if (is_array($images))
          foreach ($images as $image)
            $app->render("image.tpl", array("app" => $app, "image" => $image));
        break;
      case "upload":
        $folder = IMAGE_STORAGE . DS . "products" . DS . $app->request->post('folder');
        if (!file_exists($folder)) {
          mkdir($folder);
          chmod($folder, 0777);
        }
        foreach ($_FILES['uploader']['tmp_name'] as $key => $value) {
          $extension = strtolower(pathinfo($_FILES['uploader']['name'][$key], PATHINFO_EXTENSION ));
          if (in_array($extension, array("jpg", "png")))
            move_uploaded_file($value, $folder.DS. preg_replace('/\D+/', '', microtime()).".".$extension);
        }
        break;
      case "delete_image":
        unlink(IMAGE_STORAGE . DS . "products" . DS . $app->request->post('product_id') . DS . $app->request->post('image'));
        break;
      case "category":
        echo json_encode($app->db->getOne("SELECT * FROM `categories` WHERE category_id = '".(int)$app->request->post("category_id")."'"));
    }

    $app->stop();
  });