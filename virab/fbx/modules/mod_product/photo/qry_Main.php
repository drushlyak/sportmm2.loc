<?php
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _(" У вас нет прав на просмотр данного раздела");
		return;
	}

	$id_product = (int) $attributes['id_product'];

	$dataSet = $db->get_all(sql_placeholder("
		SELECT *
			FROM " . CFG_DBTBL_MOD_PRODUCT_PHOTO . "
		WHERE id_product = ?
		ORDER BY pos ASC
	", $id_product));
?>