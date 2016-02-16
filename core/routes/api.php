<?php

  $app->post('/api/', function () use ($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $request = json_decode(file_get_contents('php://input'));

    if ($request->function == "get_catalog") {
      $file = "android";
      if (!empty($request->data->date)) {
        $dt   = (int)$request->data->date;
        $file = "android_update";
      } else $dt = 0;
      /**
       * Products
       */
      $query    = "
				SELECT
					product_id AS 'id',
					product_name AS 'name',
					product_description AS 'description',
					'none' AS 'manufacturer',
					'none' AS 'volume',
					product_price AS 'price',
					CONCAT('data/products/', p.product_id, '/', p.product_cover) AS 'cover',
					product_visible AS 'visible',
					product_deleted AS 'deleted'
				FROM `products` p
				WHERE product_visible = 1 " . ($dt > 0 ? " AND product_updated > '" . date("Y-m-d H:i:s", $dt) . "'" : "");

      //$response['products'] = $app->db->getAll($query);
      //foreach ($response['products'] as $key => $product) {
      //  $values = $app->db->getAll("SELECT category_id FROM `lnk_products_categories` WHERE product_id = ".$product['id']);
      //  foreach ($values as $value) {
      //    $response['products'][$key]['category_id'][] = $value['category_id'];
      //  }
      //}
      /**
       * Categories
       */
      //$response['categories'] = $app->db->getAll("SELECT category_id as 'id', category_name as 'name', 0 as 'primary_id' FROM `categories`");
      /**
       * Delivery
       */
      //$response['delivery_types'] = $app->db->getAll("SELECT delivery_id as 'id', delivery_name as 'name', delivery_cost as 'cost' FROM `delivery`");
      /**
       *  Filters
       */
      $response['filters'] = $app->db->getAll("SELECT filter_id as 'id', filter_name as 'name', filter_type as 'type' FROM `filters`");
      foreach($response['filters'] as $key => $value) {
        $values = $app->db->getAll("SELECT category_id FROM `lnk_filters_categories` WHERE filter_id = ".$value['id']);
        foreach ($values as $value) {
          $response['filters'][$key]['category_id'][] = $value['category_id'];
        }
      }






      $bd = fopen(MODX_BASE_PATH."/assets/files/".$file.".json", "w");
      fwrite($bd, json_encode($base));
      fclose($bd);

      echo json_encode($response);
      $app->stop();
      // prepare response
      $response = array('response_code' => 0, 'data' => array("path" => "/cache/database.json"));
    }
    if ($request->signature != md5(json_encode($request->data) . API_KEY))
      $response = array("response_code" => 100, "data" => array("error" => "Signature doesn't match"));
    else
      switch ($request->function) {
        case "get_catalog":
          $response = [];

          break;
      }
    echo json_encode($response);
    $app->stop();
  });
