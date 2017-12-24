<?php
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, DELETE)){
		$ACL_ERROR = _("У вас нет прав на удаление");
		return;
	}
	$id  = (int) $attributes['id'];
	$did = $attributes['did'];
	$FORM_ERROR = "";
	
	//----------------------------------------------------------------
	function delElement($id) {
		global $db, $lng, $FORM_ERROR, $fotoTree, $ACL_ERROR, $auth_in;
		
		if($id){
			$resTree = $auth_in->store->getResourceTree();
			$sql = sql_placeholder("SELECT * FROM ".CFG_DBTBL_FOTO." WHERE id_fotogr=?", $id);
			$rsNodes = $db->query($sql);
			if(!$rsNodes->num_rows && $info = $fotoTree->getNodeInfo($id)){
				$category = $fotoTree->getNode($info['id'], array('name', 'description', 'id_te_value',  'res_id'));
			// Проверка доступа
				if(!$auth_in->aclCheck($category['res_id'], DELETE)){
					$ACL_ERROR .= _(" У вас нет прав на удаление раздела с id=".$id);
					return false;
				}
				$lng->Deltext($category['name']);
				$lng->Deltext($category['description']);
				$sql = "DELETE FROM ".CFG_DBTBL_TE_VALUE." WHERE id=?";
				$db->query($sql, $category['id_te_value']);
	// Выбираем id раздела в дереве ресурсов
				$sql = " 
					SELECT id 
					FROM ".$resTree->structTable." 
					WHERE data_id=?
				";
				$res_id = $db->get_one($sql, $category['res_id']);
				if($res_id){
					$resTree->removeNodes($res_id, true);
				}
				$fotoTree->removeNodes($id, true);
			}else{
				$FORM_ERROR .= _("Отсутствует запись раздела для id=".$id." Либо в разделе есть фотографии.");
			}
		}else{
			$FORM_ERROR .= _("Неопределен идентификатор записи");
		}
		return true;
	}
	//---------------------------------------------------------------------    
	if(is_array($did)){
		foreach($did as $delel){
			delElement($delel);
		}
	}elseif($id){
		delElement($id);
	}
	Location(sprintf($_XFA['cat_mainf'], ((strlen(trim($FORM_ERROR." ".$ACL_ERROR)) > 350) ? substr($FORM_ERROR, 0, 350)."..." : trim($FORM_ERROR))), 0);

?>