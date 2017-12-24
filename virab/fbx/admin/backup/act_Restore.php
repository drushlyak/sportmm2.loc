<?
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, LOAD)){
		$ACL_ERROR = _("У вас нет прав на создание БД");
		Location(sprintf($_XFA['mainf'], ((strlen(trim($ACL_ERROR)) > 350) ? substr($ACL_ERROR, 0, 350)."..." : trim($ACL_ERROR))), 0);
		die;
	}

	$id = $attributes['id'];

	include_once('functions.php');

	$dir_struct = getDirStruct(BACKUP_PATH);
	$ids = getIDS($dir_struct);
	$filename = $ids[$id];

	if ($filename) {
		// развернем gz и передадим поток в mysql
		$cmd = "/usr/bin/mysql --verbose --default-character-set=\"utf8\" --user=\"" . CFG_DB_USERNAME . "\" --host=\"" . CFG_DB_HOSTNAME . "\" --password=\"" . CFG_DB_PASSWORD . "\" " . CFG_DB_DATABASE;
		exec("gzip -d -c " . BACKUP_PATH . "/" . $filename . " | " . $cmd);

		// очистим memcache
		if (class_exists('Memcache') && MEMCACHE_USE) {
			$memcache = new Memcache();
			$memcache->connect(MEMCACHE_CONFIG_HOST, MEMCACHE_CONFIG_PORT);
			$memcache->flush();
		}

	} else {
		$FORM_ERROR = _("Файл не найден");
		Location(sprintf($_XFA['mainf'], ((strlen(trim($FORM_ERROR)) > 350) ? substr($FORM_ERROR, 0, 350)."..." : trim($FORM_ERROR))), 0);
	}

	Location(sprintf($_XFA['mainmessage'], _("Дамп базы успешно восстановлен.")), 0);
?>