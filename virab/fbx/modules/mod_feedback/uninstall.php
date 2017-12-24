<?php
$resTree = $auth_in->store->getResourceTree();
//---------------------------------------------------------------	
function del_group($id)
{
	global $db, $lng, $FORM_ERROR, $resTree, $ACL_ERROR, $auth_in;
	if($id){
		$sql = "
			SELECT *
			FROM ".CFG_DBTBL_MOD_FEEDBACK_GROUP." 
			WHERE id=?
		";
		$node = $db->get_row($sql, $id);
		if($node){
			$sql = "
				SELECT id
				FROM ".CFG_DBTBL_MOD_FEEDBACK_TEXT." 
				WHERE id_group=?
			";
			$nodeSet = $db->get_all($sql, $id);
			if(is_array($nodeSet)){
				foreach($nodeSet as $cat){
					$lng->Deltext($cat['text']);
				}
			}
			$lng->Deltext($node['name']);
			$sql = sql_placeholder("
				DELETE 
				FROM ".CFG_DBTBL_TE_VALUE." 
				WHERE 
				id=?", 
				$node['id_te_value']
			);
			$db->query($sql);
// Удаляем ресурс
			$auth_in->acl->remove($node['res_id']);
// Удаляем раздел из дерева
			$sql = "
				DELETE
				FROM ".CFG_DBTBL_MOD_FEEDBACK_GROUP."
				WHERE id=?
			";
			$db->query($sql, $id);
		}
	}
	return true;
}

function del_txt($id)
{
	global $lng, $FORM_ERROR, $db;
	if($id){
		$sql = sql_placeholder("
			SELECT * 
			FROM ".CFG_DBTBL_MOD_FEEDBACK_TEXT." 
			WHERE id=?", 
			$id
		);
		$node = $db->get_row($sql);
		if(is_array($node)){
			$lng->Deltext($node['text']);
			$sql = sql_placeholder("
				DELETE 
				FROM ".CFG_DBTBL_MOD_FEEDBACK_TEXT." 
				WHERE id=?", 
				$id
			);
			$db->query($sql);
		}
	}else{
		$FORM_ERROR .= _("Неопределен идентификатор записи");
	}
	return true;
}

//---------------------------------------------------------------
// Выбираем все разделы вопросов
$sql = "SELECT * FROM " . CFG_DBTBL_MOD_FEEDBACK_GROUP;
$nodeSet = $db->get_all($sql);
if(is_array($nodeSet)){
	foreach($nodeSet as $node){
		// Выбираем все вопросы раздела
		$sql = sql_placeholder("SELECT * FROM " . CFG_DBTBL_MOD_FEEDBACK_TEXT . " WHERE group_id = ?", $node['id']);
		$txtnodeSet = $db->get_all($sql);
		if(is_array($txtnodeSet)){
			foreach($txtnodeSet as $txtnode){
				// Удаление языковых конструкций для текущего вопроса
				$lng->Deltext($txtnode['text']);
				
				del_txt($txtnode['id']);
			}
		}
		
		// Удаление языковых конструкций для текущего раздела 
		$lng->Deltext($node['name']);
		
		del_group($node['id']);
    }
}

?>