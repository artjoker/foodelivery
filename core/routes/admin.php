<?php

  require PATH_ROUTE . "auth.php";


  $app->group('/admin', 'protect', function () use ($app) {
    /**
     * Order list frontend
     */
    $app->get('/orders', function () use ($app) {
      $page   = LIMIT * $app->request->get('p');
      $query  = "
      SELECT
        SQL_CALC_FOUND_ROWS *
      FROM `modx_a_order` o
      JOIN `modx_web_user_attributes` a ON a.internalKey = o.order_client
      ORDER BY o.order_id DESC
      LIMIT " . $page . ", " . LIMIT;
      $orders = $app->db->getAll($query);
      $app->db->getOne("SELECT FOUND_ROWS() AS 'cnt'");
      // TODO finish pagination for orders
      // TODO add filters reaction
      $app->view->setData(array(
        "title"   => $app->lang->get('Orders'),
        "menu"    => "order",
        "content" => $app->view->fetch('orders.tpl', array(
          "app"    => $app,
          "orders" => $orders,
        )),
      ));
    });
    /**
     * Single order frontend
     */
    $app->get('/order/:id', function ($id) use ($app) {
      $query = "
      SELECT
         *
      FROM `modx_a_order` o
      JOIN `modx_web_user_attributes` a ON a.internalKey = o.order_client
      WHERE o.order_id = '" . $app->db->esc($id) . "'";
      $order = $app->db->getOne($query);
      $app->view->setData(array(
        "title"   => "# " . $order['order_id'] . " " . $app->lang->get('order details'),
        "menu"    => "order",
        "content" => $app->view->fetch('order.tpl', array(
          "app"      => $app,
          "order"    => $order,
          "delivery" => json_decode($order['order_client'] != '' ? $order['addr'] : $order['order_delivery'], true),
          "shipping" => $app->db->getAll("SELECT * FROM `modx_a_delivery` ORDER BY delivery_default DESC"),
          "managers" => $app->db->getAll("SELECT username, id FROM `modx_manager_users` ORDER BY username ASC"),
          "products" => $app->db->getAll("
            SELECT *,
              (SELECT product_url FROM `modx_a_products` WHERE product_id = o.product_id) AS 'product_url'
            FROM `modx_a_order_products` o
            JOIN `modx_a_product_strings` s ON s.product_id = o.product_id AND s.translate_lang = '" . LANG . "'
            WHERE o.order_id = " . $order['order_id']),
        )),
      ));
    });
    /**
     * Product list frontend
     */
    $app->get('/products', function () use ($app) {

      $query    = "
        SELECT SQL_CALC_FOUND_ROWS
          *,
          p.product_id AS 'product_id'
        FROM `modx_a_products` p
        LEFT JOIN `modx_a_product_strings` ps ON ps.product_id = p.product_id AND ps.translate_lang = '" . LANG . "'
        WHERE deleted = 0
      ";
      $products = $app->db->getAll($query);
      $app->view->setData(array(
        "title"   => $app->lang->get('Products'),
        "menu"    => "order",
        "content" => $app->view->fetch('products.tpl', array(
          "app"      => $app,
          "products" => $products,
        )),
      ));
    });
    $app->get('/product/:id', function ($id) use ($app) {
      echo "product $id";
    });
    $app->get('/filters', function () use ($app) {
      echo "filters";
    });
    $app->get('/filter/:id', function ($id) use ($app) {
      echo "filter $id";
    });
    $app->get('/feedback', function () use ($app) {
      echo "feedback";
    });
    $app->get('/feedback/:id', function ($id) use ($app) {
      echo "feedback $id";
    });
    $app->get('/banners', function () use ($app) {
      echo "banners";
    });
    $app->get('/banner/:id', function ($id) use ($app) {
      echo "banner $id";
    });
    $app->get('/config', function () use ($app) {
      echo "config";
    });
    // new
    $app->get('/catalog', function () use ($app) {
      echo "catalog";
    });
    $app->get('/catalog/:id', function ($id) use ($app) {
      echo "catalog $id";
    });
    $app->get('/shops', function () use ($app) {
      echo "shops";
    });
    $app->get('/shop/:id', function ($id) use ($app) {
      echo "shop $id";
    });
  });


  $app->hook('slim.after.router', function () use ($app) {
    $app->render('index.tpl', array("app" => $app));
  });
