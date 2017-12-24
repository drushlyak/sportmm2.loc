<?php
	if(!isset($attributes["fuseaction"])){
		$attributes["fuseaction"] = "home.main";
	}

	//useful constants
	if (!isset($GLOBALS["self"])) {
		$GLOBALS["self"] = "index.php";
	}

	$XFA = array();

	//default values for layout files
	$Fusebox["layoutDir"] = "";
	$Fusebox["layoutFile"] = "fbx_DefaultLayout.php";
	//should fusebox silently suppress its own error messages? default is FALSE
	$Fusebox["suppressErrors"] = false;
	if ($Fusebox["isHomeCircuit"]) {
		//put settings here that you want to execute only when this is the application's home circuit (for example: session_start(); )
	} else {
		//put settings here that you want to execute only when this is not an application's home circuit
	}

	// Запуск сессии
	session_start();

	// Авторизация пользователя
	$auth_in = new Auth();
	if(!$auth_in->authed){
		exit;
	}

	// Языки
	$__LNG_ARRAY = $db->get_all("
		SELECT * FROM " . CFG_DBTBL_DICT_LANGUAGE . "
	");

	$__LNG_ID_deflt_msgid_gettext = $db->get_one("
		SELECT id
			FROM " . CFG_DBTBL_DICT_LANGUAGE . "
		WHERE deflt_msgid_gettext = 1
	");

	if (empty($_SESSION['lng_selected'])) {
		$_SESSION['lng_selected'] = $__LNG_ID_deflt_msgid_gettext;
	}

	$lng->setNowLng($_SESSION['lng_selected']);
	if ($_SESSION['lng_selected'] != $__LNG_ID_deflt_msgid_gettext) {
		$lng_param_row = $db->get_row("SELECT * FROM " . CFG_DBTBL_DICT_LANGUAGE . " WHERE id = ?", $_SESSION['lng_selected']);

		// переключим языковую локаль
		putenv("LC_ALL=" . $lng_param_row['locale']);
		setlocale (LC_ALL, $lng_param_row['locale']);
		$domain = 'messages';
		bindtextdomain ($domain, BASE_PATH . "/locale");
		textdomain ($domain);
	}

	// Подгружаем константы для привилегий
	$configACL = $auth_in->store->getConfig();
	$sql = "
		SELECT * FROM ".$configACL['privilegeTable']
	;
	$privileges = $db->get_all($sql);
	if ($privileges) {
		foreach($privileges as $priv){
			$var = strtoupper($priv['var']);
			define($var, $priv['id']);
		}
	}

	// Навигация
	include ("fbx/qry_Navigation.php");

?>