<?php

	/**
	 * @author: .ter (rou.terra@gmail.com)
	 * @copyright Cruiser (cruiser.com.ua)
	 */

	require_once ("../../../conf/core.cfg.php");
	require_once (LIB_PATH . "/Common.php");
	require_once (LIB_PATH . "/ajax/JSON.php");

	$number = $_REQUEST['number'];
	$limit = (int) $_REQUEST['limit'];

	$json = new Services_JSON();

	$db->query("CREATE TEMPORARY TABLE _msgid AS SELECT moai.id_order AS id, moai.number AS number FROM " . CFG_DBTBL_MOD_ORDER_ADDITIONAL_INFO . " AS moai");
	$db->query("ALTER TABLE _msgid MODIFY COLUMN `number` TEXT");
	$db->query("ALTER TABLE _msgid ADD FULLTEXT INDEX `ix__number_ft`(`number`)");
	$data_array = $db->get_all_("
		SELECT 	  id AS id
				, number AS value
				, MATCH(number) AGAINST('%" . $number . "%') AS relev
			FROM _msgid
		WHERE number LIKE '%" . $number . "%'
		ORDER BY relev DESC
		LIMIT {$limit}
	");

	$aResults = array();
	if (is_array($data_array)) {
		foreach ($data_array as $data) {
			$aResults[] = array( "id"=> $data['id'] ,"value"=>htmlspecialchars($data['value']), "info"=> "", "article" => "", "data" => $json->encode($data) );
		}
	}

	header("Content-Type: application/json");

	echo "{\"results\": [";
	$arr = array();
	for ($i=0;$i<count($aResults);$i++)
	{
		$arr[] = "{\"id\": \"" . $aResults[$i]['id'] . "\", \"value\": \"" . $aResults[$i]['value'] . "\", \"info\": \"\", \"data\": " . $aResults[$i]['data'] . " }";
	}
	echo implode(", ", $arr);
	echo "]}";

