<?php
//------------------------------------------------
function createResources()
{
	global $store, $db;
	$resTree = $store->getResourceTree();
	$resTree->clear();
}
//------------------------------------------------
function createSysModule($module_name)
{
	global $auth_in, $db, $lng;

	$resTree = $auth_in->store->getResourceTree();
	$id = $auth_in->store->newResourceId();
	$top_id = $resTree->appendChild(1, array(), $id);
	$lng_id = $lng->NewId();
	$text = array();
	$text['msgid'] = $lng_id;
	$text[1] = "module_name"; 
	$lng->SetTextlng($text);
	$sql = sql_placeholder("
		INSERT 
		INTO ".CFG_DBTBL_MODULE." 
		SET 
			var=?, 
			name=?, 
			top_id=?, 
			enabled=1, 
			sys=1",
		$module_name, $lng_id, $top_id
	);
	$db->query($sql);
}
//------------------------------------------------
function removeContNode($id)
{
	global $cntTree;
	$cntTree->removeNodes($id);
}
//------------------------------------------------
?>