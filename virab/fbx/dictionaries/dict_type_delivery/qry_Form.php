<?php
	$id  = (int)$attributes['id'];
	$type = (int) $attributes['type'];
	$id_city = (int) $attributes['id_city'];

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
			FROM " . CFG_DBTBL_MOD_TYPE_DELIVERY . "
	    	WHERE id = ?
	    ", $id );
		$dict_data = $db->get_row($sql);
		// языковые преобразования
		//$dict_data['name'] = $lng->Gettextlngall($dict_data['name']);

		if(!is_array($dict_data)){
			Location(sprintf($_XFA['type_delivery'], $id_city), 0);
		}
	}

?>