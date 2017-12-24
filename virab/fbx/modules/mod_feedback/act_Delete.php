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
		global $db, $lng, $FORM_ERROR, $resTree, $ACL_ERROR, $auth_in;
		
		if($id){
			$sql = sql_placeholder("SELECT * FROM " . CFG_DBTBL_MOD_FEEDBACK_GROUP . " WHERE id=?", $id);
			$node = $db->get_row($sql);
			
			if($node){
				// Проверка доступа
				if(!$auth_in->aclCheck($node['res_id'], DELETE)){
					$ACL_ERROR .= _(" У вас нет прав на удаление раздела с id=".$id);
					return false;
				}
				
				$sql = sql_placeholder("SELECT id FROM " . CFG_DBTBL_MOD_FEEDBACK_TEXT . " WHERE id_group=?", $id);
				$nodeSet = $db->get_all($sql);
				if(is_array($nodeSet)){
					$FORM_ERROR .= _("В разделе с id=".$id." есть отзывы. Перед тем, как удалять раздел, необходимо удалить все отзывы.");
					return false;
				}
				
				$lng->Deltext($node['name']);
				
				// Удаляем шаблонную переменную
				$sql = sql_placeholder("DELETE FROM " . CFG_DBTBL_TE_VALUE . " WHERE id=?", $node['id_te_value']);
				$db->query($sql);
				
				// Удаляем ресурс
//				$auth_in->acl->remove($node['res_id']);
				
				// Удаляем раздел из дерева
				$sql = sql_placeholder("DELETE FROM " . CFG_DBTBL_MOD_FEEDBACK_GROUP . " WHERE id=?", $id);
				$db->query($sql);
			}else{
				$FORM_ERROR .= _("Отсутствует запись раздела для id=".$id);
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