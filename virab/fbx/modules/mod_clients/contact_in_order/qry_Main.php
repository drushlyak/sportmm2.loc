<?php
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _(" У вас нет прав на просмотр данного раздела");
		return;
	}

	$id_client = (int) $attributes['id_client'];

	$tableID = 't_mod_client_contact_in_order_grid';

	// сортировка =================================================================

	$order_line = $data_helper->getOrderLine($tableID, array(
		'fio' => 'mcio.fio',
	), "ORDER BY mcio.id DESC");

	// фильтрация =================================================================

	$part_where_line = $data_helper->getWhereLine(
		$tableID
		, $attributes
		, array(
			'fio' => "mcio.fio LIKE '*%'"
		)
		, "AND"
		, ""
	);

	// =================================================================

	$datapager = $dsp_helper->getDataPager(array('pageID' => 'mod_clients.contact_in_order'));

	$sql = sql_placeholder("
		SELECT mcio.*
			FROM " . CFG_DBTBL_MOD_CONTACT_IN_ORDER . " AS mcio, " . CFG_DBTBL_MOD_CLIENT_RECIPIENT . " AS mcr
			WHERE mcr.id_contact_in_order = mcio.id
			  AND mcr.id_client = ?
			  AND NOT mcio.`is_himself`
			{$part_where_line}
			{$order_line}
	", $id_client);

	$datapager->setTotal($db->query_total($sql));
	$datapager->getPagingParams($attributes);

	$dataSet = $db->get_all($sql . " " . $datapager->getLimitLine());
