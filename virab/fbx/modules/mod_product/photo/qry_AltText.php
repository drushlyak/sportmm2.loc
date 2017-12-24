<?php
	$id_photo  = (int)$attributes['id_photo'];

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

	if ($id_photo) {
		$mod_data = $db->get_row("SELECT * FROM " . CFG_DBTBL_MOD_PRODUCT_PHOTO . " WHERE id = ?", $id_photo );
	}