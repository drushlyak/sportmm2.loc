<?php
$id = intval($attributes['id']);
$did = $attributes['did'];
$FORM_ERROR = "";
// Проверка доступа
if(!$auth_in->aclCheck($resourceId, DELETE)){
	$ACL_ERROR .= _(" У вас нет прав на удаление");
	Location(sprintf($_XFA['mainf'], ((strlen(trim($ACL_ERROR)) > 350) ? substr($ACL_ERROR, 0, 350)."..." : trim($ACL_ERROR))), 0);
	die;
}
//------------------------------------------------------------
function delElement($id)
{
	global $sTree, $lng, $FORM_ERROR;
	if($id){
		if($category = $sTree->getNode($id, array('title', 'quick_help'))){
			$lng->Deltext($category['title']);
			$lng->Deltext($category['quick_help']);
			$sTree->removeNode($id);
		}else{
			$FORM_ERROR .= _("Отсутствует запись раздела меню для id=") .$id;
		}
	}else{
		$FORM_ERROR .= _("Не определен id записи");
	}
	return true;
}
//-----------------------------------------------------
if(is_array($did)){
	foreach($did as $delel){
		delElement($delel);
	}
}elseif($id){
	delElement($id);
}
Location(sprintf($_XFA['mainf'], ((strlen(trim($FORM_ERROR)) > 350) ? substr($FORM_ERROR, 0, 350)."..." : trim($FORM_ERROR))), 0);

?>