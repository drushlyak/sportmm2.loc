<?php
	require_once ("db.connect.cfg.php");

	// ===
	define('DB_HOSTNAME',					CFG_DB_HOSTNAME);
	define('DB_USERNAME',					CFG_DB_USERNAME);
	define('DB_PASSWORD',					CFG_DB_PASSWORD);
	define('DB_DATABASE',					CFG_DB_DATABASE);
	define('DB_CHARACTER',					'utf8');

	// ========= Системные таблицы
	define('CFG_DBTBL_CONFIG',				"config");
	define('CFG_DBTBL_NAVIGATION',			"navigation");
	define('CFG_DBTBL_LANGUAGE',			"language");
	define('CFG_DBTBL_MODULE',				"module");
	define('CFG_DBTBL_CIRCUIT',				"circuit");
	define('CFG_DBTBL_BACKUP',				"backup_db");

	// ========= ACL
	define('CFG_DBTBL_UDATA',				"acl_user_data");
	define('CFG_DBTBL_ACL_MOD_PRIV',		"acl_module_privilege");
	define('CFG_DBTBL_DSP_ACL',				"acl_dsp_files");
	define('CFG_DBTBL_ACL_RESOURCE',		"acl_resource");
	define('CFG_DBTBL_ACL_RESEQ',			"acl_resource_seq");

	// ================= Структура
	// Структура сайта
	define('CFG_DBTBL_PAGE',				"map_data");
	define('CFG_DBTBL_PAGETREE',			"map_struct");

	// Меню
	define('CFG_DBTBL_MENU',				"menu_data");
	define('CFG_DBTBL_MENUTREE',			"menu_struct");

	// Шаблоны
	define('CFG_DBTBL_TE_CONTDATA',			"te_contaner_data");
	define('CFG_DBTBL_TE_CONTTREE',			"te_contaner_struct");
	define('CFG_DBTBL_TE_EXEC',				"te_executor");
	define('CFG_DBTBL_TE_EXECWCODE',		"te_executor_wysiwyg_code");
	define('CFG_DBTBL_TE_EXECSCODE',		"te_executor_simple_code");
	define('CFG_DBTBL_TE_SELECTIVE_TMPL',	"te_selective_tmpl");
	define('CFG_DBTBL_TE_VALUE',			"te_value");

	define('CFG_DBTBL_TABLES',				"const_tables");
	define('CFG_DBTBL_TE_INCLUDES',			"te_includes");
	define('CFG_DBTBL_TE_TYPE',				"te_types");
	define('CFG_DBTBL_TE_TYPE_ARRAY',		"te_types_array");
	define('CFG_DBTBL_ATTR',				"page_attr");
	define('CFG_DBTBL_PAGELNG',				"page_lang");

	//Справочник
	define ('CFG_DBTBL_DICT_STATE_ORDER',		"dict_state_order");
	define ('CFG_DBTBL_DICT_LANGUAGE',		"dict_language");
	define ('CFG_DBTBL_DICT_SOURCE_ORDERS',		"dict_source_order");
	define ('CFG_DBTBL_DICT_DECLINED_REASON', "dict_declined_reason");
	define ('CFG_DBTBL_DICT_TYPE_DELIVERY',		"dict_type_delivery");
	define ('CFG_DBTBL_DICT_TYPE_PAYMENT',		"dict_type_payment");
	define ('CFG_DBTBL_DICT_MAIN_CATEGORY',		"dict_main_category");
	define ('CFG_DBTBL_DICT_CATEGORY',		"dict_category");
	define ('CFG_DBTBL_DICT_COLLECTIONS',		"dict_collections");
	


	// ================= Модули
	define ('CFG_DBTBL_DEPENDS',			"module_depend");
	define ('CFG_DBTBL_MOD_PRODUCT',		"mod_product");
	define ('CFG_DBTBL_MOD_CATEGORY_PRODUCT',		"mod_category_product");
	define ('CFG_DBTBL_MOD_COLLECTION_PRODUCT',		"mod_collection_product");
	define ('CFG_DBTBL_MOD_TYPE_VIEW_PRODUCT',		"mod_type_view_product");
	define ('CFG_DBTBL_MOD_PRODUCT_PHOTO',		"mod_product_photo");
	define ('CFG_DBTBL_MOD_PRODUCT_RECOMMENDED',		"mod_product_recommended");
	define ('CFG_DBTBL_MOD_ORDER', "mod_order");
	define ('CFG_DBTBL_MOD_ORDER_PRODUCT', "mod_order_product");
	define ('CFG_DBTBL_MOD_CONTACT', "mod_contact");
	define ('CFG_DBTBL_MOD_FEEDBACK_TEXT', "mod_feedback_txt");
	define ('CFG_DBTBL_MOD_FEEDBACK_GROUP', "mod_feedback_group");
	define ('CFG_DBTBL_MOD_PRODUCT_TAGS', "mod_product_tags");
	define ('CFG_DBTBL_MOD_TAGS', "mod_tags");
	define ('CFG_DBTBL_MOD_COLOR_PRODUCT', "mod_color_product");
	define ('CFG_DBTBL_MOD_CLIENT', "mod_client");
	define ('CFG_DBTBL_MOD_ARTICLES', "mod_articles");
	define ('CFG_DBTBL_MOD_CATEGORY_ARTICLES', "mod_category_articles");
	define ('CFG_DBTBL_MOD_ARTICLES_TAGS', "mod_articles_tags");
	define ('CFG_DBTBL_MOD_ANONCE', "mod_anonce");
	define ('CFG_DBTBL_MOD_PHOTO_DELIVERY', "mod_photo_delivery");
	define ('CFG_DBTBL_MOD_PHOTO_DELIVERY_PRODUCT', "mod_photo_delivery_product");
	define ('CFG_DBTBL_MOD_MAIN_SECTIONS', "mod_main_sections");
	define ('CFG_DBTBL_MOD_MAIN_SECTIONS_PRODUCT', "mod_main_sections_product");
	define ('CFG_DBTBL_MOD_MAIN_CATEGORY', "mod_main_category");
	define ('CFG_DBTBL_MOD_PHOTO',			"mod_photo");
	define ('CFG_DBTBL_MOD_PHOTO_GRDATA',	"mod_photogr_data");
	define ('CFG_DBTBL_MOD_BRANDS',	"mod_brands");
	define ('CFG_DBTBL_MOD_VIDEO',	"mod_videoslider");
	
	define ('CFG_DBTBL_SHUTTER_TEST',	"shutter_test");
	
	define ('CFG_DBTBL_MOD_FOTO',			"mod_foto");
	define ('CFG_DBTBL_MOD_FOTO_GRDATA',	"mod_fotogr_data");
	define ('CFG_DBTBL_MOD_FOTO_GRTREE',	"mod_fotogr_struct");
	
	$__TYPE_PAYMENT = array(
		1 => 'Наличными курьеру',
		2 => 'Безналичный расчет'
	);
