<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

	define ('ROOT', dirname(__FILE__));
	require_once(ROOT.'../../../manager/includes/protect.inc.php');
	include_once ROOT.'../../../manager/includes/config.inc.php';
	define('MODX_API_MODE', true);
	include_once(MODX_MANAGER_PATH.'/includes/document.parser.class.inc.php');

	$modx = new DocumentParser();
	$modx->db->connect();
	$modx->getSettings();
	global $modx;

	startCMSSession();
/**
 * Android API
 */
define('KEY', md5($_SERVER['SERVER_NAME']."Artjoker"));
require_once MODX_BASE_PATH . "assets/shop/shop.class.php";
$shop = new Shop($modx);
$lang = $modx->config['lang_default'];
$modx->config['lang'] = $lang;
// Currency config
$currency = explode("|", $modx->config['shop_curr_code']);
$signs    = explode("|", $modx->config['shop_curr_sign']);
$modx->config['currcode'] = $modx->config['shop_curr_default_code'];
$modx->config['currency'] = $signs[array_search($modx->config['shop_curr_default_code'], $currency)];
$response  = Array();
// Get request method and signature
$json      = json_decode(file_get_contents('php://input'), true);
// file_put_contents(MODX_BASE_PATH ."/assets/docs/log.log", $_SERVER['REQUEST_URI']."\n".file_get_contents('php://input')."\n\n",  FILE_APPEND);
$action    = $json['function'];
$signature = $json['signature'];
// Checking security hash
$check     = md5(json_encode($json['data']).KEY);

if (is_string($json['data'])) 
	$data = json_decode($json['data'], true);
else
	$data = $json['data'];
// Continue to parse request
if (is_array($data))
	foreach ($data as $key => $value) 
		$request[$key] = $modx->db->escape($value);
if ($check != $signature && $action != "get_catalog") $error = "Signature doesnt match!";
else
	switch ($action) {
		// register new user
		case "reg":
			$check = $modx->db->getRow($modx->db->query('
	      SELECT 
	      COUNT(*) AS "cnt" 
	      FROM `modx_web_user_attributes` 
	      WHERE email = "'.$request['email'].'" '));

	    if ($check['cnt'] > 0) {
	      $error = "User with that email already registered! Try another email";
	    } else {
	    	$username = $shop->gen_pass(6);
	    	$password = $shop->gen_pass(10);
	      $modx->db->query("INSERT INTO `modx_web_users` SET username = '{$username}', password = '".md5($password)."'");
	    	$user_id  = $modx->db->getInsertId();
	    	$modx->db->query("INSERT INTO `modx_web_user_attributes` SET 
					internalKey = '$user_id', 
					fullname    = '{$request[name]}',
					surname     = '{$request[surname]}',
					phone       = '{$request[phone]}',
					email       = '{$request[email]}',
					addr        = '".$modx->db->escape(json_encode(Array(
						"index" => '',
						"city"  => $request['city'],
						"ave"   => $request['street'],
						"house" => $request['house_number'],
						"room"  => $request['room_number'],
						)) )."'
	    		"); 
	    	$modx->db->query("INSERT INTO `modx_web_groups` SET webgroup = 1, webuser = '$user_id'");
	      $shop->sendMail($request['email'], "register", Array("email" => $request['email'], "password" => $password));

	      $response['response_code'] = 0;
	      $response['data']['user']['user_id'] = $user_id;
	    }
			break;
		// log in 
		case "auth":
			$user = $modx->runSnippet("Auth", Array("email" => $request['email'], "pass" => $request['pass'], "md5" => true));

			switch ($user) {
	      case 11: $error = "User with that email not found! Try another email"; break;
	      case 13: $error = "Wrong password!"; break;
	      case 159263: 
	      	$addr = json_decode($_SESSION['webuser']['addr'], true);
	      	
	      	$response = Array(
						"response_code" => 0, 
						"data"          => array(
						"user"          => 
		      		Array(
								"user_id" => $_SESSION['webuser']['internalKey'],
								"surname" => $_SESSION['webuser']['surname'],
								"name"    => $_SESSION['webuser']['fullname'],
								"email"   => $_SESSION['webuser']['email'],
								"phone"   => (string)$_SESSION['webuser']['phone'],
								"city"    => $addr['city'],
								"street"  => $addr['ave'],
								"house"   => $addr['house'],
								"flat"    => $addr['room']
		      		)));
	      	break;
	  	}
			break;
		// update user profile
		case "update_user":
			$check = $modx->db->getRow($modx->db->query('
	      SELECT 
	      COUNT(*) AS "cnt" 
	      FROM `modx_web_user_attributes` 
	      WHERE email = "'.$request['email'].'" 
	      AND internalKey != "'.$request['user_id'].'"'));

	    if ($check['cnt'] > 0) {
	      $error ="Пользователь с таким email уже зарегистрирован";
	    } else {
				$addr = json_encode(Array(
					"city"  => $request['city'],
					"ave"   => $request['street'],
					"house" => $request['house_number'],
					"room"  => $request['room_number']
					));
				$modx->db->query("UPDATE `modx_web_user_attributes` SET
					fullname = '{$request[name]}',
					surname = '{$request[surname]}',
					phone    = '{$request[phone]}',
					email    = '{$request[email]}',
					addr     = '{$addr}'
					WHERE internalKey = '{$request[user_id]}'");
				$response = array("response_code" => 0);
			}
			break;
		// password recovery
		case "forgot":
			$pwd = $shop->gen_pass();
	    $modx->db->query("UPDATE `modx_web_users` SET 
	    	password = '".md5($pwd)."' 
	    	WHERE id = (
	    		SELECT internalKey 
	    		FROM `modx_web_user_attributes` 
	    		WHERE email = '".filter_var($request['email'], FILTER_SANITIZE_EMAIL)."')");
	    if ($modx->db->getAffectedRows() > 0) {
	      $shop->sendMail($request['email'], "recovery", Array("password" => $pwd));
				$response = array("response_code" => 0);
	    } else
	      $error = "Email not registered! Check it";
			break;
		// change user password
		case "change_password":	
			$user = $modx->getWebUserInfo((int)$request['user_id']);
			if ($user['internalKey'] == "")
	      $error = "User with this ID doesnt exist";
			elseif ($request['old_password'] == $user['password']) {
	      $modx->db->query("update `modx_web_users` set password = '".$modx->db->escape($request['new_password'])."' WHERE id = ".$user['internalKey']);
	      $response = array("response_code" => 0);
	    } else
	      $error = "Invalid current password";
			break;
		// Create new order
		case "order":
	    $response['response_code'] = 0;
	    $user = $modx->getWebUserInfo($request['user_id']);
			// Generate main record of order
			$modx->db->query("INSERT INTO `modx_a_order` SET
				order_created  = '".date("Y-m-d H:i:s")."',
				order_client   = '".$user['internalKey']."',
				order_delivery = '{\"type\": \"".$request['delivery_type_id']."\"}',
				order_comment  = '".$modx->db->escape(strip_tags($request['comment']))."'
				"); 
			// Get order id
			$order_id = $modx->db->getInsertId();
			$ids = $mail = Array();
			foreach ($request['products'] as $key => $value)
				$ids[$value['id']] = $value['count'];
			// Generate order letter with product list and insert products into database
	    $items = $modx->db->query("
	        SELECT * 
	        FROM `modx_a_products` p
	        JOIN `modx_a_product_strings` s ON s.product_id = p.product_id AND s.translate_lang = '".$lang."'
	        WHERE p.product_id IN (".implode(",", array_keys($ids)).") ");
	    while ($item = $modx->db->getRow($items)) {
	      $item['product_url']   = $modx->makeUrl($modx->config['shop_page_item']).$item['product_url'];
	      $item['cart_quantity'] = $ids[$item['product_id']];
	      $item['product_price'] = $item['product_price'];
	      $item['cart_sum']      = round($item['cart_quantity'] * $item['product_price'], 2);
	      $mail['total']				+= $item['cart_sum'];
	      $mail['items']        .= $modx->parseChunk("mail_order_item", $item);
	      $modx->db->query("INSERT INTO `modx_a_order_products` SET 
					order_id      = '$order_id',
					product_id    = '".$item['product_id']."',
					product_count = '".$item['cart_quantity']."',
					product_price = '".$item['product_price']."'
	      	");
	    }
	    $delivery = $modx->db->getRow($modx->db->query("SELECT * FROM `modx_a_delivery` WHERE delivery_id = ".$request['delivery_type_id']));
			$mail['total']        += $delivery['delivery_cost'];
			$mail['delivery_cost'] = $delivery['delivery_cost'];
			$mail['currency']      = $modx->config['currency'];
	    $modx->db->query("UPDATE `modx_a_order` SET order_cost = '{$mail[total]}' WHERE order_id = '$order_id'");
	    $mail['items'] = $modx->parseDocumentSource($mail['items']);
	    // Send letters to admin and client
	    $shop->sendMail($user['email'], "order", $mail);
	    $shop->sendMail($modx->config['emailsender'], "order", $mail);
	    $response['response_code'] = 0;
			break;
		// Return info about single product
		case "product_info":
			$products = array();
			foreach ($_POST['data']['products'] as $value) 
				$products[] = (int)$value['id'];
	    $response['response_code'] = 0;
	    $response['data']['actual_products'] = $modx->db->makeArray($modx->db->query("
	    	SELECT
	    		product_id AS 'id',
	    		1 AS 'stock_quantity',
	    		produt_price AS 'price'
	    	FROM `modx_a_products` 
	    	WHERE product_id IN (".implode(",", $products).")
	    	"));
			break;
		// client order history
		case "history":
			$orders = $shop->getOrders((int)$request['user_id'], 200);
			while ($order = $modx->db->getRow($orders)) {
				switch ($order['order_status']) {
					case 0: $status = "Deleted"; break;
					case 1: $status = "New"; break;
					case 2: $status = "Sent"; break;
					case 3: $status = "Delivered"; break;
				}
				$o = array(
					"date"     => strtotime($order['order_created']),
					"price"    => $order['order_cost'],
					"currency" => "UAH",
					"status"   => $status
					);
				$items = $shop->getOrderItems($order['order_id']);
				while ($item = $modx->db->getRow($items)) 
					$o['products'][] = array("id" => $item['product_id'], "count" => $item['product_count']);
				
				$response['data']['history_orders'][] = $o;
			}
	    $response['response_code'] = 0;
			break;
		// Generate all database
		case "get_catalog":
			$file = "android";
			if (0 < $request['date']) {
				$dt = (int)$request['date'];
				$file = "android_update";
			} else $dt = 0;
			// products
			$query = "
				SELECT 
					p.product_id AS 'id',
					s.product_name AS 'name',
					s.product_description AS 'description',
					'none' AS 'manufacturer',
					'none' AS 'volume',
					p.product_price AS 'price',
					CONCAT('".$modx->config['site_url']."assets/images/items/', p.product_id, '/', p.product_cover) AS 'cover',
					p.product_visible AS 'visible',
					p.deleted
				FROM `modx_a_products` p 
				JOIN `modx_a_product_strings` s ON p.product_id = s.product_id AND s.translate_lang = '".$lang."'
				WHERE p.product_visible = 1 ".($dt > 0 ? " AND p.product_edited > '".date("Y-m-d H:i:s", $dt)."'" : "");
			$products = $modx->db->query($query);
			while ($product = $modx->db->getRow($products)) {
				$query = "
					SELECT 
						modx_id
					FROM `modx_a_pro2cat`
					WHERE product_id = '".$product['id']."' ";
				$categories = $modx->db->query($query);
				while($category_id = $modx->db->getRow($categories)){
					$product['category_id'][] = $category_id['modx_id']; 
				}
				$base['data']['products'][] = $product;
			}
			// categories
			$query = "
				SELECT 
					id,
					IF (parent = '".$modx->config['shop_catalog_root']."', 0, parent) AS 'primary_id',
					pagetitle_".$lang." AS 'name'
				FROM `modx_site_content`
				WHERE published = 1 AND deleted = 0 AND template = ".$modx->config['shop_tpl_category']." and id != '".$modx->config['shop_catalog_root']."'";

			$base['data']['categories'] = $modx->db->makeArray($modx->db->query($query));
			// delivery
			$query = "
				SELECT 
					delivery_id AS 'id',
					delivery_title AS 'name',
					delivery_cost AS 'cost'
				FROM `modx_a_delivery`";
			$base['data']['delivery_types'] = $modx->db->makeArray($modx->db->query($query));
			// filters
			$query = "
				SELECT 
					filter_id AS 'id',
					filter_name AS 'name',
					filter_type AS 'type'
				FROM `modx_a_filters`";
			$filters = $modx->db->query($query);
			while ($filter = $modx->db->getRow($filters)) {
				$query = "
					SELECT 
						modx_id
					FROM `modx_a_fc`
					WHERE filter_id = '".$filter['id']."' ";
				$categories = $modx->db->query($query);
				while($category_id = $modx->db->getRow($categories)){
					$filter['category_id'][] = $category_id['modx_id']; 
				}
				$base['data']['filters'][] = $filter;
			}
			// linking filters with category
			$query = "
				SELECT filter_id, product_id, value_id AS 'filter_value_id'
				FROM `modx_a_pro2val`";
			$base['data']['filter_link'] = $modx->db->makeArray($modx->db->query($query));

			$query = "
				SELECT 
					value_id AS 'filter_value_id',
					IF (val = '', num, val) AS 'filter_value',
					IF (val = '', 'int', 'str') AS 'filter_type'
				FROM `modx_a_values`";
			$base['data']['filter_values'] = $modx->db->makeArray($modx->db->query($query));
			// write base to json file
			$bd = fopen(MODX_BASE_PATH."/assets/files/".$file.".json", "w");
			fwrite($bd, json_encode($base));
			fclose($bd);
			$response = array("response_code" => 0, "data" => array("path" => "/assets/files/".$file.".json"));
			break;
		// banners
		case "banners":
			$banners = $modx->db->makeArray($modx->db->query("SELECT * FROM `modx_a_banners` WHERE banner_active = 1 ORDER BY banner_position ASC"));
			$param[] = "far=1";
			if ($request['width'] > 0) $param[] = "w=".$request['width'];
			if ($request['height'] > 0) $param[] = "h=".$request['height'];
			$params = implode("&", $param);
			foreach ($banners as $banner) {
				$ban[] = array(
					"image"       => rtrim($modx->config['site_url'], '/') . $modx->runSnippet("R", array("img" => "/assets/images/banners/" . $banner['banner_image'] , "opt" => $params)),
					"product_id"  => ($banner['banner_link_type'] == 1 ? $banner['banner_link_id'] : 0),
					"category_id" => ($banner['banner_link_type'] == 2 ? $banner['banner_link_id'] : 0)
					);
			}
			$response = array("response_code" => 0, "data" => array("banners" => $ban));
			break;
		// shop list
		case "shoplist":
			$shops = $modx->db->query("
				SELECT
				c.*, 
				(SELECT value FROM `modx_site_tmplvar_contentvalues` WHERE tmplvarid = 10 AND contentid = c.id) AS 'coords' 
				FROM `modx_site_content` c
				WHERE c.parent = 21 ");
			while ($shop = $modx->db->getRow($shops)) {
				$coord = explode(",", $shop['coords']);
				$res[] = Array(
						"name"      => $shop['pagetitle_'.$lang], 
						"address"   => $shop['content_'.$lang],
						"phone"     => $shop['longtitle_'.$lang],
						"latitude"  => (float)$coord[0],
						"longitude" => (float)$coord[1]
						);
			}
			$response = array("response_code" => 0, "data" => array("shoplist" => $res));
			break;
	}
if (!empty($error))
	$response = array("response_code" => 100, "data" => array("error" => $error));

header("Content-type: text/json");
echo json_encode($response);

die;