<?php
if(!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') die("No access");
define("ROOT", dirname(__FILE__));
define("TPLS", dirname(__FILE__)."/tpl/settings/");
$languages 			= explode(",", $modx->config['lang_list']);
$bootstrap      = isset($_GET['b']) ? $_GET['b'] : "";
$controller     = isset($_GET['c']) ? $_GET['c'] : "";
$res            = Array();
$res['version'] = "v 1.0";
$res['url']     = $url = "index.php?a=250&";
$search         = true;
$messages       = Array(
	"currency_updated"  => $_lang["shop_currency_updated"],
	"templates_updated" => $_lang["shop_templates_updated"],
	"payments_updated"  => $_lang["shop_payments_updated"],
	"imex_updated"		=> $_lang["shop_imex_updated"]
	);

include MODX_BASE_PATH . "/assets/shop/shop.class.php";
$shop = new Shop($modx);

switch ($bootstrap) {
	case 'delivery':
		if (count($_POST) > 0) {
			$modx->db->query("truncate table `modx_a_delivery`");
			foreach ($_POST['delivery']['title'] as $key => $value) 
				if ($value != '')
					$modx->db->query("insert into `modx_a_delivery` set delivery_title = '".$modx->db->escape($value)."', delivery_cost = '".$modx->db->escape($_POST['delivery']['cost'][$key])."'".($_POST['default'] == $key ? ", delivery_default = 1" : ""));
		}
		$delivery = $modx->db->makeArray($modx->db->query("select * from `modx_a_delivery` order by delivery_title asc"));
		$tpl = "delivery.tpl";
		break;
	case 'currency':
		if (count($_POST) > 0) {
			foreach ($_POST['name'] as $key => $value)
				if ($value == "") {
					unset($_POST['name'][$key]);
					unset($_POST['sign'][$key]);
					unset($_POST['rate'][$key]);
					unset($_POST['active'][$key]);
				}
			$modx->db->query("replace into `modx_system_settings` (setting_value, setting_name) values  ('".implode("|", $_POST['name'])."', 'shop_curr_name')");
			$modx->db->query("replace into `modx_system_settings` (setting_value, setting_name) values  ('".implode("|", $_POST['code'])."', 'shop_curr_code')");
			$modx->db->query("replace into `modx_system_settings` (setting_value, setting_name) values  ('".implode("|", $_POST['sign'])."', 'shop_curr_sign')");
			$modx->db->query("replace into `modx_system_settings` (setting_value, setting_name) values  ('".implode("|", $_POST['rate'])."', 'shop_curr_rate')");
			$modx->db->query("replace into `modx_system_settings` (setting_value, setting_name) values  ('".implode("|", $_POST['active'])."', 'shop_curr_active')");
			$modx->db->query("replace into `modx_system_settings` (setting_value, setting_name) values  ('".$_POST['sign'][array_search(1, $_POST['active'])]."', 'shop_curr_default_sign')");
			$modx->db->query("replace into `modx_system_settings` (setting_value, setting_name) values  ('".$_POST['code'][array_search(1, $_POST['active'])]."', 'shop_curr_default_code')");
	    $shop->clearCache();
			die(header("Location: index.php?a=250&b=currency&w=currency_updated"));
		}
		$names = explode("|", $modx->config['shop_curr_name']);
		$codes = explode("|", $modx->config['shop_curr_code']);
		$rates = explode("|", $modx->config['shop_curr_rate']);
		$signs = explode("|", $modx->config['shop_curr_sign']);
		$activ = explode("|", $modx->config['shop_curr_active']);
		$tpl = "currency.tpl";
		break;
	case "imex":
		if (count($_POST) > 0) {
		    foreach($_POST as $key => $value)
				$modx->db->query("replace into `modx_system_settings` (setting_value, setting_name) values  ('".$modx->db->escape($value)."', '".$key."')");
			$shop->clearCache();
			die(header("Location: index.php?a=250&b=imex&w=imex_updated"));
		}
		$langs = explode(',', $modx->config["lang_list"]);
		$tpl = "imex.tpl";
		break;
	case "mail":
		switch ($_GET['tpl']) {
			default:
				$template = $shop->getMailTemplate($_GET['tpl']);
			case "new":
				$tpl = "mail_edit.tpl";
				break;
			case "":
				if (count($_POST) > 0) {
					foreach ($_POST['mail']['tpl'] as $lang => $tpl)
						$modx->db->query("REPLACE INTO `modx_a_mail_templates` SET 
							mail_name     = '".$modx->db->escape($_POST['mail']['name'])."',
							mail_title    = '".$modx->db->escape($_POST['mail']['title'])."',
							mail_lang     = '".$lang."',
							mail_subject  = '".$modx->db->escape($_POST['mail']['subject'][$lang])."',
							mail_template = '".$modx->db->escape($tpl)."'
							");
					$shop->go($url."b=mail&w=templates_updated");
				}
				$mails = $shop->getMailTemplates();
				$tpl = "mail.tpl";
				break;
		}
		break;
	default:
		if (count($_POST) > 0) {
	    foreach($_POST as $key => $value)
				$modx->db->query("replace into `modx_system_settings` (setting_value, setting_name) values  ('".$modx->db->escape($value)."', '".$key."')");
			$shop->clearCache();
			die(header("Location: index.php?a=250"));
	    }
		$tpl = "main.tpl";
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