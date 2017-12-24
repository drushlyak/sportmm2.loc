<?php
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _(" У вас нет прав на просмотр данного раздела");
		return;
	}

	$tableID = 't_mod_photo_grid';

	// сортировка =================================================================

	$order_line = $data_helper->getOrderLine($tableID, array(
		'name' => 'mpg.name'
	), "ORDER BY mpg.id DESC");

	// фильтрация =================================================================
	$part_where_line = $data_helper->getWhereLine(
		$tableID
		, $attributes
		, array(
			'name'		=> "mpg.name LIKE '%*%'"
		)
		, "AND"
		, ""
	);

	// =================================================================

	$datapager = $dsp_helper->getDataPager(array('pageID' => 'mod_photo.main'));

	$sql = "
		SELECT mpg.*
		FROM " . CFG_DBTBL_MOD_PHOTO_GRDATA . " AS mpg
			WHERE mpg.id {$part_where_line}
		{$order_line}
	";
	//echo $sql;

	$datapager->setTotal($db->query_total($sql));
	$datapager->getPagingParams($attributes);

	$dataSet = $db->get_all($sql . " " . $datapager->getLimitLine());

?>