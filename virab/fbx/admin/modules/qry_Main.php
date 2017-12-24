<?php
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _("У вас нет прав на просмотр");
		return;
	}

	$id_mod = $attributes['id_mod'];
	$count_pg = ($attributes['count_pg']) ? $attributes['count_pg'] : 20;
	$pg = ($attributes['pg']) ? $attributes['pg']-1 : 0;
	$record_now = $pg * $count_pg;

	$sql = "
		SELECT * FROM " . CFG_DBTBL_MODULE . "
		LIMIT $record_now, $count_pg
	";
	$count_records = $db->query_total($sql);
	$nodeSet = $db->get_all($sql);

?>