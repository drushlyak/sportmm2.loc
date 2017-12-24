<?php	
//--------------------------------------------------
function createDisplayAcl($nodeSet)
{
	global $db, $sTree, $resTree, $top_id, $res;
	if($nodeSet){
		foreach($nodeSet as &$node){
			if($node['id'] == $top_id){
				$node['name'] = $res['name'];
				continue;
			}
			$tNode = $resTree->getNodeInfo($node['id']);
			$sql = sql_placeholder("
				SELECT title 
				FROM ".$sTree->dataTable." 
				WHERE res_id=?", 
				$tNode['data_id']
			);
			$name = $db->get_one($sql);
			if($name){
				$node['name'] = $name;
			}
		}
	}
}
//-----------------------------------------------------------
?>