<?php
// general
	define('L_EMAIL', 'Email');
	define('L_DOB', 'Дата рождения');
	define('L_DATE', 'Дата');
	define('L_COUNTRY', 'Страна');
	define('L_CITY', 'Город');
	define('L_SEX', 'Пол');
	define('L_NOT_SELECTED', 'Не выбрано');
	define('L_AGE', 'Возраст');
	define('L_TYPE', 'Тип');
	define('L_STATUS', 'Статус');
	define("L_TEXT", "Текст");
	define("L_IMAGE", "Изображение");
	define("L_VIDEO", "Видео");
	define("L_SETTING", "Конфигурации");
	define("L_ERROR", "Ошибка");
	define("L_PASSWORD", "Пароль");
	define("L_LOGIN", "Войти");
	define("L_SAVE", "Сохранить");
	define("L_LANG", "Язык");
	define("L_ADMIN", "Администратор");
	define("L_MORE", "Загрузить еще");
	define("L_EDIT", "Редактировать");
	define("L_BAN", "Забанить");
	define("L_UNBAN", "Разбанить");
	define("L_DELETE", "Удалить");
	define("L_AVATAR", "Аватарка");
	define("L_CLOSE", "Закрыть");
	define("L_TO", "До");
	define("L_COUNT", "Количество");
	define("L_REPOST", "Репост");

//Аппеляции
	define("L_APPEALS_JUST", " подал жалобу");
	define("L_APPEALS_MANY", "Много жалоб");
	define("L_APPEALS_FROM_POST", "на пост ");
	define("L_APPEALS_USER", "Пользователь подал аппеляцию");
// main menu
	define('L_USERS', 'Пользователи');
	define('L_PUBS', 'Публикации');
	define('L_VIP_PUBS', 'Vip Публикации');
	define('L_AUCTION', 'Аукцион');
	define('L_CATEGORIES', 'Категории');
	define('L_CATEGORY', 'Категория');
	define('L_STAT', 'Статистика');
	define('L_ENERGY', 'Энергия');
	define('L_QUIT', 'Выход');
// users/index
	define('L_USER_NEW', 'Новый пользователь создан');
	define('L_USER', 'Пользователь');
	define('L_USER_ADD', 'Добавить пользователя');
	// search form
	define('L_SEARCH', 'Поиск');
	define('L_RESET', 'Сбросить');
	define('L_SEARCH_PERSONAL', 'По персональным данным');
	define('L_SEARCH_NAME', 'По нику, еmail');
	define('L_SEARCH_COUNTRY', 'По стране');
	define('L_SEARCH_CITY', 'По городу');
	define('L_SEARCH_BTN', 'Искать');
	// table
	define('L_CAPTION_ID', 'ID');
	define('L_CAPTION_NICK', 'Ник');
	define('L_CAPTION_POSTS', 'Посты');
	define('L_CAPTION_COMMENTS', 'Комментарии');
	define('L_CAPTION_COM', 'Ком.');

	// users/edit
	define('L_FORM_USER_CAPTION', 'Данные о пользователе');
	define('L_FORM_REGISTERED', 'Зарегистрирован');
	define('L_FORM_PROMOCODE', 'Промокод');
	define('L_INVITE', 'Приглашен');
	define('L_FORM_BAN', 'Бан');
	define('L_BAN_REASON', 'Причина');
	define('L_BAN_EXPIRE', 'Забанить до');
	define('L_BANNED', 'Забанен');
	define('L_USER_FORM_SUCCESS', 'Данные пользователя успешно обновлены');
	define('L_USER_BAN', 'Пользователь забанен');
	define('L_USER_UNBAN', 'Пользователь разбанен');
	define("L_USER_ROLE", "Роль пользователя");
	define("L_USER_POSTS", "Посты пользователя");
	// users/delete
	define('L_USER_DELETE', 'Пользователь удален');
	define('L_USER_IF_DELETE', 'Вы уверены что хотите удалить пользователя?\nУдалятся все его посты и комментарии');
	//ban/unban
	define("L_USER_IF_BAN", "Вы уверены что хотите забанить пользователя?");
	define("L_USER_IF_UNBAN", "Вы уверены что хотите разбанить пользователя?");
	//subscriber
	define("L_USER_SUB", "Подписка");
	define("L_USER_SUBSCRIBER", "Подписчики");
	define("L_USER_SUBS_AUTHOR", "Пользователь подписан на");
	//appeals
	define("L_USER_APPEALS", "Апеляции");

//setting
	//save/update
	define('L_SETTING_SAVE', "Настройки успешно сохранены");
	//basic
	define('L_SETTING_NAME_PRODJECT', "Название проекта");
	define("L_SETTING_BASIC", "Основные настройки");
	//SMTP
	define("L_SETTING_SMTP","SMTP");
	define("L_SETTING_HOST", "Хост");
	define("L_SETTING_PORT", "Порт");
	//energy
	define("L_SETTING_ENERGY_REG", "Кол-во энергии при регистрации");
	define("L_SETTING_ENERGY_PROMO", "Кол-во энергии при применении промокода");
	define("L_SETTING_ENERGY_PUB", "Создание публикации(снятие энергии)");
	define("L_SETTING_ENERGY_CR_PUB_URL", 'Создание публикации "URL"');
	define("L_SETTING_ENERGY_CR_PUB_IMAGE", 'Создание публикации "изображение"');
	define("L_SETTING_ENERGY_CR_PUB_VIDEO", 'Создание публикации "видео"');
	define("L_SETTING_ENERGY_PUB_VIEW", "Начисление энергии при просмотре публикации");
	define("L_SETTING_ENERGY_PUB_VIEW_VIP", "Начисление энергии при просмотре публикации(VIP)");
	define("L_SETTING_ENERGY_PUB_URL", 'Публикации "URL"');
	define("L_SETTING_ENERGY_PUB_IMAGE", 'Публикации "изображение"');
	define("L_SETTING_ENERGY_PUB_VIDEO", 'Публикации "видео"');
	//add setting
	define("L_SETTING_APP", "Дополнительные настройки");
	define("L_SETTING_MAX_SUBS", "Максимальное кол-во подписок");
	//ban
	define("L_SETTING_BAN", "Информация о банах");
	define("L_SETTING_COUNT_BAN", "Кол-во жалоб, после которых банить пользователя");
	define("L_SETTING_TIME_BAN", "Время на которое забанить пользователя(в часах)");
	//API
	define('L_SETTING_KEY_FOR_PAYMENT', 'Ключи для оплат через');

//auth
	define("L_AUTH_USER_NOT_FOUND", "Пользователь не найден");
	define("L_AUTH_USER_PASS_ERROR", "Не правильный пароль");
//Восстановление пароля
	define("L_FORGOT_PASS", "Забыли пароль?");
	define("L_RESTORE_PASS", "Восстановить пароль");
	define("L_PASS_TO_EMAIL", "Новый пароль выслан на email");
//category
	define("L_CAT_UPDATE", "Категория обновлена");
	define("L_CAT_INSERT", "Добавлена новая категорияи");
	define("L_CAT_DELETE", "Категория удалена");
	define("L_CAT_ADD", "Добавить категорию");
	define("L_CAT_POSTS", "Постов");
	define("L_CAT_REPOSTS", "Репостов");
	define("L_CAT_COMMENTS", "Комментариев");
	define("L_CAT_VIEWS", "Просмотров");

//posts
	define("L_POST", "Публикация");
	define("L_POST_AU_FOLLOWERS", "Аудитория");
	define("L_POST_AU_SUPER", "Суперпост");
	define("L_POST_CONTENT", "Содержание");
	define("L_POST_SETTING", "Настройки поста");
	define("L_POST_DELETE", "Удален");
	define("L_POST_AVAILABLE", "Доступен");
	define("L_POST_MODER", "Модерация");
	//add/edit
	define("L_POST_ADD", "Добавить публикацию");
	define("L_POST_EDIT", "Редактировать публикацию");
	define("L_POST_PUB_START", "Начало публикации");
	define("L_POST_PUB_STOP", "Конец публикации");
	define("L_POST_SAVE", "Добавлена новая публикация");
	define("L_POST_UPDATE", "Публикация обновлена");
	define("L_POST_ONLY_VIDEO", "Только видео в формате mp4");
//Biling
	define("L_BILING_PLUS_ADMIN", "Начисление энергии через админ панель");
	define("L_BILING_MINUS_ADMIN", "Снятие энергии через админ панель");
	define("L_BILING_REG", "Начисление энергии при регистрации");
	define("L_BILING_PROMOCODE", "Начисление энергии от промокода при регистрации");
	define("L_BILING_NEW_POST", "Создание публикации");
	define("L_BILING_VIEW_POST", "Просмотр публикации");
	define("L_BILING_BUY", "Покупка энергии");

//PUSH
	define("P_NEW_COMMENT", "Новый комментарий");
	define("P_NEW_COMMENT_DESC_BEFORE", "У Вашего поста появилось ");
	define("P_NEW_COMMENT_DESC_AFTER", " новых комментариев");
	define("P_NEW_REPOST", "Новый репост");
	define("P_NEW_REPOST_DESC", "Вашей публикации сделали репост");
	define("P_NEW_SUBS", "Новый подписчик");
	define("P_NEW_SUBS_DESC", "На Ваши подписался новй подписчик");

//Statistic
	define("L_STAT_COUNT_CREATED_PUB", "Кол-во созданных публикаций");
	define("L_STAT_TYPE_CREATED_PUB", "Типы созданных постов");
	define("L_STAT_COUNT_COMM", "Кол-во комментариев");
	define("L_STAT_COUNT_REPOST", "Кол-во репостов");
	define("L_STAT_AGE", "Статистика пользователей по возрасту");
	define("L_STAT_REG_PERIOD", "Кол-во регистраций за период");
	define("L_STAT_USERS_COUNTRY", "Статистика пользователей по странам");
	define("L_STAT_USERS_GENDER", "Статистика пользователей по полу");
	define("L_STAT_ACCUMULATION", "Накопление");
	define("L_STAT_COSTS", "Расходы");
	define("L_STAT_ACC", "Накопление и расход энергии");
	define("L_STAT_BUY_ENERGY", "Куплено энергии");
	define("L_STAT_ACTIVITY", "Кол-во заходов");