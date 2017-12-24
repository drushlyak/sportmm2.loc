<?php

$resTree = $auth_in->store->getResourceTree();
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
//---------------------------------------------------------------	
function del($id)
{
	global $db, $lng, $fotoTree, $resTree;
	if($id){
		$sql = "
			SELECT * 
			FROM ".CFG_DBTBL_MOD_FOTO." 
			WHERE id_fotogr=?
		";
		$fotoSet = $db->get_all($sql, $id);
		if($fotoSet){
			foreach($fotoSet as $foto){
// Удаляем языковые конструкции для текущей фотографии
				$lng->Deltext($foto['name']);
				$lng->Deltext($foto['description']);
// Удаляем файлы
				if($foto['url']){
					@unlink(BASE_PATH.$node['url']."_n.".$node['exten']);
					@unlink(BASE_PATH.$node['url']."_s.".$node['exten']);
					@unlink(BASE_PATH.$node['url']."_o.".$node['exten']);
				}
// Удаляем фото 
				$sql = "
						DELETE
						FROM ".CFG_DBTBL_MOD_FOTO." WHERE id=?
					";
					$db->query($sql, $foto['id']);
				}
			}
	}
	return true;
}
//---------------------------------------------------------------
// Выбираем все разделы фотографий
$nodeSet = $fotoTree->selectNodes(0, 0, 
	array(
		'name', 
		'id_te_value', 
		'description', 
		'res_id'
	)
);
if($nodeSet){
	foreach($nodeSet as $node){
		if($node['res_id']){
			del($node['id']);
// Удаление языковых конструкций для текущего раздела фотографий
			$lng->Deltext($node['name']);
			$lng->Deltext($node['description']);
		}
    }
}

?>