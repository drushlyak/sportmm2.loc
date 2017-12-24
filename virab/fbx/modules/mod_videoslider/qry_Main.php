<?php
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _(" У вас нет прав на просмотр данного раздела");
		return;
	}

//	$id_product = (int) $attributes['id_product'];

	$tableID = 't_mod_videoslider_grid';

	// сортировка =================================================================

	$order_line = $data_helper->getOrderLine($tableID, array(
		'name' => 'mp.name',
		'article' => 'mp.article'
	), "ORDER BY mpr.id DESC");

	// фильтрация =================================================================

	$part_where_line = $data_helper->getWhereLine(
		$tableID
		, $attributes
		, array(
			'name' => "mp.name LIKE '*%'",
			'article' => "mp.article LIKE '%*%'"
		)
		, "AND"
		, ""
	);

	// =================================================================

	$datapager = $dsp_helper->getDataPager(array('pageID' => 'mod_videoslider'));

	$sql = sql_placeholder("
		SELECT mpr.*
			 , IF (mpr.is_active = 0, 'f7ccd8', '') AS color
		FROM " . CFG_DBTBL_MOD_VIDEO . " AS mpr
		WHERE mpr.id {$part_where_line}
		{$order_line}
	");

	$datapager->setTotal($db->query_total($sql));
	$datapager->getPagingParams($attributes);

	$dataSet = $db->get_all($sql . " " . $datapager->getLimitLine());
?>