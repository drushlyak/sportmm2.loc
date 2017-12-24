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

	$data_array = $db->get_all_("
		SELECT 	  mp.id
				, mp.name AS value
				, mp.article AS article
				, MATCH(mp.name, mp.article) AGAINST('%" . $name . "%') AS relev
				, mp.cost_excess
				, mp.main_foto50 AS photo
			FROM " . CFG_DBTBL_MOD_PRODUCT . " AS mp
		WHERE mp.num_stock > 0 AND mp.name LIKE '%" . $name . "%'
		   OR mp.article LIKE '%" . $name . "%'
		ORDER BY relev DESC
		LIMIT {$limit}
	");

	$aResults = array();
	if (is_array($data_array)) {
		foreach ($data_array as $data) {
			$aResults[] = array( "id"=> $data['id'] ,"value"=>htmlspecialchars($data['value']), "info"=> "", "article" => htmlspecialchars($data['article']), "data" => $json->encode($data) );
		}
	}

	header("Content-Type: application/json");

	echo "{\"results\": [";
	$arr = array();
	for ($i=0;$i<count($aResults);$i++)
	{
		$arr[] = "{\"id\": \"" . $aResults[$i]['id'] . "\", \"value\": \"" . $aResults[$i]['value'] . " (" . $aResults[$i]['article'] . ")\", \"info\": \"\", \"data\": " . $aResults[$i]['data'] . " }";
	}
	echo implode(", ", $arr);
	echo "]}";

