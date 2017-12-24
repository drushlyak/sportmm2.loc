<?php

	$id	= (int)$attributes['id'];
	$type = (int) $attributes['type'];

	$configTable = $auth_in->store->getConfig();

	// Проверка доступа
	if ($id && ($type == 2)) {
		if (!$auth_in->aclCheck($resourceId, EDIT)) {
			$ACL_ERROR = _("У вас нет прав на редактирование");
			Location(sprintf($_XFA['mainf'], ((strlen(trim($ACL_ERROR)) > 350) ? substr($ACL_ERROR, 0, 350)."..." : trim($ACL_ERROR))), 0);
			return;
		}
	} else {
		if (!$auth_in->aclCheck($resourceId, CREATE)) {
			$ACL_ERROR = _("У вас нет прав на создание");
			Location(sprintf($_XFA['mainf'], ((strlen(trim($ACL_ERROR)) > 350) ? substr($ACL_ERROR, 0, 350)."..." : trim($ACL_ERROR))), 0);
			return;
		}
	}

	if ($id && $type == 2) {

		$form_data = $db->get_row("
			SELECT id
				 , name
			FROM {$configTable['roleTable']}
			WHERE id = ?
		", $id);

		if(!is_array($form_data)){
			Location(sprintf($_XFA['products_main'], $id_group), 0);
		}
	} else {
		$form_data = array();
	}

?>