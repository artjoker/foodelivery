<?php
// general
	define('L_EMAIL', 'Email');
	define('L_DOB', 'Date of Birth');
	define('L_DATE', 'Date');
	define('L_COUNTRY', 'Country');
	define('L_CITY', 'City');
	define('L_SEX', 'Gender');
	define('L_NOT_SELECTED', 'Not selected');
	define('L_AGE', 'Age');
	define('L_TYPE', 'Type');
	define('L_STATUS', 'Status');
	define("L_TEXT", "Text");
	define("L_IMAGE", "Image");
	define("L_VIDEO", "Video");
	define("L_SETTING", "Setting");
	define("L_ERROR", "Error");
	define("L_PASSWORD", "Password");
	define("L_LOGIN", "Login");
	define("L_SAVE", "Save");
	define("L_LANG", "Language");
	define("L_ADMIN", "Administrator");
	define("L_MORE", "Load more");
	define("L_EDIT", "Edit");
	define("L_BAN", "Ban");
	define("L_UNBAN", "Unban");
	define("L_DELETE", "Delete");
	define("L_AVATAR", "Avatar");
	define("L_CLOSE", "Close");
	define("L_TO", "to");
	define("L_COUNT", "Count");
	define("L_REPOST", "Repost");

//Аппеляции
	define("L_APPEALS_JUST", " complained");
	define("L_APPEALS_MANY", "Many complaints");
	define("L_APPEALS_FROM_POST", " the post ");
	define("L_APPEALS_USER", "User appealed");
// main menu
	define('L_USERS', 'Users');
	define('L_PUBS', 'Publications');
	define('L_VIP_PUBS', 'Vip publications');
	define('L_AUCTION', 'Auction');
	define('L_CATEGORIES', 'Categories');
	define('L_CATEGORY', 'Category');
	define('L_STAT', 'Statistic');
	define('L_ENERGY', 'Energy');
	define('L_QUIT', 'Exit');
// users/index
	define('L_USER_NEW', 'New user created');
	define('L_USER', 'User');
	define('L_USER_ADD', 'Add User');
	// search form
	define('L_SEARCH', 'Search');
	define('L_RESET', 'Clear');
	define('L_SEARCH_PERSONAL', 'As personal data');
	define('L_SEARCH_NAME', 'Search nick, mail');
	define('L_SEARCH_COUNTRY', 'Search country');
	define('L_SEARCH_CITY', 'Search city');
	define('L_SEARCH_BTN', 'search');
	// table
	define('L_CAPTION_ID', 'ID');
	define('L_CAPTION_NICK', 'Nick');
	define('L_CAPTION_POSTS', 'Publications');
	define('L_CAPTION_COMMENTS', 'Comments');
	define('L_CAPTION_COM', 'Com.');

	// users/edit
	define('L_FORM_USER_CAPTION', 'Information about the user');
	define('L_FORM_REGISTERED', 'Registered');
	define('L_FORM_PROMOCODE', 'Promocode');
	define('L_INVITE', 'Invited');
	define('L_FORM_BAN', 'Ban');
	define('L_BAN_REASON', 'Reason');
	define('L_BAN_EXPIRE', 'Ban to');
	define('L_BANNED', 'Banned');
	define('L_USER_FORM_SUCCESS', 'User data updated successfully');
	define('L_USER_BAN', 'User is banned');
	define('L_USER_UNBAN', 'User is unbanned');
	define("L_USER_ROLE", "user Role");
	define("L_USER_POSTS", "Content");
	// users/delete
	define('L_USER_DELETE', 'User removed');
	define('L_USER_IF_DELETE', 'Are you sure you want to remove? Delete all his posts and comments');
	//ban/unban
	define("L_USER_IF_BAN", "Are you sure you want to ban a user?");
	define("L_USER_IF_UNBAN", "Are you sure you want to unban a user?");
	//subscriber
	define("L_USER_SUB", "Offering");
	define("L_USER_SUBSCRIBER", "Followers");
	define("L_USER_SUBS_AUTHOR", "To subscribe to");
	//appeals
	define("L_USER_APPEALS", "Appeals");

//setting
	//save/update
	define('L_SETTING_SAVE', "Settings saved successfully");
	//basic
	define('L_SETTING_NAME_PRODJECT', "Project name");
	define("L_SETTING_BASIC", "Basic settings");
	//SMTP
	define("L_SETTING_SMTP","SMTP");
	define("L_SETTING_HOST", "Host");
	define("L_SETTING_PORT", "Port");
	//energy
	define("L_SETTING_ENERGY_REG", "Energy during registration");
	define("L_SETTING_ENERGY_PROMO", "Energy when applying promotional code");
	define("L_SETTING_ENERGY_PUB", "Create a post (removal of power)");
	define("L_SETTING_ENERGY_CR_PUB_URL", 'Create publication "URL"');
	define("L_SETTING_ENERGY_CR_PUB_IMAGE", 'Create publication "Image"');
	define("L_SETTING_ENERGY_CR_PUB_VIDEO", 'Create publication "Video"');
	define("L_SETTING_ENERGY_PUB_VIEW", "Earning energy when viewing the publication");
	define("L_SETTING_ENERGY_PUB_VIEW_VIP", "Earning energy when viewing the publication (VIP)");
	define("L_SETTING_ENERGY_PUB_URL", 'Publications "URL"');
	define("L_SETTING_ENERGY_PUB_IMAGE", 'Publications "Image"');
	define("L_SETTING_ENERGY_PUB_VIDEO", 'Publications "Video"');
	//add setting
	define("L_SETTING_APP", "Additional settings");
	define("L_SETTING_MAX_SUBS", "Maximum number of subscriptions");
	//ban
	define("L_SETTING_BAN", "Information about Banned");
	define("L_SETTING_COUNT_BAN", "Number of complaints, after which the banned user");
	define("L_SETTING_TIME_BAN", "Time is ban the user (in hours)");
	//APi
	define('L_SETTING_KEY_FOR_PAYMENT', 'Keys for Payment through');

//auth
	define("L_AUTH_USER_NOT_FOUND", "User not found");
	define("L_AUTH_USER_PASS_ERROR", "Wrong password");
//Восстановление пароля
	define("L_FORGOT_PASS", "Forgot your password?");
	define("L_RESTORE_PASS", "Restore password");
	define("L_PASS_TO_EMAIL", "New password sent to e mail");
//category
	define("L_CAT_UPDATE", "Category updated");
	define("L_CAT_INSERT", "Added a new category");
	define("L_CAT_DELETE", "Categories Clear");
	define("L_CAT_ADD", "Add category");
	define("L_CAT_POSTS", "Publications");
	define("L_CAT_REPOSTS", "Repost");
	define("L_CAT_COMMENTS", "Comments");
	define("L_CAT_VIEWS", "Views");

//posts
	define("L_POST", "Published");
	define("L_POST_AU_FOLLOWERS", "Followers");
	define("L_POST_AU_SUPER", "Vip post");
	define("L_POST_CONTENT", "Content");
	define("L_POST_SETTING", "Setting post");
	define("L_POST_DELETE", "Removed");
	define("L_POST_AVAILABLE", "Available");
	define("L_POST_MODER", "Moderation");
	//add/edit
	define("L_POST_ADD", "Add publication");
	define("L_POST_EDIT", "Edit the publication");
	define("L_POST_PUB_START", "Start pub");
	define("L_POST_PUB_STOP", "End pub");
	define("L_POST_SAVE", "A new publication");
	define("L_POST_UPDATE", "Publication updated");
	define("L_POST_ONLY_VIDEO", "Only videos in mp4 format");
//Biling
	define("L_BILING_PLUS_ADMIN", "Calculation of energy through the admin panel");
	define("L_BILING_MINUS_ADMIN", "Removal of energy through the admin panel");
	define("L_BILING_REG", "Earning energy at registration");
	define("L_BILING_PROMOCODE", "Calculation of energy from the promo code when registering");
	define("L_BILING_NEW_POST", "Create a post");
	define("L_BILING_VIEW_POST", "View the publication");
	define("L_BILING_BUY", "Buy energy");
//PUSH
	define("P_NEW_COMMENT", "New comment");
	define("P_NEW_COMMENT_DESC_BEFORE", "In your post appeared ");
	define("P_NEW_COMMENT_DESC_AFTER", " new comments");
	define("P_NEW_REPOST", "New repost");
	define("P_NEW_REPOST_DESC", "Your publication did repost");
	define("P_NEW_SUBS", "New subscriber");
	define("P_NEW_SUBS_DESC", "On your signed a new subscriber");

//Statistic
	define("L_STAT_COUNT_CREATED_PUB", "Number of created publications");
	define("L_STAT_TYPE_CREATED_PUB", "Types of posts created");
	define("L_STAT_COUNT_COMM", "Number of comments");
	define("L_STAT_COUNT_REPOST", "Number of reposts");
	define("L_STAT_AGE", "User statistics for age");
	define("L_STAT_REG_PERIOD", "Number of registrations over the period");
	define("L_STAT_USERS_COUNTRY", "User Statistics Country");
	define("L_STAT_USERS_GENDER", "User statistics by sex");
	define("L_STAT_ACCUMULATION", "Accumulation");
	define("L_STAT_COSTS", "COSTS");
	define("L_STAT_ACC", "Accumulation and energy consumption");
	define("L_STAT_BUY_ENERGY", "Buy energy");
	define("L_STAT_ACTIVITY", "Number of visits");
