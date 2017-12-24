<?php

	$id = intval($attributes['id']);
	$typ = intval($attributes['typ']);

	if ($id) {
		$sql = "
			SELECT *
			FROM " . CFG_DBTBL_MODULE . "
			WHERE id = ?";
		$category = $db->get_row($sql, $id);
		if (!$category) {
			Location($_XFA['main'], 0);
		} else {
			$name = $category['name'];
			$category['name'] = $lng->Gettextlngall($category['name']);
			$category['description'] = $lng->Gettextlngall($category['description']);
	 	}
	}

	// Проверка доступа
	if ($id){
		if (!$auth_in->aclCheck($resourceId, EDIT)){
			$ACL_ERROR = _("У вас нет прав на редактирование");
			return;
		}
	} else {
		if (!$auth_in->aclCheck($resourceId, CREATE)){
			$ACL_ERROR = _("У вас нет прав на установку модулей");
			return;
		}
	}

?>