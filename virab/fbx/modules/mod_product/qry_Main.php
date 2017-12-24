<?php
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _(" У вас нет прав на просмотр данного раздела");
		return;
	}

	$tableID = 't_mod_product_grid';

	// сортировка =================================================================

	$order_line = $data_helper->getOrderLine($tableID, array(
		'name' => 'mp.name',
		'article' => 'mp.article',
		'min_cost' => 'mp.min_cost',
		'count_min_cost' => 'mp.count_min_cost',
		'cost_excess' => 'mp.cost_excess',
		'city' => 'dc.name'
	), "ORDER BY mp.id DESC");

	// фильтрация =================================================================
	if ($attributes['no_active'] == 1) {
			$attributes['no_active'] = 2;
	} else {
			$attributes['no_active'] = 0;
	}

	$part_where_line = $data_helper->getWhereLine(
		$tableID
		, $attributes
		, array(
			'name'		=> "mp.name LIKE '%*%'",
			'article'	=> "mp.article LIKE '%*%'",
			'city'		=> "mp.id_city = *",
			'categories' 	=> "mcp.id_category = *",
			'id_producer' 	=> "mp.id_producer = *",
			'is_view_main'	=> "mp.is_view_main = *",
			'no_active'		=> "IF(* = 2, mp.is_active = 0, mp.is_active = 1)",
			'is_active'		=> "mp.is_active = *",
			'no_photo'		=> "IF(* = 1, mp.main_foto50 = '', mp.main_foto50)",

		)
		, "AND"
		, ""
	);

	// =================================================================

	$datapager = $dsp_helper->getDataPager(array('pageID' => 'mod_product.main'));

	$sql = "
		SELECT mp.*
			 , IF (mp.is_active = 0, 'f7ccd8', '') AS color
		FROM " . CFG_DBTBL_MOD_PRODUCT . " AS mp  
		   " . (($attributes['categories']) ? ", " . CFG_DBTBL_MOD_CATEGORY_PRODUCT . " AS mcp" : "") . "
		 
			WHERE mp.id " . (($attributes['categories']) ? "AND mcp.id_product = mp.id" : "") . "
			  {$part_where_line}
		{$order_line}
	";
	

	$datapager->setTotal($db->query_total($sql));
	$datapager->getPagingParams($attributes);

	$dataSet = $db->get_all($sql . " " . $datapager->getLimitLine());

?>