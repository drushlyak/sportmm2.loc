<?php
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _(" У вас нет прав на просмотр данного раздела");
		return;
	}

	$id_client = (int) $attributes['id_client'];

	$tableID = 't_mod_client_bonus_grid';

	// сортировка =================================================================

	$order_line = $data_helper->getOrderLine($tableID, array(
		'number' => 'mcb.phone',
		'mobile' => 'mcb.is_mobile'
	), "ORDER BY mcb.id DESC");

	// фильтрация =================================================================

	$part_where_line = $data_helper->getWhereLine(
		$tableID
		, $attributes
		, array(
			'number' => "ph.phone LIKE '*%'"
		)
		, "AND"
		, ""
	);

	// =================================================================

	$datapager = $dsp_helper->getDataPager(array('pageID' => 'mod_clients.bonus'));

	$sql = sql_placeholder("
		SELECT mcb.*
			FROM " . CFG_DBTBL_MOD_CLIENT_BONUS . " AS mcb
			WHERE mcb.id_client = ?
			{$part_where_line}
			{$order_line}
	", $id_client);
	
	$datapager->setTotal($db->query_total($sql));
	$datapager->getPagingParams($attributes);

	$dataSet = $db->get_all($sql . " " . $datapager->getLimitLine());
?>