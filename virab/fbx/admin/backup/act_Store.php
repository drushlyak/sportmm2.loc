<?
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, CREATE)){
		$ACL_ERROR = _("У вас нет прав на создание архива БД");
		Location(sprintf($_XFA['mainf'], ((strlen(trim($ACL_ERROR)) > 350) ? substr($ACL_ERROR, 0, 350)."..." : trim($ACL_ERROR))), 0);
		die;
	}

	$cmd = "/usr/bin/mysqldump --user=\"" . CFG_DB_USERNAME . "\" --host=\"" . CFG_DB_HOSTNAME . "\" --password=\"" . CFG_DB_PASSWORD . "\" " . CFG_DB_DATABASE;

	if (CFG_DB_HOSTNAME === '192.168.100.1') {
		// хак для старого MySQL
		$cmd = "/usr/bin/mysqldump --default-character-set=\"utf8\" --user=\"" . CFG_DB_USERNAME . "\" --host=\"" . CFG_DB_HOSTNAME . "\" --password=\"" . CFG_DB_PASSWORD . "\" " . CFG_DB_DATABASE;
	}

	/**
	 * Структура наименования файла (с разделением знаком _):
	 * dump
	 * PROJECT_ID (с удаленным _)
	 * ID пользователя
	 * дата-время в формате Unix timestamp
	 * признак загруженного извне дампа (0 или 1)
	 */
	$file = 'dump_' . str_replace('_', '', PROJECT_ID) . "_" . $auth_in->user_id . "_" . date("U", mktime()) . "_0" . ".sql";
	$output = BACKUP_PATH . "/" . $file;

	if (is_writable(BACKUP_PATH)) {
		exec($cmd . " | gzip -9 -c > " . $output . ".gz");

		if (!file_exists($output . ".gz")) {
			$FORM_ERROR = _("Невозможно создать архив");
		}

		Location($_XFA['main'], 0);

	} else {
		$FORM_ERROR = _("Нет прав на запись");
	}
	Location(sprintf($_XFA['mainf'], ((strlen(trim($FORM_ERROR)) > 350) ? substr($FORM_ERROR, 0, 350)."..." : trim($FORM_ERROR))), 0);
?>