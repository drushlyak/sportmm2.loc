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
			FROM " . CFG_DBTBL_MOD_MAIN_SECTIONS . "
	    	WHERE id = ?
	    ", $id );
		$mod_data = $db->get_row($sql);
		// языковые преобразования
		//$dict_data['name'] = $lng->Gettextlngall($dict_data['name']);
		
		// заказанные продукты
		$mod_data['products'] = $db->get_all("
		SELECT *, mp.main_foto50 AS photo
			FROM " . CFG_DBTBL_MOD_MAIN_SECTIONS_PRODUCT . " AS mop
			   , " . CFG_DBTBL_MOD_PRODUCT . " AS mp
			WHERE mop.id_section = ?
			  AND mop.id_product = mp.id
		", $id);
		
		
		if(!is_array($mod_data)){
			Location($_XFA['main'], 0);
		}
	}
	
?>