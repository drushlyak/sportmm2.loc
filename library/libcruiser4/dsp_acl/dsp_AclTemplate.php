<?php	
//--------------------------------------------------
function createDisplayAcl($nodeSet)
{
	global $db, $nsTree, $resTree, $top_id, $res;
	
	if($nodeSet){
		foreach($nodeSet as &$node){
			if($node['id'] == $top_id){
				$node['name'] = $res['name'];
				continue;
			}
			$tNode = $resTree->getNodeInfo($node['id']);
			$sql = sql_placeholder("
				SELECT name FROM ".CFG_DBTBL_TE_CONTDATA." 
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