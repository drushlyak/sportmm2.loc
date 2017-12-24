<?php
	$id  = (int)$attributes['id'];
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
		$sql = sql_placeholder("
			SELECT *
			FROM " . CFG_DBTBL_MOD_VIDEO . "
	    	WHERE id = ?
	    ", $id );
		$mod_data = $db->get_row($sql);

		if(!is_array($mod_data)){
			Location($_XFA['main'], 0);
		}
	}

?>