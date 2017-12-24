<?php

	$type = (int) $attributes['type'];
	$id = (int) $attributes['id'];
	$id_client  = (int)$attributes['id_client'];
	$id_contact  = (int)$attributes['id_contact'];

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
			SELECT mps.*
			FROM " . CFG_DBTBL_MOD_PHONES_STORAGE . " AS mps
	    	WHERE mps.id = ?
	    ", $id);
		$mod_data = $db->get_row($sql);

		if(!is_array($mod_data)){
			Location(sprintf($_XFA['cio_phones'], $id_client, $id_contact), 0);
		}
	}
?>