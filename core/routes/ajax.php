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
        break;
      case "update_banner":
        foreach($app->request->post("banner") as $key => $value)
          $app->db->query("UPDATE `banners` SET
            banner_active	= " . (isset($value['active']) ? 1 : 0) . ",
            banner_position	= '" . (int)$value['position'] . "',
            banner_link_type =	" . ($value['type'] == 'product' ? 1 : 2) . ",
            banner_link_id = " . (int)($value['type'] == 'product' ? $value['product'] : $value['category']) . "
            WHERE banner_id = ".(int)$key."
          ");
        break;
      case "update_delivery":
        foreach($app->request->post("delivery") as $key => $value)
          $app->db->query("UPDATE `delivery` SET
            delivery_active	= " . (isset($value['active']) ? 1 : 0) . ",
            delivery_name	= '" . $app->db->esc($value['name']) . "',
            delivery_cost =	" . $app->db->esc($value['cost']) . "
            WHERE delivery_id = ".(int)$key."
          ");
        break;
    }

    $app->stop();
  });