<?php

	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _("У вас нет прав на просмотр");
		return;
	}

	$tableID = 't_language_construct';

	// сортировка =================================================================

	$order_line = $data_helper->getOrderLine($tableID, array(
		'id' => 'l.id',
		'var' => 'l.name_value',
		'lng' => 'l.id_dict_language'
	), "ORDER BY l.name_value DESC");

	// фильтрация =================================================================

	$part_where_line = $data_helper->getWhereLine(
		$tableID
	  , &$attributes
	  , array(
			'var_name' => "l.name_value LIKE '*%'",
			'text' => "l.text LIKE '%*%'"
		)
	  , "AND"
	  , "l.id_dict_language = d.id"
	);

	// =================================================================

	$datapager = $dsp_helper->getDataPager(array('pageID' => 'language.main'));

	$sql = "
		SELECT l.id
			 , l.name_value
			 , l.text AS value
			 , d.name AS name_lng
		FROM " . CFG_DBTBL_LANGUAGE . " AS l,
			 " . CFG_DBTBL_DICT_LANGUAGE . " AS d
		WHERE {$part_where_line}
		{$order_line}
	";

	$datapager->setTotal($db->query_total($sql));
	$datapager->getPagingParams($attributes);

	$dataSet = $db->get_all($sql . " " . $datapager->getLimitLine());


?>