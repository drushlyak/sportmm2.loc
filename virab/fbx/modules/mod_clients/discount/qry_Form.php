<?php

	$type = (int) $attributes['type'];
	$id = (int) $attributes['id'];
	$id_client  = (int)$attributes['id_client'];

	// Проверка доступа
	if ($id) {
		if(!$auth_in->aclCheck($resourceId, EDIT)){
			$ACL_ERROR = _("У вас нет прав на редактирование");
			return;
		}
	} else {
		if (!$auth_in->aclCheck($resourceId, CREATE)){
			$ACL_ERROR = _("У вас нет прав на добавление");
			return;
		}
	}

	if ($id) {
		$sql = sql_placeholder("
			SELECT *
			FROM " . CFG_DBTBL_MOD_CLIENT_DISCOUNT . "
	    	WHERE id = ?
	    ", $id );
		$mod_data = $db->get_row($sql);

		if(!is_array($mod_data)){
			Location(sprintf($_XFA['discount'], $id_client), 0);
		}
	}
?>