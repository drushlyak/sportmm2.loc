<?php

	error_reporting(E_ALL); //ERROR | E_WARNING | E_PARSE); // Отключаем выдачу сообщений о неинициализированных переменных
//	set_magic_quotes_runtime(0);					// Отключаем magic_quotes_runtime
//	if (get_magic_quotes_gpc()) {
//		die(_("Для работы системы необходимо отключить magic_quotes_gpc!"));
//	}

	// insulator of old code
	require_once (LIB_PATH . "/InsulatorOldCode.php");
	// Misc usefull function
	require_once (LIB_PATH . "/Misc.functions.php");
	require_once (LIB_PATH . "/Misc.forObradoval.php");
	// Db config
	require_once (LIB_PATH . "/db/class.mydb.php");
	// Nested sets trees
	require_once (LIB_PATH . "/Nstree.class.php");
	require_once (LIB_PATH . "/STree.class.php");
	// Dsp helper class
	require_once (LIB_PATH . "/DspHelper.class.php");
	// Data helper class
	require_once (LIB_PATH . "/DataHelper.class.php");
	// template helper class
	require_once (LIB_PATH . "/TemplateHelper.class.php");
	// Firebug php debug library
	require_once (MAIN_LIB_PATH . "/firePHP/fb.php");

	// Получим экземпляр DB layer
	$db = mydb::instance();

	// Получим экземпляр хэлпера отображения
	$dsp_helper = DspHelper::getInstance();

	// Получим экземпляр хэлпера входных данных
	$data_helper = DataHelper::getInstance();

	// Получим экземпляр хэлпера отображения
	$template_helper = TemplateHelper::getInstance();

	// Загрузим константы для таблиц установленных в системе модулей
	$sql = "SELECT * FROM " . CFG_DBTBL_TABLES;
	$tblSet = $db->get_all($sql);
	if ($tblSet) {
		foreach ($tblSet as $tbl) {
			define($tbl['tbl_const'],$tbl['tbl_name']);
		}
	}

	// Загрузим константы для типов шаблонов установленных в системе
	$sql = "SELECT * FROM " . CFG_DBTBL_TE_TYPE;
	$teSet = $db->get_all($sql);
	if ($teSet) {
		foreach ($teSet as $te) {
			define($te['te_const'], $te['te_value']);
		}
	}

	// Загрузим $_TYPE_TE_VALUE
	$__TYPE_TE_VALUE = array();
	$sql = "SELECT * FROM " . CFG_DBTBL_TE_TYPE_ARRAY;
	$teTypeSet = $db->get_all($sql);
	if ($teTypeSet) {
		foreach ($teTypeSet as $teType) {
			$sql = "SELECT te_value FROM ".CFG_DBTBL_TE_TYPE." WHERE id=?";
			$__const = $db->get_one($sql, $teType['te_type']);
			$__TYPE_TE_VALUE[$__const] = array( 'text' => $teType['text'], 'firstchar' => $teType['firstchar']);
		}
	}

	// Варианты обработки контентных модулей при отсутствии контента
//	$wile_actions = $db->get_all("SELECT * FROM " . CFG_DBTBL_SITEMAPEMPTYCONTENTRULES );
//	if (is_array($wile_actions)) {
//		foreach ($wile_actions as $wile_action) {
//			define($wile_action['constant'], $wile_action['value']);
//		}
//	}

	// Типы шаблонов страниц сайта
//	$__TYPE_TE_SITE_PAGE = array();
//	$te_page_types = $db->get_all("SELECT * FROM " . CFG_DBTBL_SITE_TMPL_PAGE_TYPES );
//	if (is_array($te_page_types)) {
//		foreach ($te_page_types as $te_page_type) {
//			define($te_page_type['constant'], $te_page_type['value']);
//			$__TYPE_TE_SITE_PAGE[(int) $te_page_type['value']] = $te_page_type['lngh_name'];
//		}
//	}

	// Загрузим библиотеку для работы с языками
	require_once (LIB_PATH . "/Language.class.php"); // Языковой класс для поддержки мультиязычности в БД
	// Инициализируем языковой модуль
	$lng = new Language();
	if (!$lng->Init()) {
		die(_("Язык инициализировать не удалось"));
	}

	// Прочитаем конфигурационную таблицу.
	// Если это не удастся, то вываливаемся с критической ошибкой
	$rsConfig = $db->query("SELECT * FROM " . CFG_DBTBL_CONFIG);
	if (!$rsConfig->num_rows) {
		die(_("Невозможно прочесть настройки. Проверьте параметры БД!"));
	}
	while ($row = $rsConfig->fetch_assoc()) {
		$site_config[$row['config_name']] = $row['config_value'];
	}

	// Загружаем библиотеки и классы
	require_once (LIB_PATH . "/Functions.php");					// Модуль разнородных функций
	require_once (LIB_PATH . "/Auth.class.php");				// Класс для авторизации пользователя и проверки прав доступа
	require_once (LIB_PATH . "/TreeStruct.php");				// Структура деревьев для класса NSTree
	require_once (LIB_PATH . "/tmpl/teController_class.php");	// Модуль управления шаблонными переменными
	require_once (LIB_PATH . "/tmpl/teTemplate_class.php");		// Шаблон обработки шаблонных переменных
	require_once (LIB_PATH . "/tmpl/teValue_class.php");		// Класс для работы с шаблонными переменными
	require_once (MAIN_LIB_PATH . "/MilKit/Acl.php");
	require_once (MAIN_LIB_PATH . "/MilKit/Acl/Store/MyDb.php");


	// Очистим масив содержащий данные сессии вызова скрипта
	unset($_this);

	// Запишем ЧПУ
	$_this['chpu'] = isset($_GET['ln']) ? strtolower(trim($_GET['ln'])) : '';

	// Определим запрошенный домен
	if (preg_match("/^(http:\/\/)?([0-9a-z_\-.~]+)/i", $_SERVER['HTTP_HOST'], $host)) {
		$_this['domen'] = $host[2];
	}

	$_this['printable'] = 0;
	$temp = strpos($_SERVER["REQUEST_URI"], "?");
	if ($temp !== false) {
		$_this['arg'] = substr($_SERVER["REQUEST_URI"], $temp+1, strlen($_SERVER["REQUEST_URI"])-$temp-1);
	}

	// Константы должностей
//	$job_posts = $db->get_all("SELECT * FROM " . CFG_DBTBL_DICT_JOB_POST );
//	if (is_array($job_posts)) {
//		foreach ($job_posts as $job) {
//			if ($job['sys_constant']) {
//				define($job['sys_constant'], $job['id']);
//			}
//		}
//	}

