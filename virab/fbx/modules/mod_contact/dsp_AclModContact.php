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
	}
	return true;	
}
//-----------------------------------------------------------
?>