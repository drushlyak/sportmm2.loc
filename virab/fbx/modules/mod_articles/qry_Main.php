<?php
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _(" У вас нет прав на просмотр данного раздела");
		return;
	}

	$tableID = 't_mod_articles_grid';

	// сортировка =================================================================

	$order_line = $data_helper->getOrderLine($tableID, array(
		'phone' => 'mc.phone',
		'email' => 'mc.email',
		'f_name'   => 'mc.f_name',
		'i_name'   => 'mc.i_name',
	), "ORDER BY mc.id DESC");

	// фильтрация =================================================================

	$part_where_line = $data_helper->getWhereLine(
		$tableID
		, $attributes
		, array(
			'email' => "IF ('*' <> '', mc.email LIKE '%*%', TRUE)",
			'phone' => "IF ('*' <> '', mc.phone LIKE '%*%', TRUE)",
			'f_name' => "IF ('*' <> '', mc.f_name LIKE '%*%', TRUE)",
			'i_name' => "IF ('*' <> '', mc.i_name LIKE '%*%', TRUE)"
		)
		, "AND"
		, "TRUE"
	);

	// =================================================================

	$datapager = $dsp_helper->getDataPager(array('pageID' => 'mod_articles.main'));

	$sql = "
		SELECT mc.*
		FROM " . CFG_DBTBL_MOD_CATEGORY_ARTICLES . " AS mc
			WHERE {$part_where_line}
			{$order_line}
	";
			//echo $sql;

	$datapager->setTotal($db->query_total($sql));
	$datapager->getPagingParams($attributes);

	$dataSet = $db->get_all($sql . " " . $datapager->getLimitLine());
?>