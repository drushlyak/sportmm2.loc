<?php
	$id_city = (int) $attributes['id_city'];

	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _(" У вас нет прав на просмотр данного раздела");
		return;
	}

	$count_pg = ($attributes['count_pg']) ? $attributes['count_pg'] : 20;
	$pg = ($attributes['pg']) ? $attributes['pg']-1 : 0;
	$record_now = $pg * $count_pg;

	$sql = sql_placeholder("
		SELECT *
			FROM " . CFG_DBTBL_DICT_TYPE_DELIVERY );
	$count_records = $db->query_total($sql);
	$dataSet = $db->get_all_($sql);
?>