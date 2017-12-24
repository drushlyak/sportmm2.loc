<?php	
//--------------------------------------------------
function createDisplayAcl($nodeSet)
{
	global $db, $top_id, $res;
	
	foreach($nodeSet as &$node){
		if($node['id'] == $top_id){
			$node['name'] = $res['name'];
			continue;
		}
		$sql = sql_placeholder("
			SELECT name 
			FROM ".CFG_DBTBL_MOD_FEEDBACK_GROUP." 
			WHERE res_id=?", 
			$node['data_id']
		);
		$name = $db->get_one($sql);
		if($name){
			$node['name'] = $name;
		}
	}
	return true;	
}
//-----------------------------------------------------------
?>