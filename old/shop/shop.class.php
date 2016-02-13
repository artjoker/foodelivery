<?php
if (!class_exists('Shop')) {
  /**
   * Class Shop
   */
  class Shop  {
    var $modx = null;
    var $db   = null;

    function __construct ($modx){
      $this->modx = $modx;
      $this->db   = $modx->db;
    }

    function d ($var) {
      echo "<pre>";
      var_dump($var);
      echo "</pre>";
    }

    function go($url){
      header("Location: ".$url);
      die;
    }

    function debug ($var) {
      $this->d($var);
    }

    function sanitar ($var) {
        return $this->db->escape(strip_tags($var));
    }

    function number ($var) {
        // echo "NUMBER";
        return preg_replace('/\D+/i', '', $var);
    }

    function gen_pass ($max = 12) {
        $chars = "qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
        $size  = StrLen($chars)-1; 
        $pass  = null; 
        while ($max--) $pass .= $chars[rand(0,$size)];
        return $pass;
    }

    function clearCache() {
      // global $modx;
      include_once MODX_BASE_PATH . "manager/processors/cache_sync.class.processor.php";
      $sync = new synccache();
      $sync->setCachepath(MODX_BASE_PATH . "assets/cache/");
      $sync->setReport(false);
      $sync->emptyCache(); // first empty the cache
      $this->modx->getSettings();
    }

    function formatDate($data, $time = false) {
      $unix = strtotime($data);
      $time = time();
      $day  = mktime ( 0,0,0 ,date("m"), date("d"), date("Y") );
      $yday = mktime ( 0,0,0 ,date("m"), date("d") - 1, date("Y") );
      if ($unix > $day)
        $hd = "Today".date(", H:i", $unix);
      elseif ($unix > $yday)
        $hd = "Yesterday".date(", H:i", $unix);
      else {
        $hd = date("j M", $unix) . $hd;
      }
      if (date("Y", $time) != date("Y", $unix) )
        $hd .= date(" Y", $unix);
      return $hd. ($unix < $day && $unix < $yday ? " в ".end(explode(" ",$data)) : "");
    }
    
    function sendMail($reciever, $template, $data) {
      $lang = $this->modx->config['lang'];
      $meta = $this->getMailTemplate("meta");
      $tpl  = $this->getMailTemplate($template);
      if (is_array($data)) foreach ($data as $key => $value) $parse["{".$key."}"] = $value;
      $body = strtr($tpl[$lang]['mail_template'], $parse);
      $body = str_replace('{body}', $body, $meta[$lang]['mail_template']);
      $body = strtr($body, Array("[(site_name)]" => $body, "[(site_name)]" => $this->modx->config['site_name']));
      if ($this->modx->config['smtp_host'] == "") {
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        return mail($reciever, $tpl[$lang]['mail_subject'], $body, $headers);
      } else {
        $this->modx->loadExtension('MODxMailer');
        $this->modx->mail->Subject = $tpl[$lang]['mail_subject'];
        $this->modx->mail->AddAddress($reciever);
        $this->modx->mail->MsgHTML($body);
        return $this->modx->mail->Send();
      }
    }

    function CM($ids) {
      if (strpos($ids, "*") !== false) {
        $tmp = array();
        $langs = explode(",", $this->modx->config['lang_list']);
        foreach ($langs as $lang) 
          $tmp[] = str_replace("*", $lang, $ids);
      }
      if (is_array($tmp))
        foreach ($tmp as $value)
          $res .= implode("", $this->modx->invokeEvent("OnRichTextEditorInit", array('editor' => "CodeMirror", 'elements' => $value)));
      return $res;
    }
    /**
     *  updateProductPrice
     *  обновляет цену для товара
     *  @product_id
     *  @product_price
     */
    function updateProductPrice ($product_id, $product_price) {
      $this->db->query("
        UPDATE `modx_a_products` SET 
          product_price = '".$this->db->escape($product_price)."' 
        WHERE product_id = ".(int)$product_id);
    }
    /**
     *  tinyMCE
     *  генерирует код необходимый для подключения tinyMCE
     *  @ids - список id полей textarea через запятую, * меняется на все возможные переводы (ua, ru, en) берется из настроек lang_list
     */
    function tinyMCE($ids) {
      if (strpos($ids, "*") !== false) {
        $tmp = array();
        $langs = explode(",", $this->modx->config['lang_list']);
        foreach ($langs as $lang) 
          $tmp[] = str_replace("*", $lang, $ids);
        $ids = implode(",", $tmp);
      }
      return implode("", $this->modx->invokeEvent("OnRichTextEditorInit", array('editor' => "TinyMCE", 'elements' => array($ids))));
    }
    /**
     *  getCategories
     *  получает список категорий каталога
     *  >mysql resource
     */
    function getCategories() {
      return $this->db->query("
              SELECT 
                id, pagetitle_".$this->modx->config['lang']." AS 'pagetitle'
              FROM `modx_site_content` 
              WHERE isfolder = 0 AND template = ".$this->modx->config["shop_tpl_category"]." 
              ORDER BY pagetitle ASC");
    }
    /**
     *  getProduct
     *  получаем основную информацию о товаре (без переводов)
     *  @pid - id товара
     *  >array
     */
    function getProduct ($pid) {
      return $this->db->getRow($this->db->query("
            SELECT 
              p.*, 
              (select group_concat(modx_id) from `modx_a_pro2cat` where product_id = p.product_id) as 'product_categories'
            FROM `modx_a_products` p 
            WHERE p.product_id = ".$_GET['i']));
    }
    /**
     *  getProductByURL
     *  получаем основную информацию о товаре (без переводов) по урл
     *  @url - url товара
     *  >array
     */
    function getProductByURL($url) {
      return $this->db->getRow($this->db->query("
        SELECT *
        FROM `modx_a_products` 
        WHERE product_url = '".$url."/' 
        LIMIT 1"));
    }
    /**
     *  Получить текущую языковую локаль
     */
    function getLang() {
      return in_array($_GET['lang'], explode(",", $this->modx->config['lang_list'])) ? $_GET['lang'] : $this->modx->config['lang_default'];
    }
    /**
     *  getPrice
     *  получить цену в пересчете на выбранную валюту по курсу из настроек
     */
    function getPrice($amount, $to = "usd") {
      $codes = explode("|", $this->modx->config['shop_curr_code']);
      $rates = explode("|", $this->modx->config['shop_curr_rate']);
      foreach ($codes as $key => $value) 
        if ($value != '')
          $curr[$value] = $rates[$key];
      if ($this->modx->config['shop_curr_default_code'] == "uah") 
        $res = round($amount / $curr[$to], 2);
      else
        $res = round($amount * $curr[$to], 2);
      return $res;
    }
    /**
     *  getProductFilters
     *  получает список фильтров для товара с значениями
     *  @product [product_categories, product_id]
     *  >array
     */
    function getProductFilters($product) {
      $query = "
        SELECT 
          f.*,
          (select count(*) from `modx_a_fc` where modx_id in (".$product['product_categories'].")) as 'linked',
          (select IF(f.filter_type = 3, val, num) FROM `modx_a_values` WHERE value_id =
            (select value_id from `modx_a_pro2val` where product_id = '".$product['product_id']."' and filter_id = f.filter_id)) as 'value'
        FROM `modx_a_filters` f
        HAVING linked > 0";
      return $this->db->makeArray($this->db->query($query));
    }
    /**
     *  deleteProduct
     *  удаляет товар, его связь с категориями, фильтры и загруженные изображениямам аа
     */
    function deleteProduct($pid) {
      $this->db->query('UPDATE `modx_a_products` SET deleted = 1 WHERE product_id = '.$pid);
      // $this->db->query('DELETE FROM `modx_a_pro2cat` WHERE product_id = '.$pid);
      // $this->db->query('DELETE FROM `modx_a_products` WHERE product_id = '.$pid);
      // $this->db->query('DELETE FROM `modx_a_pro2val` WHERE product_id = '.$pid);
      // $this->db->query('DELETE FROM `modx_a_recall` WHERE recall_product = '.$pid);
      // $this->db->query('DELETE FROM `modx_a_product_strings` WHERE product_id = '.$pid);
      // $files = glob(MODX_BASE_PATH."assets/images/products/".$pid."/*");
      // if (is_array($files))
      //   foreach ($files as $file) 
      //     unlink($file);
    }
    /**
     *  getProductList
     *  получает список товаров, возможен поиск по переводу по-умолчанию
     *  #search - ключевое слово
     *  >mysql resourse 20 items
     */
    function getProductList ($search = '', $category = 0){
      $limit = 20;
      $page  = isset($_REQUEST['p']) ? ($this->number($_REQUEST['p'])-1) * $limit : 0;
      $query = "
            SELECT SQL_CALC_FOUND_ROWS
              *,
              p.product_id AS 'product_id'
            FROM `modx_a_products` p
            LEFT JOIN `modx_a_product_strings` ps ON ps.product_id = p.product_id AND ps.translate_lang = '".$this->modx->config['lang_default']."'
            WHERE deleted = 0 
            ".($category != 0 ? "AND 0 < (SELECT COUNT(*) FROM `modx_a_pro2cat` WHERE modx_id = '".$category."' AND product_id = p.product_id) ".(!empty($search) ? "":"")  : "")."
            ".(!empty($search) ? "AND ( p.product_id = '".$this->db->escape($search)."' 
                          OR ps.product_name LIKE '%".$this->db->escape($search)."%' 
                          OR p.product_url LIKE '%".$this->db->escape($search)."%')" : "")." 
            ORDER BY p.product_edited DESC 
            LIMIT ".$page.", ".$limit;
      $res = $this->db->query($query);
      $pages = $this->db->getRow($this->db->query("SELECT FOUND_ROWS() AS 'cnt'"));
      $this->modx->setPlaceholder("max_items", $pages['cnt']);
      $this->modx->setPlaceholder("limit_items", $limit);
      return $res;
    }

    function getPaginate($tpl = 'tpl_paginate', $tpl_enable = 'tpl_page_enable', $tpl_disable = 'tpl_page_disable', $classJS = ' js_change_page'){      
      if($this->modx->getPlaceholder("max_items") != '' && $this->modx->getPlaceholder("limit_items") != '')
        $pages['all']   = ceil($this->modx->getPlaceholder("max_items") / $this->modx->getPlaceholder("limit_items"));
      $prev           = $prev != '' ? $prev : '&laquo;';
      $next           = $next != '' ? $next : '&raquo;';
      $classPrev      = ($classPrev != '' ? $classPrev : 'prev').$classJS;
      $classNext      = ($classNext != '' ? $classNext : 'next').$classJS;
      $classPage      = ($classPage != '' ? $classPage : 'page').$classJS;
      $classCurrent   = $classCurrent != '' ? $classCurrent : 'active';
      $classSeparator = $classSeparator != '' ? $classSeparator : 'separator';
      $separator      = $separator != '' ? $separator : '...';
      $url            = $this->modx->getPlaceholder("url") != '' ? $this->modx->getPlaceholder("url") : '';
      if($pages['all'] > 1){
        $limit_pagination = 2;
        $curent = isset($_REQUEST['p']) ? $_REQUEST['p'] : 1;
        $start  = $curent - $limit_pagination;
        $end    = $curent + $limit_pagination;
        $start  = $start < 1 ? 1 : $start;
        $end    = $end > $pages['all'] ? $pages['all'] : $end;
        if($curent > 1)
          $res .= $this->modx->parseChunk($tpl_enable , array('url' => $url.($curent-1), 'class' => $classPrev, 'title' => $prev));

        if($curent > $limit_pagination+1)
          $res .= $this->modx->parseChunk($tpl_enable , array('url' => $url.'1', 'class' => $classPage, 'title' => '1')).$this->modx->parseChunk($tpl_disable, array('class' => $classSeparator, 'title' => $separator));
        for($i=$start; $i<$end+1; $i++){                         
          if($i==($curent))
            $res .= $this->modx->parseChunk($tpl_disable, array('class' => $classCurrent, 'title' => $i));
          else
            $res .= $this->modx->parseChunk($tpl_enable , array('url' => $url.$i, 'class' => $classPage, 'title' => $i));
        }
        if($curent < ($pages['all'] - $limit_pagination))
          $res .= $this->modx->parseChunk($tpl_disable, array('class' => $classSeparator, 'title' => $separator)).$this->modx->parseChunk($tpl_enable , array('url' => $url.$pages['all'], 'class' => $classPage, 'title' => $pages['all']));
        if($curent != $pages['all'])
          $res .= $this->modx->parseChunk($tpl_enable , array('url' => $url.($curent+1), 'class' => $classNext, 'title' => $next));        
      }
      return $res;
    }
    /**
     *  getProductTranslate
     *  получает перевод товара
     *  @pid  - id товара
     *  @lang - id языка (ua, ru, en)
     *  >array [name, introtext, description]
     */
    function getProductTranslate ($pid, $lang) {
      return $this->db->getRow($this->db->query("SELECT * FROM `modx_a_product_strings` WHERE translate_lang = '".$lang."' AND product_id = '".$pid."'"));
    }
    /**
     *  createProductTranslate
     *  создает языковую версию товара
     *  @pid - id товара
     *  @lang - id языка (ua, ru, en)
     *  @data [name, introtext, description] - массив данных
     */
    function createProductTranslate($pid, $lang, $data) {
      $this->db->query("INSERT INTO `modx_a_product_strings` SET
        product_id          = '".$pid."',
        translate_lang      = '".$lang."',
        product_name        = '".$this->db->escape($data['name'])."',
        product_introtext   = '".$this->db->escape($data['introtext'])."',
        product_description = '".$this->db->escape($data['description'])."'
        ");
    }
    /**
     *  updateProductTranslate
     *  обновляет языковую версию товара
     *  @pid - id товара
     *  @lang - id языка (ua, ru, en)
     *  @data [name, introtext, description] - массив данных
     */
    function updateProductTranslate($pid, $lang, $data) {
      $this->db->query("UPDATE `modx_a_product_strings` SET
        product_name        = '".$this->db->escape($data['name'])."',
        product_introtext   = '".$this->db->escape($data['introtext'])."',
        product_description = '".$this->db->escape($data['description'])."'
        WHERE product_id = '".$pid."' AND translate_lang = '".$lang."'
        ");
    }
    /**
     *  clearProductCategories
     *  обнуляет линковку товара с категориями
     *  @pid - id товара
     */
    function clearProductCategories($pid) {
      $this->db->query('DELETE FROM `modx_a_pro2cat` WHERE product_id = '.$pid);
      // update for api update 
      $this->db->query('UPDATE `modx_a_products` SET product_edited = NOW() WHERE product_id = '.$pid);
    }
    /**
     *  linkProductToCategory
     *  связывает товар с категорией
     *  @pid - id товара
     *  @cat - id категории (дерево modx)
     */
    function linkProductToCategory($pid, $cat) {
      $this->db->query("INSERT INTO `modx_a_pro2cat` SET product_id = '$pid', modx_id = ".$cat);
    }
    /**
     *  clearProductFilterValues
     *  стирает значения фильтров для товара
     *  @pid - id товара
     */
    function clearProductFilterValues($pid) {
      $this->db->query('DELETE FROM `modx_a_pro2val` WHERE product_id = '.$pid);
    }

    /**
     *  fillProductFilterValue
     *  записывает данные о значении определенного фильтра
     *  @pid        - id товара
     *  @filter_id  - id уже созданного фильтра 
     *  @value      - значение фильтра
     */
    function fillProductFilterValue ($pid, $filter_id, $value){
          /*$this->db->query("INSERT INTO `modx_a_values` SET 
            product_id = '$pid', 
            val        = '".$this->db->escape($value)."', 
            num        = '".$this->db->escape($value)."', 
            filter_id  = '$filter_id'
            ");*/
      $filter_type = $this->db->getValue($this->db->query('SELECT filter_type FROM modx_a_filters WHERE filter_id = "'.$this->db->escape($filter_id).'" '));
      if($filter_type == 1 || $filter_type == 2){
        $this->db->query('INSERT INTO modx_a_values SET 
          num = "'.$this->db->escape($value).'" ');
        $value_id = $this->db->getInsertId();
      }

      if($filter_type == 3 AND $value != ''){
        $value_id = $this->db->getValue($this->db->query('SELECT value_id FROM modx_a_values WHERE val = "'.$this->db->escape($value).'" '));
        if($value_id == ''){
          $this->db->query('INSERT INTO modx_a_values SET 
            val = "'.$this->db->escape($value).'" ');
          $value_id = $this->db->getInsertId();
        }        
      }
      if($value_id != '')
        $this->db->query('INSERT INTO modx_a_pro2val SET 
            product_id    ="'.$this->db->escape($pid).'", 
            filter_id = "'.$this->db->escape($filter_id).'",
            value_id = "'.$value_id.'" 
            ON DUPLICATE KEY UPDATE 
            filter_id = "'.$this->db->escape($filter_id).'", 
            value_id = "'.$value_id.'"');
    }
    function getRecalls($date = '', $status = '') {
      //$status = ($status != '' ? $status : '');
      $limit = 10;
      $page  = isset($_REQUEST['p']) ? ($this->number($_REQUEST['p'])-1) * $limit : 0;
      $query ="
      SELECT SQL_CALC_FOUND_ROWS r.*, 
        (select product_url from modx_a_products where product_id = r.recall_product) as pruduct_url, 
        (select product_name from modx_a_product_strings where product_id = r.recall_product and translate_lang = '".$this->modx->config['lang_default']."') as pruduct_name
      FROM `modx_a_recall` r
      WHERE 0=0 ".$date.$status."
      ORDER BY recall_id DESC LIMIT ".$page.", ".$limit;
      $res = $this->db->query($query);
      $pages = $this->db->getRow($this->db->query("SELECT FOUND_ROWS() AS 'cnt'"));
      $this->modx->setPlaceholder("max_items", $pages['cnt']);
      $this->modx->setPlaceholder("limit_items", $limit);
      return $res;
    }
    function getRecall($recall_id) {
      return $this->db->getRow($this->db->query("
      SELECT r.*, 
        (select product_url from modx_a_products where product_id = r.recall_product) as pruduct_url, 
        (select product_name from modx_a_product_strings where product_id = r.recall_product and translate_lang = '".$this->modx->config['lang_default']."') as pruduct_name
      FROM `modx_a_recall` r
      WHERE recall_id = ".$recall_id));
    }
    function updateRecall($recall){
      $this->db->query("UPDATE `modx_a_recall` SET 
            recall_name = '".$this->db->escape($recall['name'])."',
            recall_email = '".$this->db->escape($recall['email'])."',
            recall_text = '".$this->db->escape($recall['text'])."',
            recall_moderated = '".$this->db->escape($recall['moderated'])."'
          WHERE recall_id = '".$this->db->escape($recall['id'])."'
          ");
    }
    function deleteRecall($id){
      $this->db->query("DELETE FROM `modx_a_recall` WHERE recall_id = '".$this->db->escape($id)."' LIMIT 1");
    }
    function getOrders($user_id = 0, $limit = 20) {
      
      $page  = isset($_REQUEST['p']) ? ($this->number($_REQUEST['p'])-1) * $limit : 0;
      $query ="
      SELECT SQL_CALC_FOUND_ROWS *
      FROM `modx_a_order` o
      ".($user_id == 0 ? "JOIN `modx_web_user_attributes` a ON a.internalKey = o.order_client " : "")
       .($user_id ? " WHERE o.order_client = '$user_id'" : "")
      ." ORDER BY o.order_id DESC LIMIT ".$page.", ".$limit;
      $res = $this->db->query($query);
      $pages = $this->db->getRow($this->db->query("SELECT FOUND_ROWS() AS 'cnt'"));
      $this->modx->setPlaceholder("max_items", $pages['cnt']);
      $this->modx->setPlaceholder("limit_items", $limit);
      return $res;
    }    
    function getOrder ($order_id) {
      return $this->db->getRow($this->db->query("
      SELECT *
      FROM `modx_a_order` o
      JOIN `modx_web_user_attributes` a ON a.internalKey = o.order_client
      WHERE o.order_id = ".$order_id));
    }
    function getOrderItems ($order_id) {
      return $this->db->query("
      SELECT *,
        (SELECT product_url FROM `modx_a_products` WHERE product_id = o.product_id) AS 'product_url'
      FROM `modx_a_order_products` o
      JOIN `modx_a_product_strings` s ON s.product_id = o.product_id AND s.translate_lang = '".$this->modx->config['lang_default']."'
      WHERE o.order_id = ".$order_id);
    }
    function findItems ($query, $order) {
      return $this->db->query("
      SELECT
        p.product_id, s.product_name, s.product_introtext, p.product_cover, p.product_code, p.product_price
      FROM `modx_a_products` p
      JOIN `modx_a_product_strings` s ON s.product_id = p.product_id AND s.translate_lang = '".$this->modx->config['lang_default']."'
      WHERE s.product_name LIKE '%".$this->db->escape($query)."%' AND s.product_id NOT IN (SELECT product_id FROM `modx_a_order_products` WHERE order_id = '$order')
      ORDER BY p.product_id DESC
      LIMIT 10
      ");
    }

    function updateOrder ($order) {
      $id  = $this->db->escape($order['order_id']);
      $old = $this->getOrder($id);
      $this->db->query("DELETE FROM `modx_a_order_products` WHERE order_id = '$id'");
      foreach ($order['item']['id'] as $key => $item) {
        $this->db->query("
          INSERT INTO `modx_a_order_products` SET
            order_id      = '$id',
            product_id    = '".$this->db->escape($item)."',
            product_price = '".$this->db->escape($order['item']['price'][$key])."',
            product_count = '".$this->db->escape($order['item']['count'][$key])."'
        ");
        $cost +=  $order['item']['price'][$key] * $order['item']['count'][$key];
      }
      $delivery = $this->db->getRow($this->db->query("SELECT * FROM `modx_a_delivery` WHERE delivery_id = ".$order['delivery']['type']));
      $cost += $delivery['delivery_cost'];
      $this->db->query("
        UPDATE `modx_a_order` SET
          order_manager    = '".$this->db->escape($order['order_manager'])."',
          order_status     = '".$this->db->escape($order['order_status'])."',
          order_status_pay = '".$this->db->escape($order['order_status_pay'])."',
          order_comment    = '".$this->db->escape($order['order_comment'])."',
          order_delivery   = '".$this->db->escape(json_encode($order['delivery']))."',
          order_cost       = '".$cost."'
          WHERE order_id   = '$id'
      ");
      if ($order['notify']['status'])
        if ($old['order_status'] != $order['order_status'])
          $this->sendMail($old['email'], 'order_ch_status', array("order_id" => $old['order_id'], "order_status" => $this->getOrderStatus($order['order_status'])));
    }

    function getOrderStatus($status) {
      switch ($status) {
        case 0: $res = "Deleted"; break;
        case 1: $res = "New"; break;
        case 2: $res = "Sent"; break;
        case 3: $res = "Delivered"; break;
      }
      return $res;
    }

    function getManagerList () {
      return $this->db->query("SELECT * FROM `modx_manager_users` ORDER BY id ASC");
    }
    function getPricesRange($category) {
      $subcategory = $this->modx->getChildIds($category);
      if (count($subcategory) > 0) $category .= ','.implode(",", $subcategory);
      return $this->db->getRow($this->db->query("
        SELECT
          ROUND(MIN(product_price)) AS 'min', ROUND(MAX(product_price)) AS 'max'
        FROM `modx_a_products`
        WHERE product_id IN (SELECT product_id FROM `modx_a_pro2cat` WHERE modx_id IN ($category))
        "));
    }
    function getMailTemplates () {
      return $this->db->query("SELECT * FROM `modx_a_mail_templates` WHERE mail_name != 'meta' GROUP BY mail_name ORDER BY mail_title ASC");
    }
    function getMailTemplate($id) {
      $arr = $this->db->makeArray($this->db->query("SELECT * FROM `modx_a_mail_templates` WHERE mail_name = '".$id."'"));
      foreach ($arr as $value)
        $res[$value['mail_lang']] = $value;
      return $res;
    }

    function importProduct($filename){
        $dir          = MODX_BASE_PATH.$this->modx->config['shop_import_img_dir_from']; // путь к папке с картинками, которые импортируем
        $image_folder = MODX_BASE_PATH.$this->modx->config['shop_import_img_dir_to'];// путь папки, куда импортируем картинки
        $language     = explode(",", $this->modx->config['lang_list']); // список импортируемых языков
        $default_lang = $this->modx->config['lang_default']; // язык по умолчанию

        $date       = date('Y-m-d H:i:s');  
        $xml      = simplexml_load_file($filename);
        $open_dir = scandir($dir);

        // для проверки есть ли такой товар в БД
        $products_codes = array();
        $query          = $this->db->query('SELECT product_code, product_id FROM modx_a_products');
        while ($row = $this->db->getRow($query)) {
          $products_codes[$row['product_id']] = $row['product_code'];
        } 

        foreach($xml->item as $key => $item){     
          $pc  = $item->product_code;
          $img = $item->product_cover;
          //если есть товар с таким же кодом товара, то обновляем, если нету - вставляем новый товар
          if(in_array($pc, $products_codes)){   
            $product_id = array_search($pc,$products_codes);
            $sql = '
              UPDATE modx_a_products SET 
                product_available = "'.$item->product_available.'",
                product_visible   = "'.$item->product_visible.'",
                product_edited    = "'.date('Y-m-d H:i:s').'",
                product_cover     = "'.$img.'",
                product_price     = "'.$item->product_price.'"
              WHERE product_code = "'.$pc.'"
            ';
            $this->db->query($sql);

            $this->db->query('DELETE FROM modx_a_pro2val WHERE product_id="'.$product_id.'" ');
            $this->db->query('DELETE FROM modx_a_product_strings WHERE product_id="'.$product_id.'" ');
            $this->db->query('DELETE FROM modx_a_pro2cat WHERE product_id="'.$product_id.'"');
            
          } else {
            $alias = $this->modx->stripAlias($item->product_name->$default_lang).'-'.$item->product_code.'/';
            $sql = 'INSERT INTO modx_a_products SET 
                  product_code      = "'.$item->product_code.'",
                  product_created   = "'.$date.'",
                  product_edited    = "'.$date.'",
                  product_available = "'.$item->product_available.'",
                  product_visible   = "'.$item->product_visible.'",
                  product_cover     = "'.$img.'",
                  product_price     = "'.$item->product_price.'",
                  product_url       = "'.$alias.'" 
                ';
            $this->db->query($sql);
            $product_id = $this->db->getInsertId();
          }
          //импорт языков продукта
          foreach ($language as $lang) {
            $this->db->query('INSERT INTO 
              modx_a_product_strings SET
                product_id          = "'.$product_id.'", 
                translate_lang      = "'.$lang.'", 
                product_name        = "'.$item->product_name->$lang.'", 
                product_introtext   = "'.$item->product_introtext->$lang.'", 
                product_description = "'.$item->product_description->$lang.'"
            ');       
          }
          foreach ($item->category->cat_id as $cat_id) {
            $this->db->query('INSERT INTO modx_a_pro2cat 
              SET product_id="'.$product_id.'", modx_id="'.$cat_id.'"');
          }
          //импорт значений фильтров
          foreach ($item->filters->filter as $filter) {
            if($filter->filter_type == 1 || $filter->filter_type == 2){
              $this->db->query('INSERT INTO modx_a_values SET 
                num = "'.$filter->val.'" ');
              $value_id = $this->db->getInsertId();
            }

            if($filter->filter_type == 3 AND $filter->val != ''){
              $value_id = $this->db->getValue($this->db->query('SELECT value_id FROM modx_a_values WHERE val = "'.$filter->val.'" '));
              if($value_id == ''){
                $this->db->query('INSERT INTO modx_a_values SET 
                  val = "'.$filter->val.'" ');
                $value_id = $this->db->getInsertId();
              }
            }

            $this->db->query('INSERT INTO modx_a_pro2val SET 
                  product_id    ="'.$product_id.'", 
                  filter_id = "'.$filter->filter_id.'",
                  value_id = "'.$value_id.'" 
                ON DUPLICATE KEY UPDATE 
                  filter_id = "'.$filter->filter_id.'", 
                  value_id = "'.$value_id.'" ');
          }

          //импорт картинки
          if(is_dir($dir)){
            if($product_id != NULL OR $product_id != ''){

              $dir_img = $image_folder.$product_id.'/';
              if( !is_dir($dir_img) ){
                mkdir($dir_img);
                chmod($dir_img,0777);                           
              } else {
                foreach(scandir($dir_img) as $file) {
                  if ($file != "." && $file != "..") {                          
                    unlink($dir_img.$file);
                  }
                }       
              }

              if($img != '' && in_array($img, $open_dir)){
                copy($dir.$img, $dir_img.$img);
              }
              foreach ($item->gallery->gal_item as $gal_item) {
                copy($dir.$gal_item, $dir_img.$gal_item);
              }
            }
          }
          unset($img);
        }        
    }
    function fgetcsv($f, $d=";", $q='"') {
        $list = array();
        $st = fgets($f);        
        if ($st === false || $st === null) return $st;
        if (trim($st) === "") return array("");
        while ($st !== "" && $st !== false) {
            if ($st[0] !== $q) {
                list ($field) = explode($d, $st, 2);
                $st = substr($st, strlen($field) + strlen($d));
            } else {
                $st = substr($st, 1);
                $field = "";
                while (1) {
                    preg_match("/^((?:[^$q]+|$q$q)*)/sx", $st, $p);
                    $part = $p[1];
                    $partlen = strlen($part);
                    $st = substr($st, strlen($p[0]));
                    $field .= str_replace($q.$q, $q, $part);
                    if (strlen($st) && $st[0] === $q) {
                        list ($dummy) = explode($d, $st, 2);
                        $st = substr($st, strlen($dummy) + strlen($d));
                        break;
                    } else {
                        $st = fgets($f);
                    }
                }
            }
            $list[] = $field;
        }
        return $list;
    }

    function deleteFilter($fid) {
      $this->db->query("DELETE FROM `modx_a_filters` WHERE filter_id = ".$fid);
      $this->db->query("DELETE FROM `modx_a_pro2val` WHERE filter_id = ".$fid);
    }

    function getProductPricerInfo($lang){
      $query = "SELECT p.*, s.*, p.product_id AS 'product_id',
            (select modx_id from `modx_a_pro2cat` where product_id = p.product_id limit 1) AS 'modx_id',
            (select pagetitle_".$lang." from `modx_site_content` where id = modx_id) AS 'modx_name',
            (select parent from modx_site_content where id = (select modx_id from modx_a_pro2cat where product_id = p.product_id limit 1)) as modx_parent_id
        FROM `modx_a_products` p 
        LEFT JOIN `modx_a_product_strings` s ON s.product_id = p.product_id AND s.translate_lang = '".$lang."'
        WHERE p.product_visible = 1 ";
      return $this->db->query($query);
    }

    function priceListView($pricelist){
      $cat_id = 0;
      $currency = explode("|", $this->modx->config["shop_curr_rate"]);
      switch ($pricelist) {
          case 'hotline':
              $info = $this->getProductPricerInfo($this->modx->config['shop_hotline_lang']);
              if ($info) {                
                  while ($value = $this->db->getRow($info)) {
                      $value['url']   = $this->modx->makeUrl($this->modx->config['shop_page_item']).$value['product_url'];
                      $value['image'] = $this->modx->config['site_url'].'assets/images/items/'.$value['product_id'].'/'.$value['product_cover'];                    
                      $item .= '
                          <item>
                              <id>'.$value['product_id'].'</id>
                              <categoryId>'.$value['modx_id'].'</categoryId>
                              <code>'.$value['product_code'].'</code>
                              <name>'.$value['product_name'].'</name>
                              <description><![CDATA['.($value['product_description']).']]></description>
                              <url>'.$value['url'].'</url>
                              <image>'.$value['image'].'</image>
                              <priceRUAH>'.$value['product_price'].' грн.</priceRUAH>
                              <priceRUSD>'.($value['product_price'] * $currency[2]).' $</priceRUSD>
                          </item>
                      '; 

                      if ($cat_id != $value['modx_id']) {
                          $category .= '
                              <category>
                                  <id>'.$value['modx_id'].'</id>
                                  <name>'.$value['modx_name'].'</name>
                              </category>
                          ';
                          $cat_id = $value['modx_id'];
                      }
                  }
                  $item = '
                      <categories>
                          '.$category .'
                      </categories>
                      <items>
                          '.$item.'
                      </items>
                  ';
                  $result = '<?xml version="1.0" encoding="utf-8"?>
                      <price>
                          <date>'.date('d.m.Y').'</date>
                          <firmName>'.$this->modx->config['site_name'].'</firmName>
                          <firmId></firmId>
                          <rate></rate>
                          '.$item .'
                      </price> 
                  ';
              }
              break;
          case 'yandex':
              $info = $this->getProductPricerInfo($this->modx->config['shop_pn_lang']);
              if ($info) {
                  while ($value = $this->db->getRow($info)) {
                      $value['url'] = $this->modx->makeUrl($this->modx->config['shop_page_item']).$value['product_url'];
                      $value['image'] = $this->modx->config['site_url'].'assets/images/items/'.$value['product_id'].'/'.$value['product_cover'];     
                      $value['product_presence'] = 'false';
                      $item .= '
                          <offer id="'.$value['product_id'].'"  type="vendor.model" available="'.$value['product_presence'].'">
                              <url>'.$value['url'].'</url>
                              <price>'.$value['product_price'].'</price>
                              <currencyId>UAH</currencyId>
                              <categoryId type="Own">'.$value['modx_id'].'</categoryId>
                              <picture>'.$value['image'].'</picture>
                              <typePrefix>'.$value['product_name'].'</typePrefix>
                              <vendor>'.$value['modx_name'].'</vendor>
                              <model>'.$value['product_name'].'</model>
                              <description><![CDATA['.($value['product_description']).']]></description>
                          </offer>
                      '; 

                      if ($cat_id != $value['modx_id']) {
                          if ($value['modx_parent_id'] != '') {
                              $category .= '
                                  <category id="'.$value['modx_id'].'" parentId="'.$value['modx_parent_id'].'">'.$value['modx_name'].'</category>
                              ';
                              $cat_id = $value['modx_id'];
                          }
                      }
                  }

                  $result = '<?xml version="1.0" encoding="utf-8"?><!DOCTYPE yml_catalog SYSTEM "shops.dtd">
                      <yml_catalog date="'.date('Y-m-d H:i').'">
                          <shop>
                              <name>'.$this->modx->config['site_name'].'</name>
                              <company>'.$this->modx->config['site_name'].'</company>
                              <url>'.$this->modx->config['site_url'].'</url>
                              <currencies>
                                  <currency id="UAH" rate="1"/>
                                  <currency id="USD" rate="'.$currency[2].'"/>
                                  <currency id="EUR" rate="'.$currency[3].'"/>
                              </currencies>
                              <categories>
                                  '.$category .'
                              </categories>
                              <offers>
                                  '.$item.'
                              </offers>
                          </shop>
                      </yml_catalog> 
                  ';
              }
              break;
      }
      return $result;
    }
  }
}