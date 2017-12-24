<?php

	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _("У вас нет прав на просмотр");
		return;
	}

	$tableID = 't_virab_config';

	// сортировка =================================================================

	$order_line = $data_helper->getOrderLine($tableID, array(
		'var' => 'c.config_name'
	), "ORDER BY c.id DESC");

	// =================================================================

	$datapager = $dsp_helper->getDataPager(array('pageID' => $tableID));

	$sql = "
		SELECT *
		FROM " . CFG_DBTBL_CONFIG . " AS c
		{$order_line}
	";

	$datapager->setTotal($db->query_total($sql));
	$datapager->getPagingParams($attributes);

	$dataSet = $db->get_all($sql . " " . $datapager->getLimitLine());

?>