<?php

  $app->hook('slim.before.router', function () use ($app) {
    $config = $app->db->getAll("SELECT * FROM `config`");
    foreach ($config as $value)
      define($value['key'], $value['value']);
  });

  require PATH_ROUTE . "auth.php";


  $app->group('/admin', 'protect', function () use ($app) {

    $app->get('/', function () use ($app) {
      $app->redirect(URL_ROOT . 'admin/orders');
    });
    /**
     * Order list frontend
     */
    $app->get('/orders', function () use ($app) {
      $page = '' != $app->request->get('p') ? $app->request->get('p') : 0;
      // filters
      $where = array();
      if ($app->request->get("status") > 0)
        $where[] = 'order_status = "' . (int)$app->request->get('status') . '"';
      if ($app->request->get("from") != '') {
        $date    = DateTime::createFromFormat("d.m.Y", $app->request->get('from'));
        $where[] = 'order_created > "' . $date->format("Y-m-d H:i:s") . '"';
      }
      if ($app->request->get("to") != '') {
        $date    = DateTime::createFromFormat("d.m.Y", $app->request->get('to'));
        $where[] = 'order_created < "' . $date->format("Y-m-d H:i:s") . '"';
      }
      if ($app->request->get("user") != '')
        $where[] = 'order_client = "' . (int)$app->request->get('user') . '"';
      if ($app->request->get("manager") != '')
        $where[] = 'order_manager = "' . (int)$app->request->get('manager') . '"';
      $query  = "
      SELECT
        SQL_CALC_FOUND_ROWS *
      FROM `orders` o
      JOIN `users` u ON u.user_id = o.order_client
      " . (count($where) > 0 ? " WHERE " . implode(" AND ", $where) : "") . "
      ORDER BY o.order_id DESC
      LIMIT " . $page . ", " . LIMIT;
      $orders = $app->db->getAll($query);
      // pagination
      $pages = $app->db->getOne("SELECT FOUND_ROWS() AS 'cnt'");
      $get   = $app->request->get();
      unset($get['p']);
      $params = http_build_query($get);
      $app->view->setData(array(
        "title"   => $app->lang->get('Orders'),
        "menu"    => "order",
        "content" => $app->view->fetch('orders.tpl', array(
          "app"    => $app,
          "orders" => $orders,
          "pages"  => ceil($pages['cnt'] / LIMIT),
          "page"   => $page,
          "params" => $params,
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
      $order = $app->request->post('order');
      $app->db->query("UPDATE `orders` SET
        order_manager = '" . (int)$order['manager'] . "',
        order_status = '" . (int)$order['status'] . "',
        order_status_pay = '" . (isset($order['payment']) ? 1 : 0) . "',
        order_comment = '" . $app->db->esc($order['comment']) . "',
        order_delivery = '" . $app->db->esc(json_encode($order['delivery'])) . "',
        order_delivery_cost = '" . $app->db->esc($order['delivery_cost']) . "',
        order_cost = '" . $app->db->esc($order['total']) . "'
        WHERE order_id = '" . (int)$id . "'
      ");
      $app->db->query("DELETE FROM `lnk_order_products` WHERE order_id = '" . (int)$id . "'");
      foreach ($order['item']['id'] as $key => $value)
        $app->db->query("INSERT INTO `lnk_order_products` SET
          order_id = '" . (int)$id . "',
          product_id = '" . (int)$value . "',
          product_count = '" . $app->db->esc($order['item']['count'][$key]) . "',
          product_price = '" . $app->db->esc($order['item']['price'][$key]) . "'
        ");
      // notify customer
      if (isset($order['notify'])) {
        switch ((int)$order['status']) {
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
          $order['notify'],
          str_replace('{order_id}', $id, EMAIL_SUBJECT_ORDER_CHANGE),
          strtr(EMAIL_BODY_ORDER_CHANGE, array('{order_id}' => $id, '{order_status}' => $status))
        );
      }
      $app->flash("success", $app->lang->get('Order successfully updated'));
      $app->redirect(URL_ROOT . 'admin/order/' . $id);
    });
    /**
     * Product list frontend
     */
    $app->get('/products', function () use ($app) {
      $page    = '' != $app->request->get('p') ? $app->request->get('p') : 0;
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
      $query    = "
        SELECT
          SQL_CALC_FOUND_ROWS *,
          (SELECT GROUP_CONCAT(category_name) FROM `categories` WHERE category_id IN (SELECT category_id FROM `lnk_products_categories` WHERE product_id = p.product_id)) AS 'category'
        FROM `products` p
        " . $where . "
        ORDER BY p.product_updated DESC
        LIMIT " . $page . ", " . LIMIT . "
      ";
      $products = $app->db->getAll($query);
      // pagination
      $pages = $app->db->getOne("SELECT FOUND_ROWS() AS 'cnt'");
      $get   = $app->request->get();
      unset($get['p']);
      $params = http_build_query($get);
      $app->view->setData(array(
        "title"   => $app->lang->get('Products'),
        "menu"    => "product",
        "content" => $app->view->fetch('products.tpl', array(
          "app"        => $app,
          "products"   => $products,
          "categories" => $app->db->getAll("SELECT * FROM `categories` ORDER BY category_name ASC"),
          "pages"      => ceil($pages['cnt'] / LIMIT),
          "page"       => $page,
          "params"     => $params,
        )),
      ));
    });
    /**
     * Add new product frontend
     */
    $app->get('/product/add', function () use ($app) {
      $app->view->setData(array(
        "title"   => $app->lang->get('Add new product'),
        "menu"    => "product",
        "content" => $app->view->fetch('product.tpl', array(
          "app"        => $app,
          "product"    => array("product_id" => 0),
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
      $filters = "
        SELECT
        *,
        (SELECT COUNT(*) FROM `lnk_filters_categories` WHERE category_id IN (" . $product['category'] . ")) AS 'linked',
        (SELECT IF (f.filter_type = 3, string, number) FROM `values` WHERE value_id =
          (SELECT value_id FROM `lnk_products_values` WHERE filter_id = f.filter_id AND product_id = " . $product['product_id'] . ")) AS 'value'
        FROM `filters` f
        HAVING linked > 0
      ";
      $app->view->setData(array(
        "title"   => ($id > 0 ? $app->lang->get('Edit') : $app->lang->get('Add new product')) . " " . $product['product_name'],
        "menu"    => "product",
        "content" => $app->view->fetch('product.tpl', array(
          "app"        => $app,
          "product"    => $product,
          "filters"    => $app->db->getAll($filters),
          "categories" => $app->db->getAll("SELECT * FROM `categories` ORDER BY category_name ASC"),
        )),
      ));
    });
    /**
     * One product backend
     */
    $app->post('/product/:id', function ($id) use ($app) {
      $product = $app->request->post('product');
      if ($id == 0) {
        $app->db->query("
          INSERT INTO `products` SET
            product_created = NOW(),
            product_updated = NOW(),
            product_name = '" . $app->db->esc($product['name']) . "',
            product_code = '" . $app->db->esc($product['code']) . "',
            product_price = '" . $app->db->esc($product['price']) . "',
            product_visible = '" . (isset($product['available']) ? 1 : 0) . "',
            product_available = '" . $app->db->esc($product['status']) . "',
            product_description = '" . $app->db->esc($product['description']) . "',
            product_intro = '" . $app->db->esc($product['intro']) . "',
            product_cover = '" . $app->db->esc($product['cover']) . "'
        ");
        $id = $app->db->getID();
        // move images
        $new_dir = IMAGE_STORAGE . DS . "products" . DS . $id;
        $images  = glob(IMAGE_STORAGE . DS . "products" . DS . "0/*");
        if (!file_exists($new_dir)) {
          mkdir($new_dir);
          chmod($new_dir, 0777);
        }
        if (0 < count($images))
          foreach ($images as $image) {
            copy($image, $new_dir . '/' . basename($image));
            unlink($image);
          }
      } else {
        $id = (int)$id;
        $app->db->query("
          UPDATE `products` SET
            product_name = '" . $app->db->esc($product['name']) . "',
            product_code = '" . $app->db->esc($product['code']) . "',
            product_price = '" . $app->db->esc($product['price']) . "',
            product_visible = '" . (isset($product['available']) ? 1 : 0) . "',
            product_available = '" . $app->db->esc($product['status']) . "',
            product_description = '" . $app->db->esc($product['description']) . "',
            product_intro = '" . $app->db->esc($product['intro']) . "',
            product_cover = '" . $app->db->esc($product['cover']) . "'
          WHERE product_id = '$id'
        ");
      }
      // update categories
      $app->db->query("DELETE FROM `lnk_products_categories` WHERE product_id = '$id'");
      if (isset($product['categories']))
        foreach ($product['categories'] as $item)
          $app->db->query("INSERT INTO `lnk_products_categories` SET
            product_id = '$id',
            category_id = '" . (int)$item . "'");
      // update filters
      $app->db->query("DELETE FROM `lnk_products_values` WHERE product_id = '$id'");
      if (0 < count($app->request->post('filter')))
        foreach ($app->request->post('filter') as $key => $value) {
          list($type, $fid) = explode("|", $key);

          switch ((int)$type) {
            case 1: // Numeric
              $uniq = $app->db->getOne("SELECT value_id FROM `values` WHERE number = '" . (int)$value . "'");
              if ($uniq['value_id'] == '') {
                $app->db->query("INSERT INTO `values` SET number = '" . (int)$value . "'");
                $vid = $app->db->getID();
              } else
                $vid = $uniq['value_id'];
              break;
            case 2: // OR
              $vid = ($value != '' ? 2 : 1);
              break;
            case 3: // AND
              $uniq = $app->db->getOne("SELECT value_id FROM `values` WHERE string = '" . $app->db->esc($value) . "'");
              if ($uniq['value_id'] == '') {
                $app->db->query("INSERT INTO `values` SET string = '" . $app->db->esc($value) . "'");
                $vid = $app->db->getID();
              } else
                $vid = $uniq['value_id'];
              break;
          }
          $app->db->query("INSERT INTO `lnk_products_values` SET
            product_id = '$id',
            value_id = '$vid',
            filter_id = '" . (int)$fid . "'
          ");

        }
      $app->flash("success", $app->lang->get('Product successfully updated'));
      $app->redirect(URL_ROOT . 'admin/product/' . $id);
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
      $filter = $app->request->post('filter');
      if ($id == 0) {
        $app->db->query("INSERT INTO `filters` SET
          filter_name = '" . $app->db->esc($filter['name']) . "',
          filter_description = '" . $app->db->esc($filter['description']) . "',
          filter_type = '" . (int)$filter['type'] . "'
        ");
        $id = $app->db->getID();
      } else {
        $app->db->query("UPDATE `filters` SET
          filter_name = '" . $app->db->esc($filter['name']) . "',
          filter_description = '" . $app->db->esc($filter['description']) . "',
          filter_type = '" . (int)$filter['type'] . "'
          WHERE filter_id = '" . (int)$id . "'
        ");
      }
      $app->db->query("DELETE FROM `lnk_filters_categories` WHERE filter_id = '" . (int)$id . "'");
      if (isset($filter['category']))
        foreach ($filter['category'] as $item) {
          $app->db->query("INSERT INTO `lnk_filters_categories` SET
            filter_id = '" . (int)$id . "',
            category_id = '" . (int)$item . "'
            ");
        }
      $app->flash("success", $app->lang->get('Filter successfully added'));
      $app->redirect(URL_ROOT . 'admin/filters');
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
        $app->redirect(URL_ROOT . 'admin/banners');
      }
      $fileName = uniqid() . '.' . end($ext);
      move_uploaded_file($_FILES['banner']['tmp_name'], IMAGE_STORAGE . DS . 'banners' . DS . $fileName);
      $banner = $app->request->post('banner');
      $app->db->query("INSERT INTO `banners` SET
        banner_active	= " . (isset($banner['active']) ? 1 : 0) . ",
        banner_image	= '" . $fileName . "',
        banner_position	= '" . (int)$banner['position'] . "',
        banner_link_type =	" . ($banner['type'] == 'product' ? 1 : 2) . ",
        banner_link_id = " . ($banner['type'] == 'product' ? (int)$banner['product'] : (int)$banner['category']) . "
      ");
      $app->flash("success", $app->lang->get('Banner successfully uploaded'));
      $app->redirect(URL_ROOT . 'admin/banners');
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
      $app->redirect(URL_ROOT . 'admin/config');
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
        $category = $app->request->post("add");
        $app->db->query("
          INSERT INTO `categories` SET
          category_name = '" . $app->db->esc($category['name']) . "'
        ");
        $app->flash("success", $app->lang->get('Category successfully added to catalog'));
        $app->flashKeep();
        $app->redirect(URL_ROOT . 'admin/catalog');
      }
      if (count($app->request->post('edit')) > 0) {
        $category = $app->request->post("edit");
        $app->db->query("UPDATE `categories` SET
          category_name = '" . $app->db->esc($category['name']) . "',
          category_active = '" . (int)$category['active'] . "'
          WHERE category_id = '" . (int)$category['id'] . "'
        ");
        $app->flash("success", $app->lang->get('Category successfully updated'));
        $app->flashKeep();
        $app->redirect(URL_ROOT . 'admin/catalog');
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
      $shop = $app->request->post('shop');
      foreach ($shop['name'] as $key => $value)
        $app->db->query("INSERT INTO `shops` SET
          shop_name = '" . $app->db->esc($shop['name'][$key]) . "',
          shop_phone = '" . $app->db->esc($shop['phone'][$key]) . "',
          shop_addr = '" . $app->db->esc($shop['addr'][$key]) . "',
          shop_active = '" . (isset($shop['active'][$key]) ? 1 : 0) . "',
          shop_lat = '" . $app->db->esc($shop['lat'][$key]) . "',
          shop_lng = '" . $app->db->esc($shop['lng'][$key]) . "'
        ");

      $app->flash("success", $app->lang->get('Shop list updated'));
      $app->redirect(URL_ROOT . 'admin/shops');
    });
    /**
     * Users frontend
     */
    $app->get('/users', function () use ($app) {
      $page  = '' != $app->request->get('p') ? $app->request->get('p') : 0;
      $query = "
       SELECT SQL_CALC_FOUND_ROWS *,
         (SELECT COUNT(*) FROM `orders` WHERE order_client = user_id) AS 'orders_count'
       FROM `users`
       " . ($app->request->get('search') != '' ? "
       WHERE user_firstname LIKE '%" . $app->db->esc($app->request->get('search')) . "%'
       OR user_lastname LIKE '%" . $app->db->esc($app->request->get('search')) . "%'
       OR user_email LIKE '%" . $app->db->esc($app->request->get('search')) . "%'
       OR user_phone LIKE '%" . $app->db->esc($app->request->get('search')) . "%'" : "") . "
       ORDER BY user_id DESC
       LIMIT " . $page . ", " . LIMIT;
      $users = $app->db->getAll($query);
      // pagination
      $pages = $app->db->getOne("SELECT FOUND_ROWS() AS 'cnt'");
      $get   = $app->request->get();
      unset($get['p']);
      $params = http_build_query($get);
      $app->view->setData(array(
        "title"   => $app->lang->get('Users'),
        "menu"    => "users",
        "content" => $app->view->fetch('users.tpl', array(
          "app"    => $app,
          "users"  => $users,
          "pages"  => ceil($pages['cnt'] / LIMIT),
          "page"   => $page,
          "params" => $params,
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
      $app->redirect(URL_ROOT . 'admin/users');
    });
    /**
     * Managers frontend
     */
    $app->get('/managers', function () use ($app) {
      $query    = "
         SELECT m.*,
          (SELECT shop_name FROM `shops` WHERE shop_id = m.shop_id) AS 'shop'
         FROM `managers` m
         ORDER BY manager_id DESC";
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
        $app->redirect(URL_ROOT . 'admin/managers/' . $id);
      }
      // create new manager or update existing
      if ($id > 0)
        $app->db->query("UPDATE `managers` SET
         manager_name = '" . $app->db->esc($user['name']) . "',
         user_email = '" . $app->db->esc($user['email']) . "',
         shop_id = '" . (int)$user['shop'] . "',
         manager_active = '" . (isset($user['active']) ? 1 : 0) . "'
         " . ($pass != '' ? ", manager_pass = '" . $pass . "'" : "") . "
         WHERE manager_id = '" . (int)$id . "'
       ");
      else
        $app->db->query("INSERT INTO `managers` SET
         manager_name = '" . $app->db->esc($user['name']) . "',
         manager_email = '" . $app->db->esc($user['email']) . "',
         shop_id = '" . (int)$user['shop'] . "',
         manager_active = '" . (isset($user['active']) ? 1 : 0) . "',
         manager_pass = '" . $pass . "'
       ");
      $app->flash("success", $app->lang->get('Manager data successfully updated'));
      $app->redirect(URL_ROOT . 'admin/managers');
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
      $delivery = $app->request->post('delivery');
      $app->db->query("INSERT INTO `delivery` SET
            delivery_active	= " . (isset($delivery['active']) ? 1 : 0) . ",
            delivery_name	= '" . $app->db->esc($delivery['name']) . "',
            delivery_cost =	" . $app->db->esc($delivery['cost']) . "
      ");
      $app->flash("success", $app->lang->get('Delivery type added successfully '));
      $app->redirect(URL_ROOT . 'admin/delivery');
    });
    /**
     * Exit admin panel
     */
    $app->get('/exit', function () use ($app) {
      unset($_SESSION['admin']);
      $app->redirect(URL_ROOT . '');
    });

  });


  $app->hook('slim.after.router', function () use ($app) {
    $app->render('index.tpl', array("app" => $app));
  });
