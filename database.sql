-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(128) NOT NULL,
  `catagory_active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `filters`;
CREATE TABLE `filters` (
  `filter_id` int(11) NOT NULL AUTO_INCREMENT,
  `filter_type` tinyint(4) NOT NULL,
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
  KEY `shop_id` (`shop_id`),
  CONSTRAINT `managers_ibfk_1` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_created` date NOT NULL,
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


DROP TABLE IF EXISTS `products`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `shops`;
CREATE TABLE `shops` (
  `shop_id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_name` varchar(128) NOT NULL,
  `shop_addr` varchar(128) NOT NULL,
  `shop_active` tinyint(4) NOT NULL DEFAULT '1',
  `shop_lat` double NOT NULL,
  `shop_lng` double NOT NULL,
  PRIMARY KEY (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


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


DROP TABLE IF EXISTS `values`;
CREATE TABLE `values` (
  `value_id` int(11) NOT NULL AUTO_INCREMENT,
  `string` varchar(128) NOT NULL,
  `number` int(11) NOT NULL,
  PRIMARY KEY (`value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `lnk_filters_categories`;
CREATE TABLE `lnk_filters_categories` (
  `filter_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  KEY `filter_id` (`filter_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `lnk_filters_categories_ibfk_1` FOREIGN KEY (`filter_id`) REFERENCES `filters` (`filter_id`) ON DELETE CASCADE,
  CONSTRAINT `lnk_filters_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE
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


-- 2016-02-07 16:25:47