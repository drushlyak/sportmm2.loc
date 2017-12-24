<?php

	/**
	 * @author: .ter (rou.terra@gmail.com)
	 * @copyright Cruiser (cruiser.com.ua)
	 */
	 
	require_once ("../../../conf/core.cfg.php");
	require_once (LIB_PATH . "/Common.php");

	$lastsOrders = $db->get_vector("
		SELECT id
			FROM " . CFG_DBTBL_MOD_ORDER . "
		ORDER BY id DESC
		LIMIT 10
	");
	$lastsOrders = is_array($lastsOrders) ? $lastsOrders : array();

	print join(",", $lastsOrders);