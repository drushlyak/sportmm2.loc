<?php
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _(" У вас нет прав на просмотр данного раздела");
		return;
	}

	$tableID = 't_mod_order_grid';

	// сортировка =================================================================

	$order_line = $data_helper->getOrderLine($tableID, array(
		'id' => 'mo.id',
		'date_order' => 'mo.date_order',
		'date_delivery' => 'mo.delivery_date',
		'price' => 'mo.sum'
	), "ORDER BY mo.id DESC");

	// фильтрация =================================================================

	$part_where_line = $data_helper->getWhereLine(
		$tableID
		, $attributes
		, array(
			'id' => "mo.id = *",
			'id_client' => "mo.id_client = *",
			'state_order' => "mo.id_state_order = *",
			'order_number' => "moai.number = *",
			'date_order_range' => "mo.date_order %between_date%",
			'date_delivery_range' => "moai.delivery_date %between_date%",
			'id_city' => "id_city = *"
		)
		, "AND"
		, "TRUE"
	);

	// =================================================================

	$datapager = $dsp_helper->getDataPager(array('pageID' => 'mod_order.main'));

	$sql = sql_placeholder("
		SELECT mo.*,
				CONCAT(mc.f_name, ' ', mc.i_name) AS fio,				
				DATE_FORMAT(mo.date_order, '%d.%m.%Y<br/>%H:%i') AS date_order
		FROM " . CFG_DBTBL_MOD_ORDER . " AS mo
			LEFT JOIN " . CFG_DBTBL_MOD_CLIENT . " AS mc ON mc.id = mo.id_client				
		WHERE {$part_where_line}
		{$order_line}
	");
		
	//echo 	$sql;

	$datapager->setTotal(count($db->get_all("
		SELECT mo.id,
				(	SELECT mpro.id_city
						FROM " . CFG_DBTBL_MOD_ORDER_PRODUCT . " mop
							JOIN " . CFG_DBTBL_MOD_PRODUCT . " mpro ON mpro.id = mop.id_product
					WHERE mop.id_order = mo.id
					LIMIT 1
				) AS id_city
		FROM " . CFG_DBTBL_MOD_ORDER . " AS mo
				
		WHERE {$part_where_line}
	")));
	$datapager->getPagingParams($attributes);

	$dataSet = $db->get_all($sql . " " . $datapager->getLimitLine());


	$lastsOrders = $db->get_vector("
		SELECT id
			FROM " . CFG_DBTBL_MOD_ORDER . "
		ORDER BY id DESC
		LIMIT 10
	");
	$lastsOrders = is_array($lastsOrders) ? $lastsOrders : array();
