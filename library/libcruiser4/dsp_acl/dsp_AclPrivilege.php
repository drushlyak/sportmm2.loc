<?php
function createDisplayAcl($nodeSet) {
	global $db, $resTree, $configTable, $top_id, $res;
	
	if ($nodeSet){
		foreach($nodeSet as &$node){
			if($node['id'] == $top_id){
				$node['name'] = $res['name'];
				continue;
			}
		}
	}
}
?>