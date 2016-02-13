<?php
/**
 * Обработчик входящих данных
 */

	/**
	 * Отправка отзыва со страницы товара
	 *	recall
	 *	@name
	 *	@email
	 *	@text
	 */
	$_SESSION['hash']['recall'] = '';
	if (count($_POST['recall']) > 0 && $_SESSION['hash']['recall'] != md5(serialize($_POST['recall']))) {
		$modx->db->query("INSERT INTO `modx_a_recall` SET 
			recall_name     = '".$modx->db->escape(strip_tags($_POST['recall']['name']))."',
			recall_email    = '".$modx->db->escape(strip_tags($_POST['recall']['email']))."',
			recall_text     = '".$modx->db->escape(strip_tags($_POST['recall']['text']))."',
			recall_product  = '".$modx->db->escape(strip_tags($_POST['recall']['product']))."',
			recall_pub_date = '".date("Y-m-d H:i:s")."'
			");
		$js = 'alert("Спасибо за Ваш комментарий! Он будет опубликован после проверки модератором сайта")';
		$_SESSION['hash']['recall'] = md5(serialize($_POST['recall']));
	}

	/**
	 * Авторизация
	 */
	if (count($_REQUEST['auth']) > 0) {
		$user = $modx->runSnippet("Auth", Array("email" => $_REQUEST['auth']['email'], "pass" => $_REQUEST['auth']['pass']));
		switch ($user) {
      case 11: $modx->setPlaceholder("error", "not_found"); break;
      case 13: $modx->setPlaceholder("error", "wrong_pwd"); break;
      case 159263: 
      	die(header("Location: ".$modx->makeUrl($_REQUEST['auth']['redirect'] > 0 ? $_REQUEST['auth']['redirect'] : $modx->config['profile']))); 
      	break;
  	}
	}
	/**
	 * Регистрация
	 */
	if (count($_POST['reg']) > 0 && $_SESSION['hash']['reg'] != md5(serialize($_POST['reg']))) {
			$dat   = $_POST['reg'];
			$email = filter_var($dat['email'], FILTER_SANITIZE_EMAIL);

      if (trim($dat['name']) == "") $modx->setPlaceholder("error", "empty_name");
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $modx->setPlaceholder("error", "wrong_email");
      if (mb_strlen($dat['pass'], 'UTF-8') < 7) $modx->setPlaceholder("error", "weak_pwd");
      if (trim($dat['pass']) != trim($dat['cfm'])) $modx->setPlaceholder("error", "pwd_mismatch");


      $_SESSION['hash']['reg'] = md5(serialize($_POST['reg']));
      if ($modx->getPlaceholder("error") == "") {
        $check = $modx->db->getRow($modx->db->query('
                    SELECT 
                    COUNT(*) AS "cnt" 
                    FROM `modx_web_user_attributes` 
                    WHERE email = "'.$modx->db->escape($email).'" '));

        if ($check['cnt'] > 0) {
          $modx->setPlaceholder("error", "already_exits");
        } else {
          $data = Array(
                  "username" => microtime(),
                  "password" => md5($dat['pass']),
                  "email"    => $modx->db->escape($email),
                  "fullname" => $modx->db->escape($dat['name']),
                  "phone"    => $modx->db->escape($dat['phone'])
              );
          $modx->db->query("INSERT INTO `modx_web_users` SET username = '{$data[username]}', password = '{$data[password]}'");
		    	$user_id  = $modx->db->getInsertId();
		    	$modx->db->query("INSERT INTO `modx_web_user_attributes` SET 
						internalKey = '$user_id', 
						fullname    = '{$dat[name]}',
						phone       = '{$dat[phone]}',
						email       = '{$email}'
		    		"); 
		    	$modx->db->query("INSERT INTO `modx_web_groups` SET webgroup = 1, webuser = '$user_id'");
          $shop->sendMail($email, "register", Array("email" => $email, "password" => $dat['pass']));
		    	$modx->runSnippet("Auth", Array("email" => $email, "autologin" => true));
          die(header("Location: ".$modx->makeUrl(9)));
        }
      }
  } elseif (count($_POST['reg']) > 0) {
          $modx->setPlaceholder("error", "double_penetration");

  }
  /**
   * Восстановление пароля
   */
  if (filter_var($_POST['recovery'], FILTER_VALIDATE_EMAIL)) {
    $pwd = $shop->gen_pass();
    $modx->db->query("UPDATE `modx_web_users` SET cachepwd = '".md5($pwd)."' WHERE id = (SELECT internalKey FROM `modx_web_user_attributes` WHERE email = '".filter_var($_POST['recovery'], FILTER_SANITIZE_EMAIL)."')");
    if ($modx->db->getAffectedRows() > 0) {
      $shop->sendMail($_POST['recovery'], "recovery", Array("password" => $pwd, "confirm_url" => $modx->makeUrl(3)."?recovery=".md5($pwd)));
      $js = "alert('На указанный email выслано письмо с новым паролем')";
    } else
      $js = "alert('Данный email не заргистрирован, проверьте его правильность')";
  }
  if (strlen($_GET['recovery']) == 32) {
    $modx->db->query("UPDATE `modx_web_users` SET password = cachepwd, cachepwd = '' WHERE cachepwd = '".$modx->db->escape($_GET['recovery'])."' LIMIT 1");
    if ($modx->db->getAffectedRows() > 0)     
      $js = "alert('Теперь вы можете войти в профиль используя пароль из письма')";
    else
      $js = "alert('Данный ключ восстановления более не действителен')";
  }
  /**
   * Выход из личного кабинета
   */
  if (isset($_GET['logout'])) {
    unset($_SESSION['webShortname']);
    unset($_SESSION['webFullname']);
    unset($_SESSION['webEmail']);
    unset($_SESSION['webValidated']);
    unset($_SESSION['webInternalKey']);
    unset($_SESSION['webValid']);
    unset($_SESSION['webUser']);
    unset($_SESSION['webFailedlogins']);
    unset($_SESSION['webLastlogin']);
    unset($_SESSION['webnrlogins']);
    unset($_SESSION['webUsrConfigSet']);
    unset($_SESSION['webUserGroupNames']);
    unset($_SESSION['webDocgroups']);
    unset($_SESSION['webuser']);
    unset($_SESSION['cart']);
    unset($_SESSION['order_num']);
    unset($_SESSION['order_cost']);
    die(header("Location: /"));
  }
  /**
   * Обновление профиля
   */
  if (count($_POST['profile']) > 0 && $_SESSION['webuser']['email'] != "") {
  	$p = $_POST['profile'];
    // check email duplicate
    $cemail = $modx->db->getRow($modx->db->query("
        select
        count(*) as 'cnt' 
        from `modx_web_user_attributes` 
        where  email = '".$modx->db->escape($p['email'])."' and 
        internalKey != '".$_SESSION['webuser']['internalKey']."'"));
    $modx->db->query("update `modx_web_user_attributes` set
        fullname = '".$modx->db->escape($p['name'])."',
        phone    = '".$modx->db->escape($p['phone'])."',
        addr     = '".$modx->db->escape(json_encode($p['addr']))."',
        delivery = '".$modx->db->escape($p['delivery'])."'
        ".($cemail['cnt'] == 0 ? ", email = '".$modx->db->escape($p['email'])."'" : "")."
        where internalKey = '".$_SESSION['webuser']['internalKey']."'
    ");
    // смена пароля если заказывали
		if (md5($p['old']) == $_SESSION['webuser']['password']){
      if ($p['new'] == $p['cfm']){
        if (strlen($d['pass']['new']) >= 7)
          $modx->db->query("update `modx_web_users` set password = '".md5($p['new'])."' WHERE id = ".$_SESSION['webuser']['internalKey']);
        else
          $modx->setPlaceholder("error", "weak_pwd");
      } else
        $modx->setPlaceholder("error", "pwd_mismatch");
    }elseif ($p['old'] != "")
      $modx->setPlaceholder("error", "wrong_pwd");
    // обновляем данные в сессии
    $modx->runSnippet("Auth", Array("email" => $p['email'], "autologin" => true));
    // сообщение об успешном обновлении данных
    if ($modx->getPlaceholder("error") == "")
 			$modx->setPlaceholder("success", "Данные профиля успешно обновлены");
  }
	/**
	 * Создание заказа
	 */
	if (count($_POST['order']) > 0) {
		// авторегистрация
		if ($_SESSION['webuser']['email'] == "") {
	    $check = $modx->db->getRow($modx->db->query("
        SELECT 
        COUNT(*) AS 'cnt'
        FROM `modx_web_user_attributes`
        WHERE email    = '".$modx->db->escape($_POST['order']['email'])."'"));
	    if ($check['cnt'] > 0)
	    	$modx->setPlaceholder("error", "already_exists");
	    else {
	    	$username = $shop->gen_pass(10);
	    	$password = $shop->gen_pass(10);
	    	$modx->db->query("INSERT INTO `modx_web_users` SET username = '$username', password = MD5('$password')");
	    	$user_id  = $modx->db->getInsertId();
	    	$modx->db->query("INSERT INTO `modx_web_user_attributes` SET 
					internalKey = '$user_id', 
					fullname    = '".$modx->db->escape($_POST['order']['fullname'])."',
					phone       = '".$modx->db->escape($_POST['order']['phone'])."',
					email       = '".$modx->db->escape($_POST['order']['email'])."',
					delivery    = '".$shop->number($_POST['order']['delivery'])."'
	    		"); 
	    	$modx->db->query("INSERT INTO `modx_web_groups` SET webgroup = 1, webuser = '$user_id'");
	    	$modx->runSnippet("Auth", Array("email" => $_POST['order']['email'], "autologin" => true));
        $shop->sendMail($email, "register", Array("email" => $_POST['order']['email'], "password" => $password));
	    }
		}
		// определяем вид доставки
		if (is_numeric($_POST['order']['addr'])) {
			$tmp  = json_decode($_SESSION['webuser']['addr'], true);
			$key  = $shop->number($_POST['order']['addr']);
			$addr = Array(
				"index" => $tmp['index'][$key], 
				"city"  => $tmp['city'][$key], 
				"ave"   => $tmp['ave'][$key], 
				"house" => $tmp['house'][$key], 
				"room"  => $tmp['room'][$key]);
		} else $addr = $_POST['addr'];
		$addr['type'] = $shop->number($_POST['order']['delivery']);
		// пишем данные о заказе
		$modx->db->query("INSERT INTO `modx_a_order` SET
			order_created       = '".date("Y-m-d H:i:s")."',
			order_client        = '".$_SESSION['webuser']['internalKey']."',
			order_delivery      = '".$modx->db->escape(json_encode($addr))."',
			order_delivery_cost = (select delivery_cost from `modx_a_delivery` where delivery_id = '".$addr['type']."'),
			order_comment       = '".$modx->db->escape(strip_tags($_POST['order']['comment']))."'
			"); 
		$order_id = $modx->db->getInsertId();
    // стоимость доставки
    $delivery = $modx->db->getRow($modx->db->query("SELECT delivery_cost FROM `modx_a_delivery` WHERE delivery_id = '".$addr['type']."'"));
		// формируем письмо и данные о товарах в заказе
    $items = $modx->db->query("
        SELECT * 
        FROM `modx_a_products` p
        JOIN `modx_a_product_strings` s ON s.product_id = p.product_id AND s.translate_lang = '".$modx->config['lang']."'
        WHERE p.product_id IN (".implode(",", array_keys($_SESSION['cart'])).") ");
    while ($item = $modx->db->getRow($items)) {
      $item['product_url']   = $modx->makeUrl($modx->config['shop_page_item']).$item['product_url'];
      $item['cart_quantity'] = $_SESSION['cart'][$item['product_id']];
      $item['product_price'] = $shop->getPrice($item['product_price'], $_SESSION['currency']);
      $item['cart_sum']      = round($item['cart_quantity'] * $item['product_price'], 2);
      $mail['total']				+= $item['cart_sum'];
      $mail['items']        .= $modx->parseChunk("mail_order_item", $item);
      $modx->db->query("INSERT INTO `modx_a_order_products` SET 
				order_id      = '$order_id',
				product_id    = '".$item['product_id']."',
				product_count = '".$_SESSION['cart'][$item['product_id']]."',
				product_price = '".$item['product_price']."'
      	");
    }
    $mail['delivery_cost'] = $delivery['delivery_cost'];
    $mail['items'] = $modx->parseDocumentSource($mail['items']);
    // письмо покупателю и менеджеру
    $shop->sendMail($_SESSION['webuser']['email'], "order", $mail);
    $shop->sendMail($modx->config['emailsender'], "order", $mail);
    $modx->db->query("UPDATE `modx_a_order` SET order_cost = '{$mail[total]}' WHERE order_id = '$order_id'");
		$_SESSION['order_num']  = $order_id;
		$_SESSION['order_cost'] = $mail['total'];
    unset($_SESSION['cart']);
    die(header("Location: ".$modx->makeUrl(11)));
	}