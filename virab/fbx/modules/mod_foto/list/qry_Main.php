<?php
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _(" У вас нет прав на просмотр данного раздела");
		return;
	}

	$id_fotogr = (int) $attributes['id_fotogr'];

	$dataSet = $db->get_all(sql_placeholder("
		SELECT *
			FROM " . CFG_DBTBL_MOD_EVENT_PHOTO . "
		WHERE id_fotogr = ?
		ORDER BY pos ASC
	", $id_fotogr));
	
	$orig = $db->get_one_(sql_placeholder("
		SELECT orig_img
			FROM " . CFG_DBTBL_MOD_FOTO_GRDATA . "
		WHERE id = ?
	", $id_fotogr));
	
?>