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
      FROM `orders` o
      #JOIN `modx_web_user_attributes` a ON a.internalKey = o.order_client
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
      FROM `orders` o
      #JOIN `modx_web_user_attributes` a ON a.internalKey = o.order_client
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
      $page  = LIMIT * $app->request->get('p');
      $where = array();
      // filter by category
      if ($app->request->get('category') > 0)
        $where[] = " (SELECT COUNT(*) FROM `lnk_products_categories` WHERE category_id = '" . (int)$app->request->get('category') . "' AND product_id = p.product_id) > 0 ";
      // filter by id or name
      if ($app->request->get('search') != '')
        $where[] = " (
          product_id LIKE '" . $app->db->esc($app->request->get('search')) . "%' OR
          product_code LIKE '" . $app->db->esc($app->request->get('search')) . "%' OR
          product_name LIKE '" . $app->db->esc($app->request->get('search')) . "%'
        ) ";
      if (count($where) > 0)
        $where = "WHERE " . implode(" AND ", $where);
      else
        $where = "";
      $query = "
        SELECT
          SQL_CALC_FOUND_ROWS *,
          (SELECT GROUP_CONCAT(category_name) FROM `categories` WHERE category_id IN (SELECT category_id FROM `lnk_products_categories` WHERE product_id = p.product_id)) AS 'category'
        FROM `products` p
        " . $where . "
        LIMIT " . $page . ", " . LIMIT . "
      ";
      // TODO pagination
      $products = $app->db->getAll($query);
      $app->view->setData(array(
        "title"   => $app->lang->get('Products'),
        "menu"    => "product",
        "content" => $app->view->fetch('products.tpl', array(
          "app"        => $app,
          "products"   => $products,
          "categories" => $app->db->getAll("SELECT * FROM `categories` ORDER BY category_name ASC"),
        )),
      ));
    });

    /**
     * One product frontend
     */
    $app->get('/product/:id', function ($id) use ($app) {
      $query   = "
      SELECT
         *,
         (SELECT GROUP_CONCAT(category_id) FROM `lnk_products_categories` WHERE product_id = p.product_id) AS 'category'
      FROM `products` p
      WHERE p.product_id = '" . $app->db->esc($id) . "'";
      $product = $app->db->getOne($query);
      $app->view->setData(array(
        "title"   => ($id > 0 ? $app->lang->get('Edit') : $app->lang->get('Add new product')) . " " . $product['product_name'],
        "menu"    => "product",
        "content" => $app->view->fetch('product.tpl', array(
          "app"        => $app,
          "product"    => $product,
          "categories" => $app->db->getAll("SELECT * FROM `categories` ORDER BY category_name ASC"),
        )),
      ));
    });
    /**
     * Filters frontend
     */
    $app->get('/filters', function () use ($app) {
      $query = "
        SELECT
          SQL_CALC_FOUND_ROWS * ,
          (SELECT GROUP_CONCAT(category_name) FROM `categories` WHERE category_id IN (SELECT category_id FROM `lnk_filters_categories` WHERE filter_id = f.filter_id)) AS 'category'
        FROM `filters` f
        ORDER BY filter_id DESC";
      $app->view->setData(array(
        "title"   => $app->lang->get('Filters'),
        "menu"    => "filter",
        "content" => $app->view->fetch('filters.tpl', array(
          "app"        => $app,
          "filters"    => $app->db->getAll($query),
          "categories" => $app->db->getAll("SELECT * FROM `categories` ORDER BY category_name ASC"),
        )),
      ));
    });
    /**
     * One filter frontend
     */
    $app->get('/filter/:id', function ($id) use ($app) {
      $query  = "
      SELECT
         *,
         (SELECT GROUP_CONCAT(category_id) FROM `lnk_filters_categories` WHERE filter_id = p.filter_id) AS 'category'
      FROM `filters` p
      WHERE p.filter_id = '" . $app->db->esc($id) . "'";
      $filter = $app->db->getOne($query);
      $app->view->setData(array(
        "title"   => ($id > 0 ? $app->lang->get('Edit') : $app->lang->get('Add new filter')) . " " . $filter['filter_name'],
        "menu"    => "filter",
        "content" => $app->view->fetch('filter.tpl', array(
          "app"        => $app,
          "filter"     => $filter,
          "categories" => $app->db->getAll("SELECT * FROM `categories` ORDER BY category_name ASC"),
        )),
      ));
    });
    /**
     * Banners frontend
     */
    $app->get('/banners', function () use ($app) {
      $app->view->setData(array(
        "title"   => $app->lang->get('Banners'),
        "menu"    => "content",
        "content" => $app->view->fetch('banners.tpl', array(
          "app"        => $app,
          "banners"    => $app->db->getAll("SELECT * FROM `banners` ORDER BY banner_position ASC"),
          "categories" => $app->db->getAll("SELECT * FROM `categories` ORDER BY category_name ASC"),
        )),
      ));
    });
    /**
     * Banners backend
     */
    $app->post('/banners', function () use ($app) {
      // upload image
      $ext = explode("/", $_FILES['banner']['type']);
      if (!in_array(end($ext), array("jpeg", "jpg", "png"))) {
        $app->flash("error", $app->lang->get('Invalid image format (jpg, png only allowed)'));
        $app->redirect('/admin/banners');
      }
      $fileName = uniqid() . '.' . end($ext);
      move_uploaded_file($_FILES['banner']['tmp_name'], IMAGE_STORAGE . DS . 'banners' . DS . $fileName);
      $app->db->query("INSERT INTO `banners` SET
        banner_active	= " . (isset($app->request->post('banner')['active']) ? 1 : 0) . ",
        banner_image	= '" . $fileName . "',
        banner_position	= '" . (int)$app->request->post('banner')['position'] . "',
        banner_link_type =	" . ($app->request->post('banner')['type'] == 'product' ? 1 : 2) . ",
        banner_link_id = " . ($app->request->post('banner')['type'] == 'product' ? (int)$app->request->post('banner')['product'] : (int)$app->request->post('banner')['category']) . "
      ");
      $app->flash("success", $app->lang->get('Banner successfully uploaded'));
      $app->redirect('/admin/banners');
    });
    $app->get('/config', function () use ($app) {
      echo "config";
    });
    // new
    /**
     * Catalog frontend
     */
    $app->get('/catalog', function () use ($app) {
      $app->view->setData(array(
        "title"   => $app->lang->get('Catalog'),
        "menu"    => "content",
        "content" => $app->view->fetch('catalog.tpl', array(
          "app"        => $app,
          "categories" => $app->db->getAll("SELECT * FROM `categories` ORDER BY category_name ASC"),
        )),
      ));
    });
    /**
     * Catalog backend
     */
    $app->post('/catalog', function () use ($app) {
      if (count($app->request->post('add')) > 0) {
        $app->db->query("INSERT INTO `categories` SET
          category_name = '" . $app->db->esc($app->request->post("add")['name']) . "'
        ");
        $app->flash("success", $app->lang->get('Category successfully added to catalog'));
        $app->flashKeep();
        $app->redirect('/admin/catalog');
      }
      if (count($app->request->post('edit')) > 0) {
        $app->db->query("UPDATE `categories` SET
          category_name = '" . $app->db->esc($app->request->post("edit")['name']) . "',
          category_active = '" . (int)$app->request->post("edit")['active'] . "'
          WHERE category_id = '" . (int)$app->request->post("edit")['id'] . "'
        ");
        $app->flash("success", $app->lang->get('Category successfully updated'));
        $app->flashKeep();
        $app->redirect('/admin/catalog');
      }

    });
    /**
     * Shop frontend
     */
    $app->get('/shops', function () use ($app) {
      $app->view->setData(array(
        "title"   => $app->lang->get('Shops'),
        "menu"    => "content",
        "content" => $app->view->fetch('shops.tpl', array(
          "app"   => $app,
          "shops" => $app->db->getAll("SELECT * FROM `shops` ORDER BY shop_name ASC"),
        )),
      ));
    });
    /**
     * Shop backend
     */
    $app->post('/shops', function () use ($app) {
      $app->db->query("TRUNCATE TABLE `shops`");
      foreach ($app->request->post('shop')['name'] as $key => $value)
        $app->db->query("INSERT INTO `shops` SET
          shop_name = '" . $app->db->esc($app->request->post('shop')['name'][$key]) . "',
          shop_addr = '" . $app->db->esc($app->request->post('shop')['addr'][$key]) . "',
          shop_active = '" . (isset($app->request->post('shop')['active'][$key]) ? 1 : 0) . "',
          shop_lat = '" . $app->db->esc($app->request->post('shop')['lat'][$key]) . "',
          shop_lng = '" . $app->db->esc($app->request->post('shop')['lng'][$key]) . "'
        ");

      $app->flash("success", $app->lang->get('Shop list updated'));
      $app->redirect('/admin/shops');
    });
    /**
     * Users frontend
     */
    $app->get('/users', function () use ($app) {
      $page   = LIMIT * $app->request->get('p');
      $query = "
     SELECT *,
       (SELECT COUNT(*) FROM `orders` WHERE order_client = user_id) AS 'orders_count'
     FROM `users`
     ".($app->request->get('search') != '' ? "
     WHERE user_firstname LIKE '%".$app->db->esc($app->request->get('search'))."%'
     OR user_lastname LIKE '%".$app->db->esc($app->request->get('search'))."%'
     OR user_email LIKE '%".$app->db->esc($app->request->get('search'))."%'
     OR user_phone LIKE '%".$app->db->esc($app->request->get('search'))."%'" : "")."
     ORDER BY user_id DESC
     LIMIT ".$page.", ".LIMIT;
      // TODO pagination
      $users = $app->db->getAll($query);
      $app->view->setData(array(
        "title"   => $app->lang->get('Users'),
        "menu"    => "users",
        "content" => $app->view->fetch('users.tpl', array(
          "app"   => $app,
          "users" => $users,
        )),
      ));
    });
    /**
     * Single user frontend
     */
    $app->get('/users/:id', function ($id) use ($app) {
      $user = $app->db->getOne(" SELECT * FROM `users` WHERE user_id = '".(int)$id."'");
      $addr = json_decode($user['user_address'], true);
      $app->view->setData(array(
        "title"   => $app->lang->get('Edit user profile'),
        "menu"    => "users",
        "content" => $app->view->fetch('user.tpl', array(
          "app"   => $app,
          "user" => $user,
          "addr" => $addr,
        )),
      ));
    });
    /**
     * Single user backend
     */
    $app->post('/users/:id', function ($id) use ($app) {
      $user = $app->request->post('user');
      $app->db->query("UPDATE `users` SET
     user_firstname = '".$app->db->esc($user['firstname'])."',
     user_lastname = '".$app->db->esc($user['lastname'])."',
     user_email = '".$app->db->esc($user['email'])."',
     user_phone = '".$app->db->esc($user['phone'])."',
     user_active = '".(isset($user['active']) ? 1 : 0)."',
     user_address = '".$app->db->esc(json_encode($user['addr']))."'
     WHERE user_id = '".(int)$id."'
   ");
      $app->flash("success", $app->lang->get('User data successfully updated'));
      $app->redirect('/admin/users');
    });

    /**
     * Managers frontend
     */
    $app->get('/managers', function () use ($app) {
      $page   = LIMIT * $app->request->get('p');
      $query = "
         SELECT m.*,
          (SELECT shop_name FROM `shops` WHERE shop_id = m.shop_id) AS 'shop'
         FROM `managers` m
         ORDER BY manager_id DESC
         LIMIT ".$page.", ".LIMIT;
      // TODO pagination
      $managers = $app->db->getAll($query);
      $app->view->setData(array(
        "title"   => $app->lang->get('Managers'),
        "menu"    => "users",
        "content" => $app->view->fetch('managers.tpl', array(
          "app"   => $app,
          "managers" => $managers,
        )),
      ));
    });



  });


  $app->hook('slim.after.router', function () use ($app) {
    $app->render('index.tpl', array("app" => $app));
  });
