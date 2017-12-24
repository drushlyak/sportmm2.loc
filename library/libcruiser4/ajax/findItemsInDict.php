<?php

	/**
	 * @author: .ter (rou.terra@gmail.com)
	 * @copyright Cruiser (cruiser.com.ua)
	 */

	require_once ("../../../conf/core.cfg.php");
	require_once (LIB_PATH . "/Common.php");
	require_once (LIB_PATH . "/ajax/JSON.php");

	$search = $_REQUEST['search'];
	$table = sql_placeholder($_REQUEST['table']);
	$noLng = (int) $_REQUEST['noLng'];

	$limit = (int) $_REQUEST['limit'];

	$data_array = $db->get_all("
		SELECT 	  t.id
				, t.name AS value
				, '' AS info
				, MATCH(t.name) AGAINST('%" . $search . "%') AS r
			FROM {$table} AS t
		WHERE t.name LIKE '%" . $search . "%'
		ORDER BY r DESC
		LIMIT {$limit}
	");

	$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);

	header("Content-Type: application/json");
	echo $json->encode(array( 'results' => $data_array ));
?>