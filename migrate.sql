#Migration tool
CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(128) NOT NULL,
  `category_active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_created` datetime NOT NULL,
  `product_updated` timestamp NOT NULL,
  `product_code` varchar(32) NOT NULL,
  `product_visible` tinyint(4) NOT NULL DEFAULT '1',
  `product_available` tinyint(4) NOT NULL DEFAULT '1',
  `product_name` varchar(512) NOT NULL,
  `product_price` float(7,2) NOT NULL,
  `product_intro` text NOT NULL,
  `product_description` mediumtext NOT NULL,
  `product_cover` varchar(32) NOT NULL,
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `product_code` (`product_code`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `lnk_products_categories` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  KEY `product_id` (`product_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `lnk_products_categories_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `lnk_products_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `shops` (
  `shop_id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_name` varchar(128) NOT NULL,
  `shop_addr` varchar(128) NOT NULL,
  `shop_active` tinyint(4) NOT NULL DEFAULT '1',
  `shop_lat` double NOT NULL,
  `shop_lng` double NOT NULL,
  PRIMARY KEY (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `managers` (
  `manager_id` int(11) NOT NULL AUTO_INCREMENT,
  `manager_name` varchar(128) NOT NULL,
  `manager_email` varchar(128) NOT NULL,
  `manager_pass` varchar(32) NOT NULL,
  `manager_active` tinyint(4) NOT NULL DEFAULT '1',
  `shop_id` int(11) NOT NULL,
  PRIMARY KEY (`manager_id`),
  KEY `shop_id` (`shop_id`),
  CONSTRAINT `managers_ibfk_1` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `filters` (
  `filter_id` int(11) NOT NULL AUTO_INCREMENT,
  `filter_type` tinyint(4) NOT NULL,
  `filter_name` varchar(64) NOT NULL,
  `filter_description` text NOT NULL,
  PRIMARY KEY (`filter_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

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

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_created` date NOT NULL,
  `order_updated` timestamp NOT NULL,
  `order_status` tinyint(4) NOT NULL DEFAULT '1',
  `order_status_pay` tinyint(4) NOT NULL DEFAULT '0',
  `order_client` int(11) NOT NULL,
  `order_manager` int(11) NOT NULL,
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

CREATE TABLE `lnk_filters_categories` (
  `filter_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  KEY `filter_id` (`filter_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `lnk_filters_categories_ibfk_1` FOREIGN KEY (`filter_id`) REFERENCES `filters` (`filter_id`) ON DELETE CASCADE,
  CONSTRAINT `lnk_filters_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#products
INSERT INTO products (product_id, product_created, product_code, product_visible,
product_available, product_name, product_price, product_intro, product_description, product_cover)
SELECT p.product_id, p.product_created, p.product_code, p.product_visible, p.product_available,
s.product_name, p.product_price, s.product_introtext, s.product_description, p.product_cover 
FROM `modx_a_products` p
JOIN `modx_a_product_strings` s ON p.product_id = s.product_id AND s.translate_lang = 'en';

#categories
INSERT INTO `categories` (category_id, category_name, category_active)
SELECT id as 'category_id', pagetitle_en as 'category_name', published as 'category_active'
FROM `modx_site_content`
WHERE template = (SELECT setting_value FROM `modx_system_settings` WHERE setting_name = 'shop_tpl_category');

#filters
INSERT INTO `filters` (filter_id, filter_type, filter_name, filter_description)
SELECT filter_id, filter_type, filter_name, filter_desc FROM `modx_a_filters`;

#products2categories
INSERT INTO `lnk_products_categories` (product_id, category_id)
SELECT product_id, modx_id FROM `modx_a_pro2cat`;

#shops
INSERT INTO `shops` (shop_id, shop_name, shop_addr, shop_active, shop_lat, shop_lng)
VALUES (NULL, 'Default shop', '221B Baker St, Marylebone, London', 1, 51.5237902,-0.1606756);

#managers
INSERT INTO `managers` (manager_id, manager_name, manager_email, manager_pass, shop_id)
SELECT u.id, ua.fullname, ua.email, replace(u.password, 'uncrypt>', '') as 'manager_pass', 1 
FROM `modx_manager_users` u
JOIN `modx_user_attributes` ua ON ua.internalKey = u.id;

#users
INSERT INTO `users` (user_id, user_reg_date, user_last_visit, user_active, user_email, user_firstname, user_lastname, user_phone, user_address, user_pass)
SELECT 
	u.id, NOW(), FROM_UNIXTIME(ua.lastlogin), IF (ua.blocked, 0, 1) as 'user_active', ua.email, ua.fullname as 'user_firstname', ua.surname as 'lastname', ua.phone, addr as 'user_address', u.password as 'user_pass'
FROM `modx_web_users` u
JOIN `modx_web_user_attributes` ua ON ua.internalKey = u.id;




#orders
INSERT INTO `orders` (order_id, order_created, order_status, order_status_pay, order_client, order_manager, order_delivery, order_delivery_cost, order_cost, order_comment)
SELECT order_id, order_created, order_status, order_status_pay, order_client, order_manager, order_delivery, order_delivery_cost, order_cost, order_comment
FROM `modx_a_order`;