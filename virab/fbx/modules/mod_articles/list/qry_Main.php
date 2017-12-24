<?php
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _(" У вас нет прав на просмотр данного раздела");
		return;
	}

	$id_category = (int) $attributes['id_category'];

	$datapager = $dsp_helper->getDataPager(array('pageID' => 'mod_articles.main'));

	$sql = sql_placeholder("
		SELECT mc.*
		FROM " . CFG_DBTBL_MOD_ARTICLES . " AS mc
			WHERE id_category = ?
	", $id_category);
			//echo $sql;

	$datapager->setTotal($db->query_total($sql));
	$datapager->getPagingParams($attributes);

	$dataSet = $db->get_all($sql . " " . $datapager->getLimitLine());
?>