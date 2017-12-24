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
			FROM " . CFG_DBTBL_MOD_PHOTO_DELIVERY . "
	    	WHERE id = ?
	    ", $id );
		$mod_data = $db->get_row($sql);

		$mod_data['products'] = $db->get_all("
			SELECT mp.id
				 , mp.name
				 , mp.article
				FROM " . CFG_DBTBL_MOD_PRODUCT . " AS mp
				   , " . CFG_DBTBL_MOD_PHOTO_DELIVERY_PRODUCT . " AS mpdp
				WHERE mp.id = mpdp.id_product
				  AND mpdp.id_photo_delivery = ?
		", $id);

		if(!is_array($mod_data)){
			Location($_XFA['main'], 0);
		}
	}

?>