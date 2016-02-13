<?php
/**
 * Обработчик AJAX запросов
 */
  switch ($_REQUEST['ajax']) {
    /**
     * Купить товар (положить в корзину)
     */
    case "buy":
      $buy = $_shop->number($_GET['buy']);
      if (in_array($buy, array_keys($_SESSION['cart'])))
        $_SESSION['cart'][$buy]++;
      else
        $_SESSION['cart'][$buy] = 1;
      $ajax = Array("cart_title" => $modx->parseDocumentSource("[#1#] (".count($_SESSION['cart']).")"));
      break;
    /**
     * Корзина
     */
    case "cart":
      if (count($_SESSION['cart']) == 0)
        $ajax['alert'] = $modx->parseDocumentSource("[#1#] [#2#]"); 
      else
        $ajax['cart'] = $modx->rewriteUrls($modx->parseDocumentSource('{{pop_cart}}'));
      break;
    /**
     * Обновление количества товара
     */
    case "cartPriceUpdate":
      $_SESSION['cart'][$_shop->number($_GET['pid'])] = $_shop->number($_GET['qnt']);
      break;
    /**
     * Удаление товара
     */
    case "cartRemove":
      unset($_SESSION['cart'][$_shop->number($_GET['pid'])]);
      break;
    /**
     * Смена валюты
     */
    case "setCurrency":
      $currency = explode("|", $modx->config['shop_curr_code']);
      $_SESSION['currency'] = (in_array($_GET['currency'], $currency) ? $_GET['currency'] : $modx->config['shop_curr_default_code']);      
      break;
    /**
     * Детали заказа
     */
    case "getOrderDetails":
      $order_id = $shop->number($_REQUEST['order']);
      if (!isset($_SESSION['webuser']['email'])) $ajax['code'] = 403;
      else {
        $order = $modx->db->getRow($modx->db->query("
          SELECT *
          FROM `modx_a_order` o
          WHERE order_id = '$order_id'
            AND order_client = '{$_SESSION[webuser][internalKey]}'"));
        // $delivery =
        $query = "
          SELECT *, op.product_price AS 'product_price'
          FROM `modx_a_order_products` op
          JOIN `modx_a_products` p ON p.product_id = op.product_id
          JOIN `modx_a_product_strings` ps ON ps.translate_lang = '".$modx->config['lang']."' AND ps.product_id = op.product_id
          WHERE op.order_id = '{$order[order_id]}' ";
          // echo $query;
        $items = $modx->db->query($query);
        $content = $order;
        $content['order_delivery_cost'] = $shop->getPrice($order['order_delivery_cost'], $_SESSION['currency']);
        $content['order_cost'] = $shop->getPrice($order['order_cost'], $_SESSION['currency']);
        $order['order_total'] = $order['order_cost'] + $order['order_delivery_cost'];
        while ($item = $modx->db->getRow($items)) {
          $item['product_price'] = $shop->getPrice($item['product_price'], $_SESSION['currency']);
          $item['product_cost'] = $item['product_price'] * $item['product_count'];
          $content['items'] .= $modx->parseChunk("tpl_order_details_item", $item);
        }
        $content['order_total'] = $content['order_cost'] + $content['order_delivery_cost'];
        // liqpay
        require_once MODX_BASE_PATH . "/assets/shop/php/LiqPay.php";
        $liqpay = new LiqPay($modx->config['shop_liqpay_mid'], $modx->config['shop_liqpay_sig']);
        $content['pay_form'] = $liqpay->getForm(array('order_id' => $order_id, 'amount' => $order['order_total'], 'currency' => 'UAH', "description" => "Оплата заказа № ".str_pad($order['order_id'], 6, 0, STR_PAD_LEFT) ));
        $ajax['order'] = $modx->rewriteUrls($modx->parseDocumentSource($modx->parseChunk("tpl_order_details", $content)));
        $ajax['code'] = 200;
      }
      break;
    /**
     * Фильтры в каталоге
     */
    case "filter":
      if($_POST['d'] == 'reset')
        unset($_POST['f']);
      $ajax['catalog']  = $modx->parseDocumentSource($modx->runSnippet("Shop", Array("get" => "catalog", "tpl" => "tpl_catalog", "limit" => 12)));
      $ajax['paginate'] = $modx->parseDocumentSource($modx->runSnippet("Shop", Array("get" => "paginate")));
        // [[Shop?&get=`catalog`&tpl=`tpl_catalog`&limit=`12`]]
      break;
    /**
     * NewPost
     */
    case "newpost":
      $ajax['content'] = $modx->runSnippet("Shop", Array("get" => "newpost", "tpl" => "tpl_option", "type" => "wh", "value" => (int)$_GET['city']));
      break;
  }

  die(json_encode($ajax));