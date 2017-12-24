<?php

	$id = (int) $attributes['id'];
	$type = (int) $attributes['type'];

	// Проверка доступа
	if ($id && ($type == 2)) {
		if (!$auth_in->aclCheck($resourceId, EDIT)) {
			$ACL_ERROR = _("У вас нет прав на редактирование");
			return;
		}
	} else {
		if (!$auth_in->aclCheck($resourceId, CREATE)) {
			$ACL_ERROR = _("У вас нет прав на создание");
			return;
		}
	}

	if ($id) {
		$sql = "
			SELECT *
			FROM " . CFG_DBTBL_DICT_LANGUAGE . "
			WHERE id = ?
		";
		$form_data = $db->get_row($sql, $id);

		if (!$form_data) {
			Location($_XFA['main'], 0);
		}

	}

?>