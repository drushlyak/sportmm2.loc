<?php
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _(" У вас нет прав на просмотр данного раздела");
		return;
	}

	$id_client = (int) $attributes['id_client'];
	$id_contact = (int) $attributes['id_contact'];

	$tableID = 't_mod_client_address_in_order_grid';

	// сортировка =================================================================

	$order_line = $data_helper->getOrderLine($tableID, array(
		'type' => 'mas.type_of_address',
		'address' => 'mas.city, mas.street, mas.house'
	), "ORDER BY mas.id DESC");

	// фильтрация =================================================================

	$part_where_line = $data_helper->getWhereLine(
		$tableID
		, $attributes
		, array(
			'city' => "mas.city LIKE '*%'",
			'street' => "mas.street LIKE '*%'",
			'house' => "mas.house LIKE '*%'"
		)
		, "AND"
		, ""
	);

	// =================================================================

	$datapager = $dsp_helper->getDataPager(array('pageID' => 'mod_clients.client_orders'));

	$sql = sql_placeholder("
		SELECT mas.*
			FROM " . CFG_DBTBL_MOD_ADDRESS_STORAGE . " AS mas, " . CFG_DBTBL_MOD_CONTACT_IN_ORDER_ADDRESSES . " AS mcoa
			WHERE mas.id = mcoa.id_address_storage
			  AND mcoa.id_contact_in_order = ?
			{$part_where_line}
			{$order_line}
	", $id_contact);

	$datapager->setTotal($db->query_total($sql));
	$datapager->getPagingParams($attributes);

	$dataSet = $db->get_all($sql . " " . $datapager->getLimitLine());
?>