<?php
// Проверка доступа
if(!$auth_in->aclCheck($resourceId, DELETE)){
	$ACL_ERROR .= _("У вас нет прав на удаление");
	Location(sprintf($_XFA['mainf'], ((strlen(trim($ACL_ERROR)) > 350) ? substr($ACL_ERROR, 0, 350)."..." : trim($ACL_ERROR))), 0);
	die;
}
$id = intval($attributes['id']);
$did = $attributes['did'];
$FORM_ERROR = "";
//--------------------------------------------------------------
function delElement($id)
{
	global $db, $lng, $FORM_ERROR;
	if($id){
		$db->query("
			DELETE
			FROM ".CFG_DBTBL_MOD_CONTACT." 
			WHERE id=?", 
			$id
		);
	}else{
		$FORM_ERROR .= _("Неопределен идентификатор записи");
	}
	return true;
}
//---------------------------------------------------------------    
if(is_array($did)){
	foreach($did as $delel){
		delElement($delel);
	}
}elseif($id){
	delElement($id);
}
Location(sprintf($_XFA['mainf'], ((strlen(trim($FORM_ERROR)) > 350) ? substr($FORM_ERROR, 0, 350)."..." : trim($FORM_ERROR))), 0);

?>