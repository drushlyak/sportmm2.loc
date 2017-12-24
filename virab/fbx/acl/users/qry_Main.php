<?php

	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _("У вас нет прав на просмотр");
		return;
	}

	$tableID = 't_users';
	$configTables = $auth_in->store->getConfig();


	// сортировка =================================================================

	$order_line = $data_helper->getOrderLine($tableID, array(
		'login' => 'u.login',
		'role' => 'u.role_id'
	), "ORDER BY u.id ASC");

	$datapager = $dsp_helper->getDataPager(array('pageID' => 'users.main'));

	$sql = "
		SELECT u.*
			 , r.name AS role_name
		FROM " . CFG_DBTBL_UDATA . " AS u
			 JOIN " . $configTables['roleTable'] . " AS r ON r.id = u.role_id
		{$order_line}
	";

	$datapager->setTotal($db->query_total($sql));
	$datapager->getPagingParams($attributes);

	$dataSet = $db->get_all($sql . " " . $datapager->getLimitLine());

?>