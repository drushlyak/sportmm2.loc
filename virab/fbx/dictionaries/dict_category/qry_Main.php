<?php
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _(" У вас нет прав на просмотр данного раздела");
		return;
	}

	$tableID = 't_dict_category_main_grid';

	$datapager = $dsp_helper->getDataPager(array('pageID' => 'dict_category.main'));

	$sql = "
		SELECT dc.*, dmc.name AS main_category
			FROM " . CFG_DBTBL_DICT_CATEGORY . " AS dc, " . CFG_DBTBL_DICT_MAIN_CATEGORY . " AS dmc
			WHERE dc.id_main_category = dmc.id
		ORDER BY dmc.ord, dc.ord
	";
//		
	$datapager->setTotal($db->get_one("
		SELECT COUNT(*)
			FROM " . CFG_DBTBL_DICT_CATEGORY . "
	"));
	$datapager->getPagingParams($attributes);

	$dataSet = $db->get_all($sql . " " . $datapager->getLimitLine());

