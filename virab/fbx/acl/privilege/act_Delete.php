<?php
	// Проверка доступа
	if (!$auth_in->aclCheck($resourceId, DELETE)) {
		$ACL_ERROR .= _("У вас нет прав на удаление");
		Location(sprintf($_XFA['mainf'], ((strlen(trim($ACL_ERROR)) > 350) ? substr($ACL_ERROR, 0, 350)."..." : trim($ACL_ERROR))), 0);
		die;
	}

	$id = $attributes['id'];
	$FORM_ERROR = "";
	$configTable = $auth_in->store->getConfig();

	if ($id) {
		$sql = sql_placeholder("SELECT * FROM {$configTable['privilegeTable']} WHERE id=?", $id);
		$privilege = $db->get_row($sql);
		// удалим текстовые элементы
		$lng->Deltext($privilege['name']);
		// удалим запись и использование оной в таблице прав и таблицы привилегий для модулей
		$sql = sql_placeholder("
			DELETE FROM " . $configTable['ruleTable'] . "
			WHERE privilege_id = ?
		", $id);
		$db->query($sql);

		$sql = sql_placeholder("
			DELETE FROM " . CFG_DBTBL_ACL_MOD_PRIV . "
			WHERE privilege_id = ?
		", $id);
		$db->query($sql);

		$sql = sql_placeholder("
			DELETE FROM " . $configTable['privilegeTable'] . "
			WHERE id = ?
		", $id);
		$db->query($sql);

	} else {
		$FORM_ERROR .= _("Отсутствует запись для") . " id=" . $id;
	}

	Location(sprintf($_XFA['mainf'], ((strlen(trim($FORM_ERROR)) > 350) ? substr($FORM_ERROR, 0, 350)."..." : trim($FORM_ERROR))), 0);

?>