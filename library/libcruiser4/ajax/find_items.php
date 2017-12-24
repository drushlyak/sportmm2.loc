<?php

	/**
	 * @author: .ter (rou.terra@gmail.com)
	 * @copyright Cruiser (cruiser.com.ua)
	 */

	require_once ("../../../conf/core.cfg.php");
	require_once (LIB_PATH . "/Common.php");

	$name = $_REQUEST['name'];
	$limit = (int) $_REQUEST['limit'];

	$data_array = $db->get_all("
		SELECT 	  mu.id
				, mu.name AS value
				, mu.article AS article
				, MATCH(mu.name) AGAINST('%" . $name . "%') AS relev
			FROM " . CFG_DBTBL_MOD_UNIT . " AS mu
		WHERE mu.name LIKE '%" . $name . "%'
		ORDER BY relev DESC
		LIMIT {$limit}
	");

	$aResults = array();
	if (is_array($data_array)) {
		foreach ($data_array as $data) {
			$aResults[] = array( "id"=> $data['id'] ,"value"=>htmlspecialchars($data['value']), "info"=> "", "article" => htmlspecialchars($data['article']) );
		}
	}

	header("Content-Type: application/json");

	echo "{\"results\": [";
	$arr = array();
	for ($i=0;$i<count($aResults);$i++)
	{
		$arr[] = "{\"id\": \"" . $aResults[$i]['id'] . "\", \"value\": \"" . $aResults[$i]['value'] . " (" . $aResults[$i]['article'] . ")\", \"info\": \"\"}";
	}
	echo implode(", ", $arr);
	echo "]}";

?>
