-- Adminer 4.2.4 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `banners`;
CREATE TABLE `banners` (
  `banner_id` int(11) NOT NULL AUTO_INCREMENT,
  `banner_active` tinyint(4) NOT NULL DEFAULT '1',
  `banner_image` varchar(64) NOT NULL,
  `banner_position` tinyint(4) NOT NULL,
  `banner_link_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 - product 2 category',
  `banner_link_id` int(11) NOT NULL,
  PRIMARY KEY (`banner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(128) NOT NULL,
  `category_active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


SET NAMES utf8;

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `key` varchar(32) NOT NULL,
  `value` text NOT NULL,
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `config` (`key`, `value`) VALUES
  ('BRAND',	'Foodelivery'),
  ('CURRENCY',	'€'),
  ('EMAIL_BODY_ORDER',	'<p>Hello, {user_firstname}!</p>\r\n<p>You create new order # {order_id}</p>\r\n<p>&nbsp;</p>\r\n<p>You ordered:</p>\r\n<p>{productlist}</p>\r\n<p>Delivery by {delivery_name} <strong>{delivery_cost}</strong></p>\r\n<p>Total <strong>{order_cost}</strong></p>'),
  ('EMAIL_BODY_ORDER_CHANGE',	'<p>Your order <strong>{order_id}</strong>&nbsp;changed status to&nbsp;<strong>{order_status}</strong></p>'),
  ('EMAIL_BODY_ORDER_ITEM',	'<ul>\r\n<li><img src=\"{product_image}\" alt=\"\" /> <br /> {product_name} <br /> {product_count} x {product_price} = <strong>{product_cost}</strong></li>\r\n</ul>'),
  ('EMAIL_BODY_RECOVERY',	'<p>You requested password recovery</p>\r\n<p>Your new password id&nbsp;<strong>{user_password}</strong></p>'),
  ('EMAIL_BODY_REG',	'<p>Hello {user_firstname}!</p>\r\n<p>&nbsp;</p>\r\n<p>You successfully registered on {brand}&nbsp;</p>\r\n<p>Your credentials is</p>\r\n<ul>\r\n<li>Login&nbsp;<strong>{user_email}</strong></li>\r\n<li>Password&nbsp;<strong>{user_password}</strong></li>\r\n</ul>\r\n<p>We are looking forward to your orders</p>'),
  ('EMAIL_SUBJECT_ORDER',	'New order on {brand}'),
  ('EMAIL_SUBJECT_ORDER_CHANGE',	'Your order {order_id} updated'),
  ('EMAIL_SUBJECT_RECOVERY',	'Password recovery for {brand}'),
  ('EMAIL_SUBJECT_REG',	'You registered on {brand}'),
  ('GMAP_KEY',	''),
  ('IMAGE_QUALITY_DEFAULT',	'85'),
  ('LANG',	'en'),
  ('LIMIT',	'20'),
  ('MAIL_HOST',	''),
  ('MAIL_PASS',	''),
  ('MAIL_PORT',	''),
  ('MAIL_SECURE',	''),
  ('MAIL_USER',	'');

DROP TABLE IF EXISTS `delivery`;
CREATE TABLE `delivery` (
  `delivery_id` int(11) NOT NULL AUTO_INCREMENT,
  `delivery_name` varchar(32) NOT NULL,
  `delivery_active` tinyint(4) NOT NULL DEFAULT '1',
  `delivery_cost` double(7,2) NOT NULL,
  PRIMARY KEY (`delivery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `filters`;
CREATE TABLE `filters` (
  `filter_id` int(11) NOT NULL AUTO_INCREMENT,
  `filter_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 Numeric 2 OR 3 AND',
  `filter_name` varchar(128) NOT NULL,
  `filter_description` text NOT NULL,
  PRIMARY KEY (`filter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `managers`;
CREATE TABLE `managers` (
  `manager_id` int(11) NOT NULL AUTO_INCREMENT,
  `manager_name` varchar(128) NOT NULL,
  `manager_email` varchar(128) NOT NULL,
  `manager_pass` varchar(32) NOT NULL,
  `manager_active` tinyint(4) NOT NULL DEFAULT '1',
  `shop_id` int(11) NOT NULL,
  PRIMARY KEY (`manager_id`),
  KEY `shop_id` (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_created` date NOT NULL,
  `order_updated` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `order_status` tinyint(4) NOT NULL DEFAULT '1',
  `order_status_pay` tinyint(4) NOT NULL DEFAULT '0',
  `order_client` int(11) NOT NULL,
  `order_manager` int(11) DEFAULT NULL,
  `order_delivery` text NOT NULL,
  `order_delivery_cost` int(11) NOT NULL,
  `order_cost` float(7,2) NOT NULL,
  `order_comment` text NOT NULL,
  PRIMARY KEY (`order_id`),
  KEY `order_client` (`order_client`),
  KEY `order_manager` (`order_manager`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`order_client`) REFERENCES `users` (`user_id`),
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`order_manager`) REFERENCES `managers` (`manager_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_created` datetime NOT NULL,
  `product_updated` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `product_code` varchar(32) NOT NULL,
  `product_visible` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0 no 1 yes',
  `product_available` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0 not available 1 available 2 new 3 hit 4 action',
  `product_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `product_name` varchar(512) NOT NULL,
  `product_price` float(7,2) NOT NULL,
  `product_intro` text NOT NULL,
  `product_description` mediumtext NOT NULL,
  `product_cover` varchar(32) NOT NULL,
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `product_code` (`product_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `shops`;
CREATE TABLE `shops` (
  `shop_id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_name` varchar(128) NOT NULL,
  `shop_addr` varchar(128) NOT NULL,
  `shop_active` tinyint(4) NOT NULL DEFAULT '1',
  `shop_lat` double NOT NULL,
  `shop_lng` double NOT NULL,
  `shop_phone` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `shops` (`shop_id`, `shop_name`, `shop_addr`, `shop_active`, `shop_lat`, `shop_lng`, `shop_phone`) VALUES
  (1,	'Default shop',	'221B Baker St, Marylebone, London',	1,	51.5237902,	-0.1606756,	'1234657890');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_reg_date` date NOT NULL,
  `user_last_visit` timestamp NOT NULL,
  `user_active` tinyint(4) NOT NULL DEFAULT '1',
  `user_email` varchar(128) NOT NULL,
  `user_firstname` varchar(32) NOT NULL,
  `user_lastname` varchar(32) NOT NULL,
  `user_phone` varchar(16) NOT NULL,
  `user_address` text NOT NULL,
  `user_pass` varchar(32) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `managers` (`manager_id`, `manager_name`, `manager_email`, `manager_pass`, `manager_active`, `shop_id`) VALUES
  (1,	'Default admin account',	'hero@foodeliveryapp.com',	'c63da8d818bf2bc9e7becb07f2eb4b6a',	1,	1);

DROP TABLE IF EXISTS `lnk_filters_categories`;
CREATE TABLE `lnk_filters_categories` (
  `filter_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  KEY `filter_id` (`filter_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `lnk_filters_categories_ibfk_1` FOREIGN KEY (`filter_id`) REFERENCES `filters` (`filter_id`) ON DELETE CASCADE,
  CONSTRAINT `lnk_filters_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `lnk_order_products`;
CREATE TABLE `lnk_order_products` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_count` int(11) NOT NULL,
  `product_price` double(7,2) NOT NULL,
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `lnk_order_products_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `lnk_order_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `lnk_products_categories`;
CREATE TABLE `lnk_products_categories` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  KEY `product_id` (`product_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `lnk_products_categories_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `lnk_products_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `lnk_products_values`;
CREATE TABLE `lnk_products_values` (
  `product_id` int(11) NOT NULL,
  `filter_id` int(11) NOT NULL,
  `value_id` int(11) NOT NULL,
  KEY `product_id` (`product_id`),
  KEY `filter_id` (`filter_id`),
  KEY `value_id` (`value_id`),
  CONSTRAINT `lnk_products_values_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `lnk_products_values_ibfk_2` FOREIGN KEY (`filter_id`) REFERENCES `filters` (`filter_id`) ON DELETE CASCADE,
  CONSTRAINT `lnk_products_values_ibfk_3` FOREIGN KEY (`value_id`) REFERENCES `values` (`value_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `values`;
CREATE TABLE `values` (
  `value_id` int(11) NOT NULL AUTO_INCREMENT,
  `string` varchar(128) DEFAULT NULL,
  `number` int(11) NOT NULL,
  PRIMARY KEY (`value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2016-02-18 17:26:08
