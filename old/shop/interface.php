<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);
if(!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') die("No access");
define("ROOT", dirname(__FILE__));
define("TPLS", dirname(__FILE__)."/tpl/interface/");
$languages      = explode(",", $modx->config['lang_list']);
$bootstrap      = isset($_GET['b']) ? $_GET['b'] : "orders";
$controller     = isset($_GET['c']) ? $_GET['c'] : "";
$res            = Array();
$res['version'] = "v 1.0";
$res['url']     = $url = "index.php?a=255&";
$search         = true;
$_SESSION['currency'] = "usd";
$modx->config['lang'] = $modx->config['lang_default'];
$messages       = Array(
  "item_added"       => $_lang["shop_item_added"],
  "item_updated"     => $_lang["shop_item_updated"],
  "payments_updated" => $_lang["shop_payments_updated"],
  "order_updated"    => $_lang["shop_order"]." № ".str_pad($_GET['id'], 6, 0, STR_PAD_LEFT)." ".$_lang["shop_updated"],
  "import_error_xml" => $_lang["shop_import_error_xml"],
  "import_error_csv" => $_lang["shop_import_error_csv"],
  "import_ok"        => $_lang["shop_import_ok"],
  "export_ok"        => $_lang["shop_export_ok"],
  "recall_updated"   => $_lang["shop_recall_updated"],
  "recall_deleted"   => $_lang["shop_recall_deleted"],
  "filter_deleted"   => $_lang["shop_filter_deleted"]
  );

include MODX_BASE_PATH . "assets/shop/shop.class.php";
$shop = new Shop($modx);

switch ($bootstrap) {
  case "find_items":
    $items = $shop->findItems($_GET['query'], $_GET['order']);
    while ($item = $modx->db->getRow($items)) {
      $item['product_cover'] = $modx->runSnippet("R", Array("img" => "/assets/images/items/".$item['product_id']."/".$item['product_cover'], "opt" => "w=128&h=128&far=1"));
      $item['price'] = $item['product_price'] . " " . $modx->config['shop_curr_default_sign'];
      $ajax[] = $item;
    }
    die(json_encode($ajax));
    break;
  case "upload":
    $folder = MODX_BASE_PATH . "assets/images/items/".$_POST['folder'];
    if (!file_exists($folder)) {
      mkdir($folder);
      chmod($folder, 0777);
    }
    foreach ($_FILES['uploader']['tmp_name'] as $key => $value)
      move_uploaded_file($value, $folder."/". preg_replace('/\D+/', '', microtime()).".".end(explode(".", $_FILES['uploader']['name'][$key])));
    echo count($_FILES);
    die;
    break;
  case 'get_images':
    $folder = "/assets/images/items/".$_GET['folder'];
    chdir(MODX_BASE_PATH . $folder);
    $files = glob('*');
    if (is_array($files))
        foreach($files as $image)
          require TPLS . "image.tpl";
    die;
    break;
  case 'delete_image':
    unlink(MODX_BASE_PATH . "/assets/images/items/".$_GET['folder'] ."/". $_GET['delete']);
    die;
    break;
  case 'update_price':
    $shop->updateProductPrice($_GET['pid'], $_GET['price']);
    die('Updated');
    break;
  case "filters":
    switch ($_GET['d']) {
      case "json";
        $values = $modx->db->query("select v.* from `modx_a_values` v where v.val != '' and 0 < (select count(*) from modx_a_pro2val where filter_id = ".$_GET['f']." and value_id = v.value_id) ");
        $res = Array();
        while($value = $modx->db->getRow($values))
          $res[] = $value['val'];
        die(json_encode($res));
      break;
      case "add":
        $categories = $modx->db->query("
          select
            id, pagetitle_".$modx->config['lang']." AS 'pagetitle'
          from `modx_site_content`
          where isfolder = 0 and template = ".$modx->config["shop_tpl_category"]."
          order by pagetitle asc");
        $tpl = "/filter_add.tpl";
      break;
      case "save":
        $sql = $modx->db->query("insert into `modx_a_filters` set
          filter_name = '".$modx->db->escape($_POST['filter']['name'])."',
          filter_type = '".$modx->db->escape($_POST['filter']['type'])."',
          filter_desc = '".$modx->db->escape($_POST['filter']['desc'])."'
          ");
        $id = $modx->db->getInsertID($sql);
        // Linking with categories
        if (is_array($_POST['filter']['category']))
          foreach ($_POST['filter']['category'] as $category)
            $modx->db->query("insert into `modx_a_fc` set filter_id = '$id', modx_id = ".$category);
        die(header("Location: ".$url."b=filters&p=5"));
      break;
      case "edit":
        $filter     = $modx->db->getRow($modx->db->query("select f.*, (select group_concat(modx_id) from `modx_a_fc` where filter_id = f.filter_id) as 'categories' from `modx_a_filters` f where f.filter_id = ".$_GET['edit']));
        $categories = $modx->db->query("select id, pagetitle_".$modx->config['lang']." AS 'pagetitle' from `modx_site_content` where isfolder = 0 and template = ".$modx->config["shop_tpl_category"]." order by pagetitle asc");
        $tpl        = "/filter_edit.tpl";
      break;
      case "delete":
        $shop->deleteFilter($_GET['delete']);
        die(header("Location: ".$url."b=filters&w=filter_deleted"));
      break;
      case "update":
        $sql = $modx->db->query("update `modx_a_filters` set
          filter_name = '".$modx->db->escape($_POST['filter']['name'])."',
          filter_type = '".$modx->db->escape($_POST['filter']['type'])."',
          filter_desc = '".$modx->db->escape($_POST['filter']['desc'])."'
          where filter_id = '".$modx->db->escape($_POST['filter']['id'])."'
          ");
        $id = $modx->db->escape($_POST['filter']['id']);
        $modx->db->query("delete from `modx_a_fc` where filter_id = ".$id);
        // Linking with categories
        if (is_array($_POST['filter']['category']))
          foreach ($_POST['filter']['category'] as $category)
            $modx->db->query("insert into `modx_a_fc` set filter_id = '$id', modx_id = ".$category);
        die(header("Location: ".$url."b=filters&p=6"));
      break;
      default:
        $filters = $modx->db->makeArray($modx->db->query("select * from `modx_a_filters` order by filter_id desc"));
        $tpl = "/filters.tpl";
      break;
    }
    break;
  case "items":
    switch ($_GET['c']) {
      case "publish":
        $tiny_mce   = $shop->tinyMCE("description_*,introtext_*");
        $categories = $shop->getCategories();
        $title      = "Добавить новый товар";
        $tpl        = "products_add.tpl";
      break;
      case "edit":
        $tiny_mce   = $shop->tinyMCE("description_*,introtext_*");
        // Список категорий к которым можно привязывать товар
        $categories = $shop->getCategories();
        // Данные о товаре
        $product    = $shop->getProduct($_GET['i']);
        // значения фильтров
        $filters      = $shop->getProductFilters ($product);
        // переводы
        foreach ($languages as $lang)
          $translate[$lang] = $shop->getProductTranslate($product['product_id'], $lang);
        $title      = "Редактировать товар";
        $tpl        = "products_edit.tpl";
      break;
      case "save":
        $modx->db->query("
                INSERT INTO `modx_a_products` SET
                  product_created     = '".date("Y-m-d H:i:s")."',
                  product_available   = '".$modx->db->escape($_POST['add']['available'])."',
                  product_visible     = '".$modx->db->escape($_POST['add']['visible'] == "yes" ? 1 : 0)."',
                  product_code        = '".$modx->db->escape($_POST['add']['code'] == "" ? time() : $_POST['add']['code'])."',
                  product_url         = '".$modx->db->escape($_POST['add']['url'])."',
                  product_cover       = '".$modx->db->escape($_POST['add']['cover'])."',
                  product_friends     = '".$modx->db->escape($_POST['add']['friends'])."',
                  tv_seo_title        = '".$modx->db->escape($_POST['add']['tv_seo_title'] == "" ? $_POST['add']['name'] : $_POST['add']['tv_seo_title'])."',
                  tv_seo_description  = '".$modx->db->escape($_POST['add']['tv_seo_description'])."',
                  tv_seo_keywords     = '".$modx->db->escape($_POST['add']['tv_seo_keywords'])."',
                  product_price       = '".$modx->db->escape($_POST['add']['price'])."'");
        $id = $modx->db->getInsertID();
        // языковые версии товара
        foreach ($languages as $lang)
          $shop->createProductTranslate($id, $lang, $_POST['add'][$lang]);
        // Linking with categories
        if (is_array($_POST['add']['category']))
          foreach ($_POST['add']['category'] as $category)
            $shop->linkProductToCategory($id, $category);
        // Linking with filters
        $shop->clearProductFilterValues($id);
        if (is_array($_POST['filter']))
          foreach ($_POST['filter'] as $key => $value)
            $shop->fillProductFilterValue($id, $key, $value);
        // Moving files into new directory
        if (!file_exists(MODX_BASE_PATH."assets/images/items/$id"))
          mkdir(MODX_BASE_PATH."assets/images/items/$id");
        $files = glob(MODX_BASE_PATH."assets/images/items/tmp/*");
        if (is_array($files))
          foreach ($files as $file) {
            copy($file, str_replace("tmp", $id, $file));
            unlink($file);
          }
        file_put_contents(MODX_BASE_PATH . $modx->config['shop_hotline_filename'], $shop->priceListView('hotline'));
        file_put_contents(MODX_BASE_PATH . $modx->config['shop_pn_filename'], $shop->priceListView('yandex'));
        die(header("Location: ".$url."b=items&c=".reset($_POST['add']['category'])."&w=item_added"));
      break;
      case "update":
        $query = "UPDATE `modx_a_products` SET
                  product_available   = '".$modx->db->escape($_POST['edit']['available'])."',
                  product_visible     = '".$modx->db->escape($_POST['edit']['visible'] == "yes" ? 1 : 0)."',
                  product_code        = '".$modx->db->escape($_POST['edit']['code'] == "" ? time() : $_POST['edit']['code'])."',
                  product_url         = '".$modx->db->escape($_POST['edit']['url'])."',
                  product_price       = '".$modx->db->escape($_POST['edit']['price'])."',
                  product_cover       = '".$modx->db->escape($_POST['edit']['cover'])."',
                  product_friends     = '".$modx->db->escape($_POST['edit']['friends'])."',
                  tv_seo_title        = '".$modx->db->escape($_POST['tv_seo_title'] == "" ? $_POST['edit']['name'] : $_POST['tv_seo_title'])."',
                  tv_seo_description  = '".$modx->db->escape($_POST['tv_seo_description'])."',
                  tv_seo_keywords     = '".$modx->db->escape($_POST['tv_seo_keywords'])."'
                  WHERE product_id    = '".$modx->db->escape($_POST['edit']['id'])."'";
        $modx->db->query($query);
        $id = $modx->db->escape($_POST['edit']['id']);
        // языковые версии товара
        foreach ($languages as $lang)
          $shop->updateProductTranslate($id, $lang, $_POST['edit'][$lang]);
        // Linking with filters
        $shop->clearProductFilterValues($id);
        if (is_array($_POST['filter']))
          foreach ($_POST['filter'] as $key => $value)
            if($value != '')
              $shop->fillProductFilterValue($id, $key, $value);
        // Linking with categories
        $shop->clearProductCategories($id);
        if (is_array($_POST['edit']['category']))
          foreach ($_POST['edit']['category'] as $category)
            $shop->linkProductToCategory($id, $category);          
        file_put_contents(MODX_BASE_PATH . $modx->config['shop_hotline_filename'], $shop->priceListView('hotline'));
        file_put_contents(MODX_BASE_PATH . $modx->config['shop_pn_filename'], $shop->priceListView('yandex'));
        die(header("Location: ".$url."b=items&w=item_updated"));
      break;
      case "delete":
        $shop->deleteProduct ($_GET['i']);
        die(header("Location: ".$url."b=items"));
      break;
      default:
        $items    = $shop->getProductList($_GET['s'], $_GET['category']);
        $category = ($_GET['category'] != '') ? "&category=".$_GET['category'] : '';
        $s        = ($_GET['s'] != '') ? "&s=".$_GET['s'] : '';
        $modx->setPlaceholder('url',$url."b=items".$category.$s."&p=");
        $pagin    = $shop->getPaginate();
        $tpl      =  "products.tpl";
      break;
    }
    break;
  case "banners":
    switch ($controller) {
      case "upload":
        $folder = MODX_BASE_PATH . "assets/images/banners/";
        foreach ($_FILES['uploader']['tmp_name'] as $key => $value) {
          $filename = preg_replace('/\D+/', '', microtime()).".".end(explode(".", $_FILES['uploader']['name'][$key]));
          move_uploaded_file($value, $folder."/". $filename);
          $modx->db->query("INSERT INTO `modx_a_banners` SET banner_image = '$filename'");
          $banner = array("banner_image" => $filename, "banner_active" => true);
          $categories = $modx->db->makeArray($shop->getCategories());
          include TPLS . "banner_one.tpl";
          echo TPLS . "banner_one.tpl";
        }
        die;
        break;
      case "delete":
        $banner = $modx->db->getRow($modx->db->query("SELECT * FROM `modx_a_banners` WHERE banner_id = '{$_GET[delete]}'"));
        $modx->db->query("DELETE FROM `modx_a_banners` WHERE banner_id = '{$_GET[delete]}'");
        unlink(MODX_BASE_PATH . "assets/images/banners/" . $banner['banner_image']);
        die(header("Location: ".$url."b=banners"));
        break;
      case "active":
        $modx->db->query("UPDATE `modx_a_banners` SET banner_active = '{$_GET[active]}' WHERE banner_id = {$_GET[update]}");
      default:
        if (0 < count($_POST['banner'])) {
          foreach($_POST['banner'] as $key => $value) 
            $modx->db->query("
              UPDATE `modx_a_banners` SET
                banner_link_type = '".(int)$value['type']."',
                banner_link_id   = '".(int)$value['link']."',
                banner_position  = '".(int)$value['position']."'
              WHERE banner_id  = '".(int)$key."'
            ");
        }
        $categories = $modx->db->makeArray($shop->getCategories());
        $banners = $modx->db->makeArray($modx->db->query("SELECT * FROM `modx_a_banners` ORDER BY banner_position ASC"));
        $tpl = "banners.tpl";
        break;
    }
    break;
  case "recalls":
    switch ($_GET['c']) {
      case 'delete':
        $shop->deleteRecall($_GET['i']);
        die(header("Location: ".$url."b=recalls&w=recall_deleted"));
        break;
      case 'update':
        $shop->updateRecall($_POST['recall']);
        die(header("Location: ".$url."b=recalls&w=recall_updated"));
        break;
      case 'edit':
        $recall = $shop->getRecall($_GET['i']);      
        $tpl = "recall_edit.tpl";
        break;
      
      default:
        if($_REQUEST['date_to'] != ''){
          $_REQUEST['date_from'] = ($_REQUEST['date_from'] != '' ? $_REQUEST['date_from'] : '0000-00-00 00:00:00');
          $date     = ' AND (recall_pub_date between "'.$modx->db->escape($_REQUEST['date_from']).' 00:00:00'.'" and "'.$modx->db->escape($_REQUEST['date_to']).' 23:59:59'.'")  ';
          $date_url = '&date_from='.$_REQUEST['date_from'].'&date_to='.$_REQUEST['date_to'];
        }
        else
          $_REQUEST['date_to'] = date("Y-m-d");
        if($_REQUEST['status'] != '' AND $_REQUEST['status'] != '-1'){
          $status     = ' AND recall_moderated = '.$modx->db->escape($_REQUEST['status']);
          $status_url = '&status='.$_REQUEST['status'];
        }
        $recalls  = $shop->getRecalls($date, $status);
        $modx->setPlaceholder('url', $url."b=recalls".$date_url.$status_url."&p=");
        $pagin = $shop->getPaginate(); 
        $tpl   = "recalls.tpl";
        break;
    }    
    break;
  case "orders":
    if (count($_POST['order']) > 0) {
      $shop->updateOrder($_POST['order']);
      die(header("Location: ".$url."b=orders&w=order_updated&id=".$_POST['order']['order_id']));
    }
    if ($_GET['order'] != '') {
      $order    = $shop->getOrder($_GET['order']);
      $delivery = json_decode($order['order_client'] != '' ? $order['addr'] : $order['order_delivery'], true);
      $items    = $shop->getOrderItems($_GET['order']);
      $managers = $shop->getManagerList();           
      $tpl = "order_details.tpl";
    } else {
      $orders  = $shop->getOrders();
      $modx->setPlaceholder('url', $url."b=orders&p=");
      $pagin    = $shop->getPaginate(); 
      $tpl = "orders.tpl";
    }
    break;
  case "import":    
    switch($_REQUEST['submit']){
      case'import_csv':
        $file_type  = explode('.',$_FILES['file']['name']);     
        $uploadfile = MODX_BASE_PATH.$modx->config['shop_import_dir'].'import.csv'; // имя файла CSV, который сохраняем на сервере

        if(strtolower(end($file_type)) == 'csv' AND move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
          $products_codes = array();
          $query          = $modx->db->query('SELECT product_code, product_id FROM modx_a_products');
          while ($row = $modx->db->getRow($query)) {
            $products_codes[$row['product_id']] = $row['product_code'];
          }         
          $count = 0;
          $head_array = array();              
          $handle = fopen($uploadfile,"r");
          while (($data = $shop->fgetcsv($handle)) !== FALSE) {           
            if ($count == 1) {//если строка с названиями полей то заносим их в массив
              for ($i = 0; $i < count($data); $i++) {
                $head_array[] = trim($data[$i]);
              }
            }
            if ($count != 0 && $count != 1) {//пропускаем первую строку с названиями полей      
              $item = array();   
              $pc   = $data[0];                        
              for ($i = 0; $i < count($data); $i++) {
                $item[] = $head_array[$i].'="'.$modx->db->escape($data[$i]).'"';                    
              }
              if (count($item) > 0) { 
                if(in_array($pc, $products_codes)){
                  $sql = 'UPDATE modx_a_products SET '.implode(',', $item).' WHERE product_code = "'.$pc.'" ';                      
                }
                else{
                  $sql = 'INSERT INTO modx_a_products SET '.implode(',', $item);
                }
                $modx->db->query($sql);
              }
            }
            $count++;
          }
          fclose($handle);
          $_GET['w'] = 'import_ok';
        }
        else
          $_GET['w'] = 'import_error_csv';
        break;

      case 'import_progress':
        //импорт товаров из разбитых файлов
        $filename = MODX_BASE_PATH.$modx->config['shop_import_dir'].'tmp-'.$_GET['counter'].'.xml';
        if(file_exists($filename)){
          $shop->importProduct($filename);
          unlink($filename);
        }
        if($_GET['counter'] != $_GET['limit']){
          include TPLS . "import_progress.tpl";
          die;        
        }
        else
          $_GET['w'] = 'import_ok'; 
        break;
      case 'import':    
        $file_type  = explode('.',$_FILES['file']['name']);        
        switch(strtolower(end($file_type))){
          case 'xml':
            // загружаем и разбиваем файл xml 
            $uploadfile = MODX_BASE_PATH.$modx->config['shop_import_dir'].$modx->config['shop_import_filename']; // имя файла XML, который сохраняем на сервере
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile) OR is_file($uploadfile)) {
              if($modx->config['shop_import_step'] != 0){ 
                set_time_limit(90);
                $str_xml=file_get_contents($uploadfile);
                $x_r=array('/<\?xml[^>]*?\?>/i','/<root[^>]*?>/i','/<\/root>/i');
                $str_xml=preg_replace($x_r,'',stristr($str_xml,'<item>'));
                preg_match_all("/<item>(.*)<\/item>/isU", $str_xml, $x_res);
                foreach ($x_res[0] as $item){ 
                  $i++; 
                  $items .= $item;
                  if(!($i % $modx->config['shop_import_step'])) { 
                    $x_arr[] = $items; 
                    unset($items); 
                  }
                } 
                if($items != '')
                  $x_arr[]=$items;
                $i=1; 
                foreach ($x_arr as $value){ 
                  $value = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<root>\n".$value."\n</root>";
                  file_put_contents(MODX_BASE_PATH.$modx->config['shop_import_dir'].'tmp-'.$i++.'.xml',$value); 
                }
                $_GET['limit']   = count($x_arr);
                $_GET['counter'] = 1;
                include TPLS . "import_progress.tpl";
                die;          
              }
              else{
                //импорт товаров если файл не разбиваем
                $shop->importProduct($uploadfile);
                $_GET['w'] = 'import_ok';
              }
            }
            break;
          default:
            $_GET['w'] = 'import_error_xml';
          break;
        }
        break;
    
    case 'export':   
      //Экспорт заказов    
        $filename = MODX_BASE_PATH.$modx->config['shop_export_dir'].$modx->config['shop_export_filename'];      
        $order_status = array(
          '0' => 'Удален',
          '1' => 'Новый',
          '2' => 'Отправлен',
          '3' => 'Доставлен'
          );
        $order_pay = array(
          '0' => 'Не оплачен',
          '1' => 'Оплачен'
          );
        if($_POST['date_from'] != '' AND $_POST['date_to'] != ''){
          $date = '(order_created between "'.$_POST['date_from'].' 00:00:00'.'" and "'.$_POST['date_to'].' 23:59:59'.'")  ';
        }      
        $orders = $modx->db->query('select o.*, ua.fullname, ua.phone, ua.email       
          from modx_a_order as o
          join modx_web_user_attributes as ua
          on internalKey=o.order_client
          where '.$date.($_POST['status'] == -1 ? "" : ' and order_status="'.$_POST['status'].'"').'');
        $count_orders = $modx->db->getRecordCount($orders);
        if($count_orders > 0){
          $xml = new XMLWriter();
          $xml->openMemory();
          $xml->startDocument('1.0', 'utf-8'); 
          $xml->startElement("root"); 
          while ($order = $modx->db->getRow($orders)) {          
            $xml->startElement("order");
              $xml->writeElement("created", $order['order_created']);
              $xml->writeElement("status", $order_status[$order['order_status']]);
              $xml->writeElement("status_code", $order['order_status']);
              $xml->writeElement("status_pay", $order_pay[$order['order_status_pay']]);
              $xml->writeElement("manager", $order['order_manager']);
              $xml->startElement("client");
                $xml->writeElement("name", $order['fullname']);
                $xml->writeElement("phone", $order['phone']);
                $xml->writeElement("email", $order['email']);
              $xml->endElement();
              $xml->startElement("delivery");
                $xml->writeElement("type", $order['order_delivery']);
                $xml->writeElement("cost", $order['order_deliver_cost']);
              $xml->endElement();
              $products = $modx->db->query('select op.*, p.product_id, p.product_url
                from modx_a_order_products as op
                join modx_a_products as p
                on p.product_id=op.product_id 
                where op.order_id="'.$order['order_id'].'"
                ');
              $xml->startElement("products");
              while($product = $modx->db->getRow($products) ){
                    $xml->startElement("product");
                    $xml->writeElement("name", $product['product_id']);
                    $xml->writeElement("url", $product['product_url']);
                    $xml->writeElement("price", $product['product_price']);
                    $xml->writeElement("count", $product['product_count']);
                  $xml->endElement();
              }          
              $xml->endElement();
              $xml->writeElement("cost", $order['order_cost']);
              $xml->writeElement("currency", "1");//тут код или вытаскивать название валюты??
              $xml->writeElement("comment", $order['order_comment']);
            $xml->endElement();        
          }
          $xml->endElement(); //закрытие корневого элемента root
          file_put_contents($filename, $xml->outputMemory()); //завершение записи в XML  
          $_GET['w'] = 'export_ok';

          //скачиваем файл
          $file = $modx->config['site_url'].$modx->config['shop_export_dir'].$modx->config['shop_export_filename'];
          header("Content-Type: application/octet-stream");
          header("Accept-Ranges: bytes");
          header("Content-Length: ".filesize($file));
          header("Content-Disposition: attachment; filename=".$modx->config['shop_export_filename']);  
          readfile($file);
          die;
        }
        break;
    }
    switch ($modx->config['shop_export_period']) {
      case '2':
        $date_begin = date("Y-m-d",strtotime("-1 week"));
        break;
      case '3':
        $date_begin = date("Y-m-d",strtotime("-1 day"));
        break;
      case '4':
        $date_begin = date("Y-m-d",'0000-00-00');
        break;
      default:
        $date_begin = date("Y-m-d",strtotime("-1 month"));
        break;
    }
    
    $tpl = 'import.tpl';
    break;
}

if (isset($tpl)) {
  ob_start();
  include TPLS . $tpl;
  $res['content'] = ob_get_contents();
  ob_end_clean();
}
include TPLS . "index.tpl";
die;