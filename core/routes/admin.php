<?php

  $app->hook('slim.before.router', function () use ($app) {
    $config = $app->db->getAll("SELECT * FROM `config`");
    foreach ($config as $value)
      define($value['key'], $value['value']);
  });

  require PATH_ROUTE . "auth.php";


  $app->group('/admin', 'protect', function () use ($app) {
    /**
     * Order list frontend
     */
    $app->get('/orders', function () use ($app) {
      $page  = LIMIT * $app->request->get('p');
      $where = array();
      if ($app->request->get("status") > 0) $where[] = 'order_status = "' . (int)$app->request->get('status') . '"';
      if ($app->request->get("from") != '') $where[] = 'order_created > "' . (int)$app->request->get('from') . '"';
      if ($app->request->get("to") != '') $where[] = 'order_created < "' . (int)$app->request->get('to') . '"';
      if ($app->request->get("user") != '') $where[] = 'order_client = "' . (int)$app->request->get('user') . '"';
      if ($app->request->get("manager") != '') $where[] = 'order_manager = "' . (int)$app->request->get('manager') . '"';
      $query  = "
      SELECT
        SQL_CALC_FOUND_ROWS *
      FROM `orders` o
      JOIN `users` u ON u.user_id = o.order_client
      " . (count($where) > 0 ? " WHERE " . implode(" AND ", $where) : "") . "
      ORDER BY o.order_id DESC
      LIMIT " . $page . ", " . LIMIT;
      $orders = $app->db->getAll($query);
      $app->db->getOne("SELECT FOUND_ROWS() AS 'cnt'");
      // TODO finish pagination for orders
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
      JOIN `users` u ON u.user_id = o.order_client
      WHERE o.order_id = '" . $app->db->esc($id) . "'";
      $order = $app->db->getOne($query);
      $app->view->setData(array(
        "title"   => "# " . $order['order_id'] . " " . $app->lang->get('order details'),
        "menu"    => "order",
        "content" => $app->view->fetch('order.tpl', array(
          "app"        => $app,
          "order"      => $order,
          "delivery"   => json_decode($order['order_client'] != '' ? $order['user_address'] : $order['order_delivery'], true),
          "shipping"   => $app->db->getAll("SELECT * FROM `delivery` ORDER BY delivery_name DESC"),
          "managers"   => $app->db->getAll("SELECT * FROM `managers` ORDER BY manager_name ASC"),
          "categories" => $app->db->getAll("SELECT * FROM `categories` ORDER BY category_name ASC"),
          "products"   => $app->db->getAll("
            SELECT *
            FROM `products` p
            JOIN `lnk_order_products` op ON op.product_id = p.product_id
            WHERE op.order_id = " . $order['order_id']),
        )),
      ));
    });
    /**
     * Single order backend
     */
    $app->post('/order/:id', function ($id) use ($app) {
      $app->db->query("UPDATE `orders` SET
        order_manager = '" . (int)$app->request->post('order')['manager'] . "',
        order_status = '" . (int)$app->request->post('order')['status'] . "',
        order_status_pay = '" . (isset($app->request->post('order')['payment']) ? 1 : 0) . "',
        order_comment = '" . $app->db->esc($app->request->post('order')['comment']) . "',
        order_delivery = '" . $app->db->esc(json_encode($app->request->post('order')['delivery'])) . "',
        order_delivery_cost = '" . $app->db->esc($app->request->post('order')['delivery_cost']) . "',
        order_cost = '" . $app->db->esc($app->request->post('order')['total']) . "'
        WHERE order_id = '" . (int)$id . "'
      ");
      $app->db->query("DELETE FROM `lnk_order_products` WHERE order_id = '" . (int)$id . "'");
      foreach ($app->request->post('order')['item']['id'] as $key => $value)
        $app->db->query("INSERT INTO `lnk_order_products` SET
          order_id = '" . (int)$id . "',
          product_id = '" . (int)$value . "',
          product_count = '" . $app->db->esc($app->request->post('order')['item']['count'][$key]) . "',
          product_price = '" . $app->db->esc($app->request->post('order')['item']['price'][$key]) . "'
        ");
      // notify customer
      if (isset($app->request->post('order')['notify'])) {
        switch ((int)$app->request->post('order')['status']) {
          case 0:
            $status = $app->lang->get("Deleted");
            break;
          case 1:
            $status = $app->lang->get("New");
            break;
          case 2:
            $status = $app->lang->get("Sent");
            break;
          case 3:
            $status = $app->lang->get("Delivered");
            break;
        }
        $app->mail->send(
          $app->request->post('order')['notify'],
          str_replace('{order_id}', $id, EMAIL_SUBJECT_ORDER_CHANGE),
          strtr(EMAIL_BODY_ORDER_CHANGE, array('{order_id}' => $id, '{order_status}' => $status))
        );
      }
      $app->flash("success", $app->lang->get('Order successfully updated'));
      $app->redirect('/admin/order/' . $id);
    });
    /**
     * Product list frontend
     */
    $app->get('/products', function () use ($app) {
      $page    = LIMIT * $app->request->get('p');
      $where   = array();
      $where[] = " p.product_deleted = 0 ";
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
      $filters  = "
        SELECT
        *,
        (SELECT COUNT(*) FROM `lnk_filters_categories` WHERE category_id IN (".$product['category'].")) AS 'linked',
        (SELECT IF (f.filter_type = 3, string, number) FROM `values` WHERE value_id =
          (SELECT value_id FROM `lnk_products_values` WHERE filter_id = f.filter_id AND product_id = ".$product['product_id'].")) AS 'value'
        FROM `filters` f
        HAVING linked > 0
      ";
      $app->view->setData(array(
        "title"   => ($id > 0 ? $app->lang->get('Edit') : $app->lang->get('Add new product')) . " " . $product['product_name'],
        "menu"    => "product",
        "content" => $app->view->fetch('product.tpl', array(
          "app"        => $app,
          "product"    => $product,
          "filters"     => $app->db->getAll($filters),
          // TODO доделать вывод фильтров в товаре
          "categories" => $app->db->getAll("SELECT * FROM `categories` ORDER BY category_name ASC"),
        )),
      ));
    });
    /**
     * One product backend
     */
    $app->post('/product/:id', function ($id) use ($app) {
      if ($id == 0) {
        $app->db->query("INSERT INTO `products` SET
          product_name = '".$app->db->esc($app->request->post('product')['name'])."',
          product_code = '".$app->db->esc($app->request->post('product')['code'])."',
          product_price = '".$app->db->esc($app->request->post('product')['price'])."',
          product_visible = '".(isset($app->request->post('product')['available']) ? 1 : 0)."',
          product_available = '".$app->db->esc($app->request->post('product')['status'])."',
          product_description = '".$app->db->esc($app->request->post('product')['description'])."',
          product_intro = '".$app->db->esc($app->request->post('product')['intro'])."',
          product_cover = '".$app->db->esc($app->request->post('product')['cover'])."'
        ");
        $id = $app->db->getID();
      } else {
        $id = (int)$id;
        $app->db->query("UPDATE `products` SET
          product_name = '".$app->db->esc($app->request->post('product')['name'])."',
          product_code = '".$app->db->esc($app->request->post('product')['code'])."',
          product_price = '".$app->db->esc($app->request->post('product')['price'])."',
          product_visible = '".(isset($app->request->post('product')['available']) ? 1 : 0)."',
          product_available = '".$app->db->esc($app->request->post('product')['status'])."',
          product_description = '".$app->db->esc($app->request->post('product')['description'])."',
          product_intro = '".$app->db->esc($app->request->post('product')['intro'])."',
          product_cover = '".$app->db->esc($app->request->post('product')['cover'])."'
          WHERE product_id = '$id'
        ");
      }
      // update categories
      $app->db->query("DELETE FROM `lnk_products_categories` WHERE product_id = '$id'");
      if (isset($app->request->post('product')['categories']))
        foreach ($app->request->post('product')['categories']as $item)
          $app->db->query("INSERT INTO `lnk_products_categories` SET
            product_id = '$id',
            category_id = '".(int)$item."'");
      var_dump($app->request->post());
      die;
      // update filters
      $app->db->query("DELETE FROM `lnk_products_values` WHERE product_id = '$id'");
      if (0 < count($app->request->post('filter')))
        foreach ($app->request->post('filter') as $key => $value){
          list($type, $fid) = explode("|", $key);
          switch ($type) {
            case 1: // Numeric
            case 3: // AND

              break;
          }
          // TODO check value unique
          // TODO link filter+product+value

          $app->db->query("INSERT INTO `lnk_products_values` SET
            product_id = '$id',
            filter_id = '".(int)$value."'");

        }
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
        " . ((int)$app->request->get('category') > 0 ? "WHERE filter_id  IN (SELECT filter_id FROM `lnk_filters_categories` WHERE category_id = '" . (int)$app->request->get("category") . "')" : "") . "
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
     * One filter backend
     */
    $app->post('/filter/:id', function ($id) use ($app) {
      if ($id == 0) {
        $app->db->query("INSERT INTO `filters` SET
          filter_name = '" . $app->db->esc($app->request->post('filter')['name']) . "',
          filter_description = '" . $app->db->esc($app->request->post('filter')['description']) . "',
          filter_type = '" . (int)$app->request->post('filter')['type'] . "'
        ");
        $id = $app->db->getID();
      } else {
        $app->db->query("UPDATE `filters` SET
          filter_name = '" . $app->db->esc($app->request->post('filter')['name']) . "',
          filter_description = '" . $app->db->esc($app->request->post('filter')['description']) . "',
          filter_type = '" . (int)$app->request->post('filter')['type'] . "'
          WHERE filter_id = '" . (int)$id . "'
        ");
      }
      $app->db->query("DELETE FROM `lnk_filters_categories` WHERE filter_id = '" . (int)$id . "'");
      if (isset($app->request->post('filter')['category']))
        foreach ($app->request->post('filter')['category'] as $item) {
          $app->db->query("INSERT INTO `lnk_filters_categories` SET
            filter_id = '" . (int)$id . "',
            category_id = '" . (int)$item . "'
            ");
        }
      $app->flash("success", $app->lang->get('Filter successfully added'));
      $app->redirect('/admin/filters');
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
    /**
     * Config frontend
     */
    $app->get('/config', function () use ($app) {
      $app->view->setData(array(
        "title"   => $app->lang->get('Configuration'),
        "menu"    => "config",
        "content" => $app->view->fetch('config.tpl', array(
          "app" => $app,
        )),
      ));
    });
    /**
     * Config backend
     */
    $app->post('/config', function () use ($app) {
      foreach ($app->request->post() as $key => $value) {
        $query = "INSERT INTO `config` SET
          `key` = '" . strtoupper($key) . "',
          `value` = '" . $app->db->esc($value) . "'
        ON DUPLICATE KEY UPDATE
          `value` = '" . $app->db->esc($value) . "'
        ";
        $app->db->query($query);
      }
      $app->flash("success", $app->lang->get('Config updated'));
      $app->redirect('/admin/config');
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
      $page  = LIMIT * $app->request->get('p');
      $query = "
     SELECT *,
       (SELECT COUNT(*) FROM `orders` WHERE order_client = user_id) AS 'orders_count'
     FROM `users`
     " . ($app->request->get('search') != '' ? "
     WHERE user_firstname LIKE '%" . $app->db->esc($app->request->get('search')) . "%'
     OR user_lastname LIKE '%" . $app->db->esc($app->request->get('search')) . "%'
     OR user_email LIKE '%" . $app->db->esc($app->request->get('search')) . "%'
     OR user_phone LIKE '%" . $app->db->esc($app->request->get('search')) . "%'" : "") . "
     ORDER BY user_id DESC
     LIMIT " . $page . ", " . LIMIT;
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
      $user = $app->db->getOne(" SELECT * FROM `users` WHERE user_id = '" . (int)$id . "'");
      $addr = json_decode($user['user_address'], true);
      $app->view->setData(array(
        "title"   => $app->lang->get('Edit user profile'),
        "menu"    => "users",
        "content" => $app->view->fetch('user.tpl', array(
          "app"  => $app,
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
     user_firstname = '" . $app->db->esc($user['firstname']) . "',
     user_lastname = '" . $app->db->esc($user['lastname']) . "',
     user_email = '" . $app->db->esc($user['email']) . "',
     user_phone = '" . $app->db->esc($user['phone']) . "',
     user_active = '" . (isset($user['active']) ? 1 : 0) . "',
     user_address = '" . $app->db->esc(json_encode($user['addr'])) . "'
     WHERE user_id = '" . (int)$id . "'
   ");
      $app->flash("success", $app->lang->get('User data successfully updated'));
      $app->redirect('/admin/users');
    });
    /**
     * Managers frontend
     */
    $app->get('/managers', function () use ($app) {
      $page  = LIMIT * $app->request->get('p');
      $query = "
         SELECT m.*,
          (SELECT shop_name FROM `shops` WHERE shop_id = m.shop_id) AS 'shop'
         FROM `managers` m
         ORDER BY manager_id DESC
         LIMIT " . $page . ", " . LIMIT;
      // TODO pagination
      $managers = $app->db->getAll($query);
      $app->view->setData(array(
        "title"   => $app->lang->get('Managers'),
        "menu"    => "users",
        "content" => $app->view->fetch('managers.tpl', array(
          "app"      => $app,
          "managers" => $managers,
        )),
      ));
    });
    /**
     * Single manager frontend
     */
    $app->get('/manager/:id', function ($id) use ($app) {
      $app->view->setData(array(
        "title"   => $app->lang->get('Edit manager profile'),
        "menu"    => "users",
        "content" => $app->view->fetch('manager.tpl', array(
          "app"     => $app,
          "manager" => $app->db->getOne(" SELECT * FROM `managers` WHERE manager_id = '" . (int)$id . "'"),
          "shops"   => $app->db->getAll(" SELECT * FROM `shops` ORDER BY shop_id ASC"),
        )),
      ));
    });
    /**
     * Single manager backend
     */
    $app->post('/manager/:id', function ($id) use ($app) {
      $user = $app->request->post('manager');
      if ($user['pass'] == $user['cfm']) $pass = md5($user['pass']);
      else {
        $app->flash("error", $app->lang->get('Password mismatch'));
        $app->redirect('/admin/managers/' . $id);
      }
      $app->db->query("UPDATE `managers` SET
       manager_name = '" . $app->db->esc($user['name']) . "',
       user_email = '" . $app->db->esc($user['email']) . "',
       shop_id = '" . (int)$user['shop'] . "',
       manager_active = '" . (isset($user['active']) ? 1 : 0) . "'
       " . ($pass != '' ? ", manager_pass = '" . $pass . "'" : "") . "
       WHERE manager_id = '" . (int)$id . "'
     ");
      $app->flash("success", $app->lang->get('Manager data successfully updated'));
      $app->redirect('/admin/managers');
    });
    /**
     * Delivery frontend
     */
    $app->get('/delivery', function () use ($app) {
      $app->view->setData(array(
        "title"   => $app->lang->get('Delivery'),
        "menu"    => "content",
        "content" => $app->view->fetch('delivery.tpl', array(
          "app"      => $app,
          "delivery" => $app->db->getAll("SELECT * FROM `delivery` ORDER BY delivery_id DESC"),
        )),
      ));
    });
    /**
     * Delivery backend
     */
    $app->post('/delivery', function () use ($app) {
      $app->db->query("INSERT INTO `delivery` SET
            delivery_active	= " . (isset($app->request->post('delivery')['active']) ? 1 : 0) . ",
            delivery_name	= '" . $app->db->esc($app->request->post('delivery')['name']) . "',
            delivery_cost =	" . $app->db->esc($app->request->post('delivery')['cost']) . "
      ");
      $app->flash("success", $app->lang->get('Delivery type added successfully '));
      $app->redirect('/admin/delivery');
    });

  });


  $app->hook('slim.after.router', function () use ($app) {
    $app->render('index.tpl', array("app" => $app));
  });
