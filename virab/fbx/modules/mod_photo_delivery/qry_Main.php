<?php
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _(" У вас нет прав на просмотр данного раздела");
		return;
	}

	$tableID = 't_mod_photo_delivery_grid';

	// сортировка =================================================================

	$order_line = $data_helper->getOrderLine($tableID, array(
		'name' => 'mpd.name',
		'add_date' => 'mpd.add_date'
		), "ORDER BY mpd.id DESC");

	// фильтрация =================================================================

	$part_where_line = $data_helper->getWhereLine(
		$tableID
		, $attributes
		, array(
			'name' => "mpd.name LIKE '%*%'"
		)
		, "AND"
		, ""
	);

	// =================================================================

	$datapager = $dsp_helper->getDataPager(array('pageID' => 'mod_photo_delivery.main'));

	$sql = "
		SELECT mpd.*
			 , (SELECT mp.name FROM " . CFG_DBTBL_MOD_PHOTO_DELIVERY_PRODUCT . " AS mpdp, " . CFG_DBTBL_MOD_PRODUCT . " AS mp WHERE mp.id = mpdp.id_product AND mpdp.id_photo_delivery = mpd.id ORDER BY mpdp.id LIMIT 1) AS product
			 , (SELECT mp.article FROM " . CFG_DBTBL_MOD_PHOTO_DELIVERY_PRODUCT . " AS mpdp, " . CFG_DBTBL_MOD_PRODUCT . " AS mp WHERE mp.id = mpdp.id_product AND mpdp.id_photo_delivery = mpd.id ORDER BY mpdp.id LIMIT 1) AS product_art
			 , (SELECT mp.main_foto50 FROM " . CFG_DBTBL_MOD_PHOTO_DELIVERY_PRODUCT . " AS mpdp, " . CFG_DBTBL_MOD_PRODUCT . " AS mp WHERE mp.id = mpdp.id_product AND mpdp.id_photo_delivery = mpd.id ORDER BY mpdp.id LIMIT 1) AS product_photo
			FROM " . CFG_DBTBL_MOD_PHOTO_DELIVERY . " AS mpd
			  {$part_where_line}
		{$order_line}
	";

	$datapager->setTotal($db->get_one("
		SELECT COUNT(*)
			FROM " . CFG_DBTBL_MOD_PHOTO_DELIVERY . " AS mpd
			  {$part_where_line}
	"));
	$datapager->getPagingParams($attributes);

	$dataSet = $db->get_all($sql . " " . $datapager->getLimitLine());

?>