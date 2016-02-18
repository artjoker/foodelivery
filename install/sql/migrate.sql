#Migration tool

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