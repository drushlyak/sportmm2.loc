<?php

	define('VIRAB_PRO', true);
	$tstart = microtime(1);								// Считываем текущее время

	require_once ("../conf/core.cfg.php");				// Настройки ядра
	require_once (LIB_PATH . "/Common.php");			// Подключаем модули, классы, читаем настройки, проверяем работоспособность
	require_once (BASE_PATH . "/spaw2/spaw.inc.php");	// Spaw WYSIWYG editor
	require_once ("fbx_Fusebox3.0_PHP4.1.x.php");		// Fusebox

?>