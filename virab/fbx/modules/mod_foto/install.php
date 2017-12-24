<?php
//==================================================
function createResources()
{
	global $auth_in;
	$resTree = $auth_in->store->getResourceTree();
	$res_id = $auth_in->store->newResourceId();
	$top_id = $resTree->appendChild(1, array(), $res_id);
	return $top_id;
}
//========================================================
?>
