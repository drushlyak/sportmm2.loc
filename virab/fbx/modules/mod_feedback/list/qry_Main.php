<?php
	$id_feedback  = (int) $attributes['id_feedback'];

	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _(" У вас нет прав на просмотр данного раздела");
		return;
	}
	$sql = sql_placeholder("SELECT res_id FROM ".CFG_DBTBL_MOD_FEEDBACK_GROUP." WHERE id=?", $id_feedback);
	$res_id = $db->get_one($sql);
	if(!$auth_in->aclCheck($res_id, VIEW)){
		$ACL_ERROR = _(" У вас нет прав на просмотр данного раздела");
		return;
	}
/**/
	$count_pg = ($attributes['count_pg']) ? $attributes['count_pg'] : 20;
	$pg = ($attributes['pg']) ? $attributes['pg']-1 : 0;
	$record_now = $pg * $count_pg;

	$sql = sql_placeholder("
		SELECT mf.*,
				mp.name AS product_name,
				mp.article AS article
			FROM " . CFG_DBTBL_MOD_FEEDBACK_TEXT . " mf
				LEFT JOIN " . CFG_DBTBL_MOD_PRODUCT . " mp ON mf.id_product = mp.id
		WHERE mf.group_id = ?
		ORDER BY idate DESC
		LIMIT $record_now, $count_pg
	", $id_feedback );

	$count_records = $db->query_total($sql);
	$dataSet = $db->get_all($sql);
?>