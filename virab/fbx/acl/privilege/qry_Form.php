<?php
	$typ = (int) $attributes['typ'];
	$id = (int) $attributes['id'];

	// Проверка доступа
	if ($typ == 2) {
		if (!$auth_in->aclCheck($resourceId, EDIT)) {
			$ACL_ERROR = _("У вас нет прав на редактирование");
			return;
		}
	} else {
		if (!$auth_in->aclCheck($resourceId, CREATE)) {
			$ACL_ERROR = _("У вас нет прав на добавление");
			return;
		}
	}

	$configTable = $auth_in->store->getConfig();
	if ($typ == 2) {
		$sql = sql_placeholder("
			SELECT *
				FROM " . $configTable['privilegeTable'] . "
				WHERE id = ?
		", $id);
		$privilege = $db->get_row($sql);
		$privilege['name'] = $lng->Gettextlngall($privilege['name']);
	}

?>