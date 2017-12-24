<?php
$id = intval($attributes['id']);
$id1 = intval($attributes['id1']);
$id2 = intval($attributes['id2']);
$did = $attributes['did'];
$FORM_ERROR = "";
// Проверка доступа
$node = $nsTree->getNode($id1, array('res_id'));
if(!$auth_in->aclCheck($node['res_id'], DELETE)){
	$ACL_ERROR .= _(" У вас нет прав на удаление");
	Location(sprintf($_XFA['mainbigf'], $id1, $id2, ((strlen(trim($ACL_ERROR)) > 350) ? substr($ACL_ERROR, 0, 350)."..." : trim($ACL_ERROR))), 0);
	die;
}
//-----------------------------------------------------------------------
function delElement($id)
{
	global $db, $lng, $FORM_ERROR;
	if($id){
		$sql = sql_placeholder("
			SELECT *
			FROM ".CFG_DBTBL_TE_EXECWCODE."
			WHERE id=?",
			$id
		);
		$node = $db->get_row($sql);
		if($node){
			$lng->Deltext($node['text']);
			$sql = sql_placeholder("
				DELETE
				FROM ".CFG_DBTBL_TE_EXECWCODE."
				WHERE id=?",
				$id
			);
			$db->query($sql);
		}else{
			$FORM_ERROR .= _("Отсутствует запись для id=") . $id;
		}
	}else{
		$FORM_ERROR .= _("Не определен id записи");
	}
	return true;
}
//----------------------------------------------------------------------
if(is_array($did)){
	foreach($did as $delel){
		delElement($delel);
	}
}elseif($id){
	delElement($id);
}
Location(sprintf($_XFA['mainbigf'], $id1, $id2, ((strlen(trim($FORM_ERROR)) > 350) ? substr($FORM_ERROR, 0, 350)."..." : trim($FORM_ERROR))), 0);

?>