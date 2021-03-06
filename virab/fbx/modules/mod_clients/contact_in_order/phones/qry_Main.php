<?php
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _(" У вас нет прав на просмотр данного раздела");
		return;
	}

	$id_client = (int) $attributes['id_client'];
	$id_contact = (int) $attributes['id_contact'];

	$tableID = 't_mod_client_contact_in_order_phones_grid';

	// сортировка =================================================================

	$order_line = $data_helper->getOrderLine($tableID, array(
		'phone' => 'mps.phone',
		'mobile' => 'mps.is_mobile'
	), "ORDER BY mps.id DESC");

	// фильтрация =================================================================

	$part_where_line = $data_helper->getWhereLine(
		$tableID
		, $attributes
		, array(
			'phone' => "mps.phone LIKE '*%'"
		)
		, "AND"
		, ""
	);

	// =================================================================

	$datapager = $dsp_helper->getDataPager(array('pageID' => 'mod_clients.cio_phones'));

	$sql = sql_placeholder("
		SELECT mps.*
			FROM " . CFG_DBTBL_MOD_PHONES_STORAGE . " AS mps, " . CFG_DBTBL_MOD_CONTACT_IN_ORDER_PHONES . " AS mcop
			WHERE mps.id = mcop.id_phone_storage
			  AND mcop.id_contact_in_order = ?
			{$part_where_line}
			{$order_line}
	", $id_contact);

	$datapager->setTotal($db->query_total($sql));
	$datapager->getPagingParams($attributes);

	$dataSet = $db->get_all($sql . " " . $datapager->getLimitLine());
?>