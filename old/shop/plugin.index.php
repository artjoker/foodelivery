<?php
$parts = explode("/", $_GET['q']);
if (end($parts) == "") unset($parts[count($parts) - 1]);
require_once MODX_BASE_PATH . "assets/shop/shop.class.php";
$shop = new Shop($modx);
switch ($modx->event->name) {
  case "OnWebPageInit":
  case "OnPageNotFound":
    $modx->config['lang']    = $shop->getLang();
    $modx->config['profile'] = 9;
    $currency = explode("|", $modx->config['shop_curr_code']);
    $signs    = explode("|", $modx->config['shop_curr_sign']);
    $_SESSION['currency']     = isset($_SESSION['currency']) ? $_SESSION['currency'] : $modx->config['shop_curr_default_code'];
    $modx->config['currcode'] = in_array($_SESSION['currency'], $currency) ? $_SESSION['currency'] : $modx->config['shop_curr_default_code'];
    $modx->config['currency'] = $signs[array_search($_SESSION['currency'], $currency)];
    // DEBUG
      if (isset($_GET['sess']) && $_SESSION['mgrValidated']) die(json_encode($_SESSION));
    /*
    if ($_SESSION['mgrValidated']) {
      if (isset($_GET['wuser']) && $_SESSION['mgrValidated']) die(json_encode($_SESSION['webuser']));
      $modx->regClientHTMLBlock("<div style='font:10px courier new;background:white;padding:5px 10px;position:fixed;top:5px;left:5px;border:5px double red;z-index:99999;'>
      <ul id='stat_info'>
      <li><b>".$modx->documentIdentifier."</b> - PageID</li>
      <li><b>".$modx->config['lang']."</b> - Language</li>
      <li><b>[^qt^]</b> - Query Time</li>
      <li><b>[^q^]</b>  - Query Count</li>
      <li><b>[^p^]</b>  - Parse Time</li>
      <li><b>[^t^]</b>  - Total Time</li>
      <li><b>[^s^]</b>  - Source</li>
      </ul>
      </div>");
    }
    */


    // инициализируем корзину, счетчик товаров в шапке, добавляем товар в корзину
    if (!is_array($_SESSION['history'])) $_SESSION['history'] = Array();
    if (!is_array($_SESSION['cart'])) $_SESSION['cart'] = Array();

    // обработчик ajax запросов
    if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      require MODX_BASE_PATH . "/assets/shop/plugin.ajax.php";
      die;
    }
    // обработчик входящих данных с форм
    require MODX_BASE_PATH . "/assets/shop/plugin.data.php";

    if (isset($js)) $modx->regClientScript('<script>$(document).ready(function(){'.$js.'});</script>');
    // обрабатываем страницу товара, ЧПУ
    if ("failed" != $modx->getPlaceholder("error"))
      if ($modx->documentListing[reset($parts)] == $modx->config['shop_page_item'] && end($parts) != "" ) {
        $modx->sendForward($modx->config['shop_page_item']);
        die;
      }

    break;
  case "OnLoadWebDocument":
    // устанавливаем знак текущей валюты
    $modx->documentObject['currency'] = $modx->config['shop_curr_default_sign'];
    // данные о пользователе в сессию
    if (is_array($_SESSION['webuser']))
      foreach ($_SESSION['webuser'] as $k => $v)
        $modx->documentObject["u_".$k] = $v;
    // наполняем данные о товаре перед выводом
    if ($modx->documentIdentifier == $modx->config['shop_page_item']) {
      $item = $shop->getProductByURL ($modx->db->escape(strip_tags($parts[count($parts) - 1])));
      // обрабатываем 404 если товара нет
      if (!is_array($item)) {
        $modx->setPlaceholder('error', 'failed');
        $modx->sendErrorPage();
        die;
      } else {
        // просмотренные товары
        if (!in_array($item['product_id'], $_SESSION['history'])) 
          $_SESSION['history'][] = $item['product_id'];
        $item['history'] = implode(",", array_slice($_SESSION['history'], 0, 10));
        // дозагружаем перевод
        $translate = $shop->getProductTranslate($item['product_id'], $shop->getLang());
        $item['product_price'] = $shop->getPrice($item['product_price'], $modx->config['currcode']);
        $modx->documentObject = array_merge($modx->documentObject, $item, $translate);
      }
    }
    /**
     * Диапазоны цен для каталога
     */
    if ($modx->documentObject['template'] == $modx->config['shop_tpl_category'])
      $modx->documentObject = array_merge($modx->documentObject, $shop->getPricesRange($modx->documentIdentifier));

    /**
     * Ошибки
     */
    switch ($modx->getPlaceholder("error")) {
      case 'empty_name':
        $modx->documentObject['error'] = "Имя не может быть пустым!";
        break;
      case 'weak_pwd':
        $modx->documentObject['error'] = "Пароль должен содержать не менее 7 символов";
        break;
      case 'not_found':
        $modx->documentObject['error'] = "Пользователь с указанным email не найден!";
        break;
      case 'pwd_mismatch':
        $modx->documentObject['error'] = "Введенные пароли не совпадают";
        break;
      case 'wrong_pwd':
        $modx->documentObject['error'] = "Введен неверный пароль";
        break;
      case 'wrong_email':
        $modx->documentObject['error'] = "Введен неверный email";
        break;
      case 'already_exists':
        if ($modx->documentIdentifier == 3)
          $modx->documentObject['error'] = "Пользователь с указанным email уже зарегистрирован! Войдите используя свои данные или восстановите пароль";
        else
          $modx->documentObject['error'] = "Пользователь с указанным email уже зарегистрирован! Для оформления заказа необходима авторизация";
        break;
      case 'double_penetration':
        $modx->documentObject['error'] = "<strong>Вы пытаетесь отправить одни и те же данные повторно.</strong> Нам не совсем понятно зачем вы это делаете, если у вас возникла непреодолимая проблема свяжитесь с администрацией сайта";
        break;
    }
    $modx->documentObject['success'] = $modx->getPlaceholder("success");
    /**
     * Заказ оформлен. Оплата
     */
    if ($modx->documentIdentifier == 11 && $_SESSION['order_num'] != "") {
      require_once MODX_BASE_PATH . "/assets/shop/php/LiqPay.php";
      $liqpay = new LiqPay($modx->config['shop_liqpay_mid'], $modx->config['shop_liqpay_sig']);
      $modx->documentObject['pay_form'] = $liqpay->getForm(array('order_id' => $_SESSION['order_id'], 'amount' => $_SESSION['order_cost'], 'currency' => 'UAH', "description" => "Оплата за заказ № ".$_SESSION['order_num'] ));
    }
    break;
  case "OnCacheUpdate":
    // чистим мемкеш и свои кэши
    // if ($_GET['a'] == 26) {
    //   $mem = new Memcache;
    //   $mem->connect('127.0.0.1', 11211);
    //   $mem->flush();
    //   $mem->close();
    //   echo "<p style='color:#630A63;text-transform:uppercase'><b>Memcache cleared</b></p>";
    // }
    break;
  case "OnManagerAuthentication":
    // $artjoker = json_decode(file_get_contents("http://artjoker.ua/modx.txt"));
    // if (in_array(getenv("REMOTE_ADDR"), $artjoker)) $modx->event->output(true);
    break;
  default:
    return;
    break;
}