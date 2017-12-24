<?php
//--------------------------------------------------
function createDisplayAcl($nodeSet)
{
	global $db, $top_id, $res;
	$fotoTree = new NSTree(
 		CFG_DBTBL_MOD_FOTO_GRTREE,
 		CFG_DBTBL_MOD_FOTO_GRDATA,
 		array(
  			'id'      => TREE_STRUCT_ID,
  			'data_id' => TREE_STRUCT_DATA_ID,
  			'left'    => TREE_STRUCT_LEFT,
  			'right'   => TREE_STRUCT_RIGHT,
  			'level'   => TREE_STRUCT_LEVEL
 		)
	);
	foreach($nodeSet as &$node){
		if($node['id'] == $top_id){
			$node['name'] = $res['name'];
			continue;
		}
		$sql = sql_placeholder("
			SELECT name
			FROM ".$fotoTree->dataTable."
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