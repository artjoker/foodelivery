<?php

  $app->post('/api/', function () use ($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $request  = json_decode(file_get_contents('php://input'));
    $response = array();
    /**
     * get_catalog
     */
    if ($request->function == "get_catalog") {
      $file = "android";
      if (!empty($request->data->date)) {
        $dt   = (int)$request->data->date;
        $file = "android_update";
      } else $dt = 0;
      /**
       * Products
       */
      $query                = "
				SELECT
					product_id AS 'id',
					product_name AS 'name',
					product_description AS 'description',
					'none' AS 'manufacturer',
					'none' AS 'volume',
					product_price AS 'price',
					CONCAT('" . URL_ROOT . "data/products/', p.product_id, '/', p.product_cover) AS 'cover',
					product_visible AS 'visible',
					product_deleted AS 'deleted'
				FROM `products` p
				WHERE product_visible = 1 " . ($dt > 0 ? " AND product_updated > '" . date("Y-m-d H:i:s", $dt) . "'" : "");
      $response['products'] = $app->db->getAll($query);
      foreach ($response['products'] as $key => $product) {
        $values = $app->db->getAll("SELECT category_id FROM `lnk_products_categories` WHERE product_id = " . $product['id']);
        foreach ($values as $value) {
          $response['products'][$key]['category_id'][] = $value['category_id'];
        }
      }
      /**
       * Categories
       */
      $response['categories'] = $app->db->getAll("SELECT category_id AS 'id', category_name AS 'name', 0 AS 'primary_id' FROM `categories`");
      /**
       * Delivery
       */
      $response['delivery_types'] = $app->db->getAll("SELECT delivery_id AS 'id', delivery_name AS 'name', delivery_cost AS 'cost' FROM `delivery`");
      /**
       *  Filters
       */
      $response['filters'] = $app->db->getAll("SELECT filter_id AS 'id', filter_name AS 'name', filter_type AS 'type' FROM `filters`");
      foreach ($response['filters'] as $key => $value) {
        $values = $app->db->getAll("SELECT category_id FROM `lnk_filters_categories` WHERE filter_id = " . $value['id']);
        foreach ($values as $value) {
          $response['filters'][$key]['category_id'][] = $value['category_id'];
        }
      }
      /**
       * Filter values link
       */
      $response['filter_link'] = $app->db->getAll("SELECT filter_id, product_id, value_id AS 'filter_value_id' FROM `lnk_products_values`");
      /**
       * Filter values
       */
      $response['filter_values'] = $app->db->getAll("
        SELECT
          v.value_id AS 'filter_value_id',
          IF (f.filter_type = 3, v.string, v.number) AS 'filter_value',
          IF (f.filter_type = 3, 'str', 'int')AS 'filter_type'
        FROM `values` v
        JOIN `lnk_products_values` pv ON pv.value_id = v.value_id
        JOIN `filters` f ON f.filter_id = pv.filter_id");
      // write json file
      $bd = fopen(PATH_CACHE . $file . ".json", "w");
      fwrite($bd, json_encode($response));
      fclose($bd);
      // prepare response
      $response = array('response_code' => 0, 'data' => array("path" => "/cache/" . $file . ".json"));
      echo json_encode($response);
      $app->stop();
    }

    // check signature
    if ($request->signature != md5(json_encode($request->data) . API_KEY))
      $response = array(
        "response_code" => 100,
        "data"          => array("error" => "Signature doesn't match"),
      );
    else
      switch ($request->function) {
        /**
         * Return banner list resized towidth/height in px
         */
        case "banners":
          $banners      = $app->db->getAll("SELECT * FROM `banners` WHERE banner_active = 1 ORDER BY banner_position ASC");
          $param['far'] = 1;
          if ($request->data->width > 0) $param['w'] = $request->data->width;
          if ($request->data->height > 0) $param['h'] = $request->data->height;
          foreach ($banners as $banner) {
            $response['data']['banners'][] = array(
              "image"       => rtrim(URL_ROOT, '/') . $app->image->resize(IMAGE_STORAGE . '/banners/' . $banner['banner_image'], $param, 'banners'),
              "product_id"  => ($banner['banner_link_type'] == 1 ? $banner['banner_link_id'] : 0),
              "category_id" => ($banner['banner_link_type'] == 2 ? $banner['banner_link_id'] : 0),
            );
          }
          break;
        /**
         * Return shop list with shop names, phone, address and coordinates
         */
        case "shoplist":
          $response['data']['shoplist'] = $app->db->getAll("
            SELECT
             shop_name AS 'name',
             shop_addr AS 'address',
             shop_phone AS 'phone',
             shop_lat AS 'latitude',
             shop_lng AS 'longitude'
            FROM `shops`
            WHERE shop_active = 1
            ORDER BY shop_id ASC");
          break;
        /**
         * User registration
         */
        case "reg":
          $app->db->query("DELETE FROM `users` WHERE user_email = 'kharkiv.adminko@gmail.com'");
          $check = $app->db->getOne(' SELECT COUNT(*) AS "cnt" FROM `users` WHERE user_email = "' . $app->db->esc($request->data->email) . '" ');
          if ($check['cnt'] > 0) {
            $error = "User with that email already registered! Try another email";
          } else {
            $password             = substr(md5(uniqid()), 10, 10);
            $request->data->index = isset($request->data->index) ? $request->data->index : '';
            $app->db->query("
              INSERT INTO `users` SET
                user_reg_date = NOW(),
                user_last_visit = NOW(),
                user_email = '" . $app->db->esc($request->data->email) . "',
                user_phone = '" . $app->db->esc($request->data->phone) . "',
                user_firstname = '" . $app->db->esc($request->data->name) . "',
                user_lastname = '" . $app->db->esc($request->data->surname) . "',
                user_pass = '" . md5($password) . "',
                user_address = '" . $app->db->esc(
                json_encode(array(
                  "index" => $request->data->index,
                  "city"  => $request->data->city,
                  "ave"   => $request->data->street,
                  "house" => $request->data->house_number,
                  "room"  => $request->data->room_number,
                ))) . "' ");
            $user_id               = $app->db->getID();
            $user                  = $app->db->getOne("SELECT * FROM `users` WHERE user_id = " . $user_id);
            $user['brand']         = BRAND;
            $user['user_password'] = $password;
            $app->mail->send(
              $request->data->email,
              EMAIL_SUBJECT_REG,
              EMAIL_BODY_REG,
              $user
            );

            $response['response_code']           = 0;
            $response['data']['user']['user_id'] = $user_id;
          }
          break;
        /**
         * User authentication
         */
        case "auth":
          $user = $app->db->getOne("SELECT * FROM `users` WHERE user_email = '" . $app->db->esc($request->data->email) . "'");
          if (0 == count($user))
            $error = "User with that email not found! Try another email";
          elseif ($request->data->pass != $user['user_pass'])
            $error = "Wrong password";
          else {
            $addr     = json_decode($user['user_address'], true);
            $response = Array(
              "response_code" => 0,
              "data"          => array(
                "user" =>
                  array(
                    "user_id" => $user['user_id'],
                    "surname" => $user['user_lastname'],
                    "name"    => $user['user_firstname'],
                    "email"   => $user['user_email'],
                    "phone"   => $user['user_phone'],
                    "index"   => $addr['index'],
                    "city"    => $addr['city'],
                    "street"  => $addr['ave'],
                    "house"   => $addr['house'],
                    "flat"    => $addr['room'],
                  )));
          }
          break;
        /**
         * User password recovery
         */
        case "forgot":
          $password = substr(md5(uniqid()), 10, 10);
          $app->db->query("UPDATE `users` SET user_pass = '" . md5($password) . "' WHERE user_email = '" . $app->db->esc($request->data->email) . "'");
          if ($app->db->getAffectedRows() > 0) {
            $user                  = $app->db->getOne("SELECT * FROM `users` WHERE user_email = '" . $app->db->esc($request->data->email) . "'");
            $user['brand']         = BRAND;
            $user['user_password'] = $password;
            $app->mail->send(
              $user['user_email'],
              EMAIL_SUBJECT_RECOVERY,
              EMAIL_BODY_RECOVERY,
              $user
            );
            $response = array("response_code" => 0);
          } else
            $error = "Email not registered! Check it";
          break;
        /**
         * User profile update
         */
        case "update_user":
          // TODO update user profile
          break;
        /**
         * Get product info
         */
        case "product_info":
          // TODO Get product info
          break;
        /**
         * Create new order
         */
        case "order":
          // TODO Create new order
          break;
        /**
         * User order history
         */
        case "history":
          // TODO User order history
          break;
      }
    // prepare response output
    if (!empty($error))
      $response = array("response_code" => 100, "data" => array("error" => $error));
    echo json_encode($response);
    $app->stop();
  });
