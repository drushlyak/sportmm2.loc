<?php
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _(" У вас нет прав на просмотр данного раздела");
		return;
	}

	$id_client = (int) $attributes['id_client'];

	$tableID = 't_mod_client_contact_person_grid';

	// сортировка =================================================================

	$order_line = $data_helper->getOrderLine($tableID, array(
		'fio' => 'mcp.fio',
		'email' => 'mcp.email'
	), "ORDER BY mcp.id DESC");

	// фильтрация =================================================================

	$part_where_line = $data_helper->getWhereLine(
		$tableID
		, $attributes
		, array(
			'fio' => "mcp.fio LIKE '*%'",
			'email' => "mcp.email LIKE '*%'"
		)
		, "AND"
		, ""
	);

	// =================================================================

	$datapager = $dsp_helper->getDataPager(array('pageID' => 'mod_clients.contact_person'));

	$sql = sql_placeholder("
		SELECT mcp.*
			FROM " . CFG_DBTBL_MOD_CONTACT_PERSON . " AS mcp, " . CFG_DBTBL_MOD_CLIENT_CONTACT_PERSONS . " AS mccp
			WHERE mccp.id_contact_person = mcp.id
			  AND mccp.id_client = ?
			{$part_where_line}
			{$order_line}
	", $id_client);

	$datapager->setTotal($db->query_total($sql));
	$datapager->getPagingParams($attributes);

	$dataSet = $db->get_all($sql . " " . $datapager->getLimitLine());
?>