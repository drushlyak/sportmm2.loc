<?php
	$id   	= (int)$attributes['id'];
	$id_feedback  = (int) $attributes['id_feedback'];
	$did 	= $attributes['did'];
	
	$FORM_ERROR = "";
	
	// Проверка доступа
	$sql = sql_placeholder("SELECT res_id FROM ".CFG_DBTBL_MOD_FEEDBACK_GROUP." WHERE id=?", $id_feedback);
	$res_id = $db->get_one($sql);
	if(!$auth_in->aclCheck($res_id, DELETE)){
		$ACL_ERROR .= _(" У вас нет прав на удаление вопроса в разделе с id = ".$id);
		Location(sprintf($_XFA['mainf'], $id_feedback, ((strlen(trim($ACL_ERROR)) > 350) ? substr($ACL_ERROR, 0, 350)."..." : trim($ACL_ERROR))), 0);
		return;
	}
	
	//--------------------------------------------------------------------
	function delElement($id) {
		global $lng, $FORM_ERROR, $db;
		if ($id) {
			$sql = sql_placeholder("SELECT * FROM ".CFG_DBTBL_MOD_FEEDBACK_TEXT." WHERE id=?", $id);
			$node = $db->get_row($sql);
			if(is_array($node)){
				$lng->Deltext($node['text']);
				$lng->Deltext($node['author_name']);
				$sql = sql_placeholder("DELETE FROM ".CFG_DBTBL_MOD_FEEDBACK_TEXT." WHERE id=?", $id);
				$db->query($sql);
			}
		} else {
			$FORM_ERROR .= _("Неопределен идентификатор записи");
		}
		return true;
	}
	//---------------------------------------------------------
	
	if(is_array($did)){
		foreach ($did as $delel){
			delElement($delel);
		}
	}elseif($id){
		delElement($id);
	}
	Location(sprintf($_XFA['mainf'], $id_feedback, ((strlen(trim($FORM_ERROR)) > 350) ? substr($FORM_ERROR, 0, 350)."..." : trim($FORM_ERROR))), 0);

?>