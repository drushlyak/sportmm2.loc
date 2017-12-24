<?php
	
	session_start();
	define('VIRAB_PRO', true);

	date_default_timezone_set('Europe/Moscow'); //устанавливаем московское время в скриптах сайта
	
	if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'msie') > 0 && !$_SESSION['test_msie_cookie']) {
		$_COOKIE['msie'] = 1;
		$_SESSION['test_msie_cookie'] = 1;
		header('Location: ' . $_SERVER['REQUEST_URI']);
		die();
	}
	
	require_once ("conf/core.cfg.php");
	require_once (LIB_PATH . "/Common.php"); 
	$attr = $_REQUEST;

	ParseUrl();
	$lng->now_lng = $_this['lng'];
	$_this['page']['title']       = $lng->Gettextlng($_this['page']['title']);
	$_this['page']['description'] = $lng->Gettextlng($_this['page']['description']);
	$_this['page']['keywords']    = $lng->Gettextlng($_this['page']['keywords']);
	
	$sql = sql_placeholder("
		SELECT id 
		FROM " . CFG_DBTBL_PAGELNG . " 
		WHERE page_id = ? 
			AND language_id = ? 
			AND page_menu = ?", 
		$_this['page']['id']
	  , $lng->deflt_lng
	  , ACC_LNG_PAGE
	);
	
	$res = $db->get_one($sql);
	if (!$res) {
		mkGoTo();
	}
	
	// Template
	$templatesController = new teController($_this['page']['id_contaner']);
	if ($templatesController) {
		print $templatesController;
	}

