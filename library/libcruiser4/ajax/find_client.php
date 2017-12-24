<?php

	/**
	 * @author: .ter (rou.terra@gmail.com)
	 * @copyright Cruiser (cruiser.com.ua)
	 */

	require_once ("../../../conf/core.cfg.php");
	require_once (LIB_PATH . "/Common.php");
	require_once (LIB_PATH . "/ajax/JSON.php");

	$name = $_REQUEST['name'];
	$limit = (int) $_REQUEST['limit'];

	$json = new Services_JSON();

	$data_array = $db->get_all("
		SELECT 	  mp.id
				, CONCAT(mp.f_name, ' ',mp.i_name, ' ', ' / ', mp.`phone`, ' /') AS value
				, MATCH(mp.i_name, mp.f_name, mp.phone) AGAINST('%" . $name . "%') AS relev
			FROM " . CFG_DBTBL_MOD_CLIENT . " AS mp
		WHERE mp.`i_name` LIKE '%" . $name . "%'
		   OR mp.`f_name` LIKE '%" . $name . "%'
		   OR mp.`phone` LIKE '%" . $name . "%'
		ORDER BY relev DESC
        LIMIT {$limit}
	");

	$aResults = array();
	if (is_array($data_array)) {
		foreach ($data_array as $data) {
			$clientContacts = getClientRecipients($data['id']);

			$aResults[] = array( "id"=> $data['id'] ,"value"=>htmlspecialchars($data['value']), "info"=> "", "data" => $json->encode($clientContacts) );
		}
	}

	header("Content-Type: application/json");

	echo "{\"results\": [";
	$arr = array();
	for ($i = 0; $i < count($aResults); $i++) {
		$arr[] = "{\"id\": \"" . $aResults[$i]['id'] . "\", \"value\": \"" . $aResults[$i]['value'] . "\", \"info\": \"\", \"data\": " . $aResults[$i]['data'] . " }";
	}
	echo implode(", ", $arr);
	echo "]}";

?>
