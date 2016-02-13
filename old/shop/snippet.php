<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/
  require MODX_BASE_PATH . "assets/shop/shop.class.php";
  $shop = new Shop($modx);
switch($get) {
  /**
   *  NewPost
   *  @type
   *  @value
   *  @tpl
   */
  case "newpost":
    switch($type) {
      case "city":
        $cities = $modx->db->query("select wh_cityid as 'id', wh_city as 'pagetitle' from `modx_a_newpost_wh` group by wh_cityid order by wh_city asc");
        while ($city = $modx->db->getRow($cities))
          $res .= $modx->parseChunk($tpl, $city, "[+", "+]");    
        break;
      case "whwh":
        $whs = $modx->db->query("select wh_id as 'id', wh_name as 'pagetitle' from `modx_a_newpost_wh` where wh_cityid = (select wh_cityid from `modx_a_newpost_wh` where wh_id = '".$modx->db->escape($value)."')");
        while ($wh = $modx->db->getRow($whs))
          $res .= $modx->parseChunk($tpl, $wh, "[+", "+]");    
        break;
      case "wh":
        $whs = $modx->db->query("select wh_id as 'id', wh_name as 'pagetitle' from `modx_a_newpost_wh` where wh_cityid = '".$modx->db->escape($value)."'");
        while ($wh = $modx->db->getRow($whs))
          $res .= $modx->parseChunk($tpl, $wh, "[+", "+]");    
        break;
    }
    break;
  /**
   * Количество товаров в корзине
   */
  case "cart_count":
    $res = count($_SESSION['cart']);
    break;
  /**
   * Выводит хлебные крошки
   */
  case "breadcrumbs":
    $id             = isset($id) ? $id : $modx->documentIdentifier;
    $showHomeCrumb  = isset($showHomeCrumb) ? $showHomeCrumb : true;
    $showLastCrumb  = isset($showLastCrumb) ? $showLastCrumb : true;
    $showHidden     = isset($showHidden) ? $showHidden : true;
    $separator      = isset($separator) ? $separator : '';
    $lastAsLink     = isset($lastAsLink) ? $lastAsLink : ($modx->documentIdentifier == $modx->config['shop_page_item']);
    $title          = isset($title) ? $title : 'pagetitle';
    $lastCrumbClass = "active";
    $documents      = Array();
    
    $outerTpl      = '<ol id="breadcrumbs" class="breadcrumb '.$outerClass.'">[+crumbs+]</ol>';
    $crumbTpl      = '<li class="'.$crumbClass.'"><a href="[~~[+url+]~~]">[+title+]</a> '.$separator.'</li>';
    $shopTpl       = '<li class="'.$crumbClass.'"><a href="[+url+]">[+title+]</a> '.$separator.'</li>';
    $lastCrumbTpl  = '<li class="'.$lastCrumbClass.'">[+title+]</li>';
    
    $parse         = "";
    if ($showHomeCrumb) $documents[] = $modx->getConfig('site_start');
    $documents     = array_merge($documents, array_reverse(array_values($modx->getParentIds($id))));
    if ($showLastCrumb) $documents[] = $id;
    $document      = implode(',', $documents);
    $fields        = $modx->config['lang_enable'] ? "pagetitle_".$modx->config['lang']." as 'pagetitle', menutitle_".$modx->config['lang']." as 'menutitle'" : "pagetitle, menutitle";
    $query         = "select 
                            id, $fields
                      from `modx_site_content`
                      where id in (".$document.") ".($showHidden ? "" : "and hidemenu = 0")."
                      order by field(id, ".$document.")";
    $crumbs        =   $modx->db->query($query);
    while ($crumb = $modx->db->getRow($crumbs)) {
      $parse[] = strtr(($crumb['id'] == end($documents) && !$lastAsLink ? $lastCrumbTpl : $crumbTpl),
                       Array("[+url+]"   => $crumb['id'],
                             "[+title+]" => ($title == "menutitle" && $crumb['menutitle'] != "" ? $crumb['menutitle'] : $crumb['pagetitle'])));
    }
    if ($modx->documentIdentifier == $modx->config['shop_page_item'])
      $parse[] = strtr($lastCrumbTpl, Array("[+title+]" => $modx->documentObject['product_name']));
    $res = str_replace("[+crumbs+]", implode("", $parse), $outerTpl);
    break;
  /**
   * Вывод товаров
   */
  case "catalog":
    $id           = $modx->documentIdentifier == 1 ? $modx->config['shop_catalog_root'] : $modx->documentIdentifier;
    $categories   = array_values($modx->getChildIds($id));
    $categories[] = $id;
    $categories   = implode(",", $categories);
    $page         = isset($_REQUEST['p']) ? ($shop->number($_REQUEST['p'])) * $limit : 0;
    $status       = ($shop->number($status) != '') ? 'and p.product_available = '.$shop->number($status) : ''; // выбираем товары одного статуса
    if ($_POST['ajax'] == "filter" && isset($_POST['f'])) {
      $filter = Array();
      foreach($_POST['f'] as $key => $value)
        if (is_array($value)) {
          if (isset($value['min'])) // range
              $filter[] = "0 < (select count(*) from `modx_a_pro2val` pv join `modx_a_values` v on v.value_id = pv.value_id and v.num between '".$shop->number($value['min'])."' and '".$shop->number($value['max'])."' where pv.product_id = p.product_id and pv.filter_id = '".$shop->number($key)."')";
          else { // and
              $one_filter = Array();
              foreach ($value as $val)
                  $one_filter[] = "(v.value_id = '".$modx->db->escape($val)."')";
              $filter[] = "0 < (select count(*) from `modx_a_pro2val` pv join `modx_a_values` v on v.value_id = pv.value_id and (".implode("OR", $one_filter).") where pv.product_id = p.product_id and pv.filter_id = '".$shop->number($key)."')";
          }
        } elseif ($value >= 0) // or
          $filter[] = "0 < (select count(*) from `modx_a_pro2val` pv join `modx_a_values` v on v.value_id = pv.value_id and v.num = '".$shop->number($value)."' where pv.product_id = p.product_id and pv.filter_id = '".$shop->number($key)."')";
    }

    $status_sort = ($shop->number($_POST['sort']['status']) > 0) ? $shop->number($_POST['sort']['status']) : -1;
    switch ($_POST['sort']['price']) {
        case 'cheap':     $sort = ", p.product_price DESC"; break;
        case 'expensive': $sort = ", p.product_price ASC"; break;
        default:  $sort = ""; break;
    }

    $query = "
      SELECT 
          SQL_CALC_FOUND_ROWS
          p.*, s.*, p.product_id AS 'product_id',
          (select count(*) from `modx_a_pro2cat` where modx_id in (".$categories.") and product_id = p.product_id) AS 'cnt',
          (select pagetitle from `modx_site_content` where id = p.product_brand) AS 'brand',
      IF(p.product_available = ".$status_sort.",1,2) AS status_sort
      FROM `modx_a_products` p 
      LEFT JOIN `modx_a_product_strings` s ON s.product_id = p.product_id AND s.translate_lang = '".$modx->config['lang']."'
      WHERE p.product_visible = 1 ".(!empty($filter) ? "\n AND ".implode(" AND\n ", $filter) : "")."
            ".($_POST['price']['min'] != "" ? "AND p.product_price BETWEEN '".$shop->number($_POST['price']['min'])."' AND '".$shop->number($_POST['price']['max'])."'" : "")."
            ".$status."
      HAVING cnt > 0 
      ORDER BY status_sort ASC ".$sort."
      LIMIT ".$page.", ".$limit;
      $_SESSION['sql'] = $query;
      /*
      $query = "
      SELECT 
          SQL_CALC_FOUND_ROWS
          p.*, s.*, p.product_id AS 'product_id',
          (select count(*) from `modx_a_pro2cat` where modx_id in (".$categories.") and product_id = p.product_id) AS 'cnt',
          (select pagetitle from `modx_site_content` where id = p.product_brand) AS 'brand'
      FROM `modx_a_products` p 
      LEFT JOIN `modx_a_product_strings` s ON s.product_id = p.product_id AND s.translate_lang = '".$modx->config['lang']."'
      WHERE p.product_visible = 1 
      HAVING cnt > 0 
      ORDER BY ".$sort."
      LIMIT ".$page.", ".$limit;*/
    $products = $modx->db->query($query);
    while ($p = $modx->db->getRow($products)) {
      $p['url'] = $modx->makeUrl($modx->config['shop_page_item']).$p['product_url'];
      $p['product_price'] = $shop->getPrice($p['product_price'], $modx->config['currcode']);
      $res .= $modx->parseChunk($tpl, $p);
    }
    $pages = $modx->db->getRow($modx->db->query("SELECT FOUND_ROWS() AS 'cnt'"));
    $modx->setPlaceholder("max_items", $pages['cnt']);
    $modx->setPlaceholder("limit_items", $limit);
    break;
  /**
   * Пагинация товара
   */
  case "paginate":  
    $tpl         = ($tpl != '') ? $tpl : 'tpl_paginate' ;
    $tpl_enable  = ($tpl_enable != '') ? $tpl_enable : 'tpl_page_enable';
    $tpl_disable = ($tpl_disable != '') ? $tpl_disable : 'tpl_page_disable';
    $classJS     = ($classJS != '') ? ' '.$classJS : ' js_change_page';
    $res = $shop->getPaginate($tpl, $tpl_enable, $tpl_disable, $classJS);    
    break;
  case "friends_product":  
    if($friends != ''){      
      $query = "
        SELECT 
            SQL_CALC_FOUND_ROWS
            p.*, s.*, p.product_id AS 'product_id',
            (select pagetitle from `modx_site_content` where id = p.product_brand) AS 'brand'
        FROM `modx_a_products` p 
        LEFT JOIN `modx_a_product_strings` s ON s.product_id = p.product_id AND s.translate_lang = '".$modx->config['lang']."'
        WHERE p.product_visible = 1 AND  p.product_id IN (".$modx->db->escape($friends).")";
      $products = $modx->db->query($query);
      while ($p = $modx->db->getRow($products)) {
        $p['url'] = $modx->makeUrl($modx->config['shop_page_item']).$p['product_url'];
        $p['product_price'] = $shop->getPrice($p['product_price'], $modx->config['currcode']);
        $res .= $modx->parseChunk($tpl, $p);
      }  
    }
    break;
  /**
   * Характеристики товара
   */
  case "item_spec":
    $spec = $modx->db->query("
      SELECT p.*,f.*,
        (select val from `modx_a_values` where value_id = p.value_id and f.filter_type = 3) as 'value',
        (select num from `modx_a_values` where value_id = p.value_id and f.filter_type = 1) as 'num',
        (select num from `modx_a_values` where value_id = p.value_id and f.filter_type = 2) as 'or'
      FROM `modx_a_pro2val` p 
      JOIN `modx_a_filters` f ON f.filter_id = p.filter_id
      WHERE p.product_id = ".$modx->documentObject['product_id']);
    while ($s = $modx->db->getRow($spec)) {
      switch ($s['filter_type']) {
        case 1: // фильтр типа число
          $s['filter_value'] = $s['num'];
          break;
        case 3: // фильтр типа И
          $s['filter_value'] = $s['value'];
          break;
        case 2: // фильтр типа ИЛИ
          $s['filter_value'] = $s['or'] ? "Да" : "Нет";
          break;
      } 
      $res .= $modx->parseChunk($tpl, $s);
    }
    break;
  /**
   * Изображения товара
   */
  case "item_images":
    $id = isset($id) ? $id : $modx->documentObject['product_id'];
    $images = glob(MODX_BASE_PATH . "assets/images/items/".$id."/*");
    if (is_array($images))
      foreach($images as $image)
        $res .= $modx->parseChunk($tpl, Array("image" => str_replace(MODX_BASE_PATH, '/', $image)));
    break;
  /**
   * Корзина
   */
  case "cart":
    if (count($_SESSION['cart']) == 0) 
      $res = '<tr><td class="js_cart_empty" colspan="4">Корзина пуста</td></tr>';
    else {
      $items = $modx->db->query("
        SELECT * 
        FROM `modx_a_products` p
        JOIN `modx_a_product_strings` s ON s.product_id = p.product_id AND s.translate_lang = '".$modx->config['lang']."'
        WHERE p.product_id IN (".implode(",", array_keys($_SESSION['cart'])).") ");
      while ($item = $modx->db->getRow($items)) {
        $item['product_url']   = $modx->makeUrl($modx->config['shop_page_item']).$item['product_url'];
        $item['product_price'] = $shop->getPrice($item['product_price'], $_SESSION['currency']);
        $item['cart_quantity'] = $_SESSION['cart'][$item['product_id']];
        $item['cart_sum']      = round($item['cart_quantity'] * $item['product_price'], 2);
        $res                  .= $modx->parseChunk($tpl, $item);
      }
    }
    break;
  /**
   * Список способов доставки
   */
  case "delivery_list":
    $list = $modx->db->query("SELECT * FROM `modx_a_delivery` ORDER BY delivery_default DESC, delivery_title ASC");
    while ($d = $modx->db->getRow($list)) {
      $d['delivery_cost'] = $shop->getPrice($d['delivery_cost'], $_SESSION['currency']);
      $res .= $modx->parseChunk($tpl, $d);
    } 
    break;
  /**
   * Список заказов пользователя
   */
  case "orders":
    $orders = $modx->db->query("SELECT * FROM `modx_a_order` WHERE order_client = ".$_SESSION['webuser']['internalKey']." ORDER BY order_created DESC");
    if ($modx->db->getRecordCount($orders) == 0)
      $res = $modx->getChunk("tpl_order_empty");
    else
      while ($order = $modx->db->getRow($orders)) {
        $order['order_cost'] = $shop->getPrice($order['order_cost'], $_SESSION['currency']);
        $order['order_delivery_cost'] = $shop->getPrice($order['order_delivery_cost'], $_SESSION['currency']);
        $order['order_total'] = $order['order_cost'] + $order['order_delivery_cost'];
        $order['order_num'] = str_pad($order['order_id'], 6, 0, STR_PAD_LEFT);
        $res .= $modx->parseChunk($tpl, $order);
      }
    break;
  /**
   * Список адресов пользователя
   */
  case "user_addr":
    $addr = json_decode($_SESSION['webuser']['addr'], true);
    if (is_array($addr))
      foreach ($addr['index'] as $key => $value) 
        $res .= $modx->parseChunk($tpl, Array(
          "key"   => $key,
          "index" => $value,
          "city"  => $addr['city'][$key],
          "ave"   => $addr['ave'][$key],
          "house" => $addr['house'][$key],
          "room"  => $addr['room'][$key]
          ));
    else 
      $res = $modx->parseChunk($tpl, Array(
          "index" => '',
          "city"  => '',
          "ave"   => '',
          "house" => '',
          "room"  => ''
          ));
    break;
  case "filters":
    /*
      !#fid=value;fid=value
      t
      1 fid=100|200
      2 fid=1,2,3
      3 fid=y/n
    */
    $category = $modx->documentIdentifier;
    if ($modx->documentObject['isfolder']) {
      $children = $modx->getChildIds($category);
      if (count($children) > 0)
        $category .= ','.implode(",", array_values($children));
    }
    $filters = $modx->db->query("
    SELECT *
    FROM `modx_a_filters`
    WHERE filter_id IN (
      SELECT filter_id
      FROM `modx_a_fc`
      WHERE modx_id IN ($category)
    )
    GROUP BY filter_id
    ");
    while ($filter = $modx->db->getRow($filters)) {
      switch ($filter['filter_type']) {
        case 1: // number
          //$query = " SELECT MIN(num) AS 'min', MAX(num) AS 'max' FROM `modx_a_values` WHERE filter_id = ".$filter['filter_id']." ";
          $query = " SELECT p.*, MIN(v.num) AS 'min', MAX(v.num) AS 'max' 
            FROM `modx_a_pro2val` p 
              JOIN `modx_a_values` v
              ON v.value_id = p.value_id
            WHERE p.filter_id = ".$filter['filter_id']." ";
          $tpl = "tpl_type1";
          break;
        case 2: // or
          $tpl = "tpl_type2";
          break;
        case 3: // and
          //$query = " SELECT DISTINCT(val) AS 'value' FROM `modx_a_values` WHERE filter_id = ".$filter['filter_id']."";
          $query = "SELECT DISTINCT(v.val) AS 'value', v.value_id AS 'value_id'
            FROM `modx_a_pro2val` p 
              JOIN `modx_a_values` v
              ON v.value_id = p.value_id
            WHERE p.filter_id = ".$filter['filter_id']." 
            AND p.product_id IN (select product_id FROM `modx_a_pro2cat` WHERE modx_id IN (".$category."))
            ORDER BY value ASC";
          $tpl = "tpl_type3";
          break;
      }
      if($query != ''){
        $filter_values = $modx->db->query($query);
        if ($modx->db->getRecordCount($filter_values) == 1) {
          $filter_one =  $modx->db->getRow($filter_values);
          $filter_one['filter_id'] = $filter['filter_id'];
          $filter['filters'] .= $modx->parseChunk($tpl, $filter_one);
        } else
          while ($filter_one = $modx->db->getRow($filter_values)) {
            $filter_one['filter_id'] = $filter['filter_id'];
            $filter['filters'] .= $modx->parseChunk($tpl, $filter_one);
          }
        $res .= $modx->parseChunk($tplWrap, $filter);
      }
    }
    break;
  case "langs":
    $langs = explode(",", $modx->config['lang_list']);
    foreach ($langs as $lang)
      $res .= $modx->parseChunk($tpl, Array("id" => $lang, "pagetitle" => $lang), "[+", "+]");
    break;
  case "currency":
    $title = explode("|", $modx->config['shop_curr_name']);
    $sign  = explode("|", $modx->config['shop_curr_sign']);
    $codes = explode("|", $modx->config['shop_curr_code']);
    foreach ($title as $key => $val)
      $res .= $modx->parseChunk($tpl, Array("id" => $codes[$key], "pagetitle" => $val." (".$sign[$key].")"), "[+", "+]");
    break;
  /**
   * Поиск по сайту
  */
  case 'search':
    if(isset($_REQUEST['search']) AND !is_array($_REQUEST['search']))  {
      if(mb_strlen($_REQUEST['search'],'UTF-8') > 2){          
        $search = $modx->db->escape(strip_tags(stripslashes($_REQUEST['search'])));             
        $lang   = $modx->config['lang'];
        $res    = "";
        $limit  = isset($limit) ? $limit : 10; 
        $start  = isset($_REQUEST['p']) ? ($_shop->number($_REQUEST['p'])-1) * $limit : 0;
        $where  = $tvs = Array();        
        $words  = explode(" ", $search);
        if (count($words) > 0)
          foreach ($words as $s)
            if (mb_strlen($s,'UTF-8') >= 3) {
              $where[] = "(s.product_name LIKE '%$s%' OR s.product_introtext LIKE '%$s%' OR s.product_description LIKE '%$s%')";
              //$tvs[] = " value like '%$s%' ";
            }
        else $res = $modx->getChunk($tplEmpty);
        $where  = implode(" OR ", $where);
        //$tvs    = implode(" OR ", $tvs);
        $query = "
          SELECT 
              SQL_CALC_FOUND_ROWS
              p.*, s.*,
              (select pagetitle from `modx_site_content` where id = p.product_brand) AS 'brand'
          FROM `modx_a_product_strings` s 
          LEFT JOIN `modx_a_products` p ON p.product_visible = 1 AND p.product_id = s.product_id
          WHERE s.translate_lang = '".$modx->config['lang']."' AND ".$where."
          LIMIT ".$start.", ".$limit;
        //echo "<pre>". $query."</pre>";
        $mysql  = $modx->db->query($query);
        if ($modx->db->getRecordCount($mysql) == 0)
          $res  = $modx->getChunk($tplEmpty);
        else {
          while ($row  = $modx->db->getRow($mysql)) {
            $row['url'] = $modx->makeUrl($modx->config['shop_page_item']).$row['product_url'];
            $res .= $modx->parseChunk($tpl, $row);
          }
          $pages = $modx->db->getRow($modx->db->query("SELECT FOUND_ROWS() AS 'cnt'"));
          $modx->setPlaceholder("max_items", $pages['cnt']);
          $modx->setPlaceholder("limit_items", $limit);
          $modx->setPlaceholder("url", $modx->makeUrl(12).'?search='.$search.'&p=');
        }
      }
      else
          $res = 'Поиск от 3х символов';
    }    

    if($res == ''){
        $res = 'Поиск не дал результатов';
    }    
    break;
  case 'item_recalls':
    $query = "
        SELECT 
          *
        FROM `modx_a_recall`
        WHERE recall_moderated = 1 and recall_product = ".$modx->documentObject['product_id'].'
        ORDER BY recall_pub_date DESC';
      $products = $modx->db->query($query);
      while ($p = $modx->db->getRow($products)) 
        $res .= $modx->parseChunk($tpl, $p);
  break;
}