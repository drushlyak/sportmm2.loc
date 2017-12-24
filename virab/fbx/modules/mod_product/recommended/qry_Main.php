<?php
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _(" У вас нет прав на просмотр данного раздела");
		return;
	}

	$id_product = (int) $attributes['id_product'];

	$tableID = 't_mod_product_recommended_grid';

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

	$datapager = $dsp_helper->getDataPager(array('pageID' => 'mod_product.recommended'));

	$sql = sql_placeholder("
		SELECT mpr.id
			 , mp.name
			 , mp.article
			 , mp.main_foto50
		FROM " . CFG_DBTBL_MOD_PRODUCT_RECOMMENDED . " AS mpr
		   , " . CFG_DBTBL_MOD_PRODUCT . " AS mp
		WHERE mpr.id_product_itself = ?
		  AND mpr.id_recommended_product = mp.id
			  {$part_where_line}
		{$order_line}
	", $id_product);

	$datapager->setTotal($db->query_total($sql));
	$datapager->getPagingParams($attributes);

	$dataSet = $db->get_all($sql . " " . $datapager->getLimitLine());
?>