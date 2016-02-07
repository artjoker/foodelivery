<?php

  $app->post('/api', function () use ($app) {
    $request = json_decode(file_get_contents('php://input'));

    if ($request->function == "get_catalog") {
      $file = "android";
      if (!empty($request->data->date)) {
        $dt   = (int)$request->data->date;
        $file = "android_update";
      } else $dt = 0;
      $query    = "
				SELECT
					p.product_id AS 'id',
					s.product_name AS 'name',
					s.product_description AS 'description',
					'none' AS 'manufacturer',
					'none' AS 'volume',
					p.product_price AS 'price',
					CONCAT('assets/images/items/', p.product_id, '/', p.product_cover) AS 'cover',
					p.product_visible AS 'visible',
					p.deleted
				FROM `modx_a_products` p
				JOIN `modx_a_product_strings` s ON p.product_id = s.product_id AND s.translate_lang = '" . LANG . "'
				WHERE p.product_visible = 1 " . ($dt > 0 ? " AND p.product_edited > '" . date("Y-m-d H:i:s", $dt) . "'" : "");
      $products = $app->db->getAll($query);
      foreach ($products as $product) {
        $query      = "
					SELECT
						modx_id
					FROM `modx_a_pro2cat`
					WHERE product_id = '" . $product['id'] . "' ";
        $categories = $app->db->getAll($query);
        foreach ($categories as $category_id) {
          $product['category_id'][] = $category_id['modx_id'];
        }
        $base['data']['products'][] = $product;
      }

      // categories
      $query = "
				SELECT
					id,
					IF (parent = '" . $modx->config['shop_catalog_root'] . "', 0, parent) AS 'primary_id',
					pagetitle_" . $lang . " AS 'name'
				FROM `modx_site_content`
				WHERE published = 1 AND deleted = 0 AND template = " . $modx->config['shop_tpl_category'] . " and id != '" . $modx->config['shop_catalog_root'] . "'";

      $base['data']['categories'] = $modx->db->makeArray($modx->db->query($query));
      // delivery
      $query                          = "
				SELECT
					delivery_id AS 'id',
					delivery_title AS 'name',
					delivery_cost AS 'cost'
				FROM `modx_a_delivery`";
      $base['data']['delivery_types'] = $modx->db->makeArray($modx->db->query($query));
      // filters
      $query   = "
				SELECT
					filter_id AS 'id',
					filter_name AS 'name',
					filter_type AS 'type'
				FROM `modx_a_filters`";
      $filters = $modx->db->query($query);
      while ($filter = $modx->db->getRow($filters)) {
        $query      = "
					SELECT
						modx_id
					FROM `modx_a_fc`
					WHERE filter_id = '" . $filter['id'] . "' ";
        $categories = $modx->db->query($query);
        while ($category_id = $modx->db->getRow($categories)) {
          $filter['category_id'][] = $category_id['modx_id'];
        }
        $base['data']['filters'][] = $filter;
      }
      // linking filters with category
      $query                       = "
				SELECT filter_id, product_id, value_id AS 'filter_value_id'
				FROM `modx_a_pro2val`";
      $base['data']['filter_link'] = $modx->db->makeArray($modx->db->query($query));

      $query                         = "
				SELECT
					value_id AS 'filter_value_id',
					IF (val = '', num, val) AS 'filter_value',
					IF (val = '', 'int', 'str') AS 'filter_type'
				FROM `modx_a_values`";
      $base['data']['filter_values'] = $modx->db->makeArray($modx->db->query($query));
      // write base to json file
      $bd = fopen(MODX_BASE_PATH . "/assets/files/" . $file . ".json", "w");
      fwrite($bd, json_encode($base));
      fclose($bd);

      // preapare response
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
