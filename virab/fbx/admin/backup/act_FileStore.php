<?
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, LOAD)){
		$ACL_ERROR = _("У вас нет прав на загрузку архива БД");
		Location(sprintf($_XFA['mainf'], ((strlen(trim($ACL_ERROR)) > 350) ? substr($ACL_ERROR, 0, 350)."..." : trim($ACL_ERROR))), 0);
		die;
	}

	/**
	 * Структура наименования файла (с разделением знаком _):
	 * dump
	 * PROJECT_ID (с удаленным _)
	 * ID пользователя
	 * дата-время в формате Unix timestamp
	 * признак загруженного извне дампа (0 или 1)
	 */
	$file = 'dump_' . str_replace('_', '', PROJECT_ID) . "_" . $auth_in->user_id . "_" . date("U", mktime()) . "_1" . ".sql.gz";
	$output = BACKUP_PATH . "/" . $file;

	if (is_writable(BACKUP_PATH)) {
		// получение файла
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			move_uploaded_file($_FILES['userfile']['tmp_name'], $output);
		}

		if (!file_exists($output)) {
			$FORM_ERROR = _("Невозможно записать файл");
		}

		Location($_XFA['main'], 0);

	} else {
		$FORM_ERROR = _("Нет прав на запись");
	}
	Location(sprintf($_XFA['mainf'], ((strlen(trim($FORM_ERROR)) > 350) ? substr($FORM_ERROR, 0, 350)."..." : trim($FORM_ERROR))), 0);
?>