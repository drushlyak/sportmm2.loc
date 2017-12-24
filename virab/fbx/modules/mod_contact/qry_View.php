<?php
// Проверка доступа
if(!$auth_in->aclCheck($resourceId, VIEW)){
	$ACL_ERROR = _(" У вас нет прав на добавление в этот раздел");
	return;
}
$id  = intval($attributes['id']);
if($id){
	$sql = "
		SELECT *
		FROM ".CFG_DBTBL_MOD_CONTACT."
		WHERE id=?
	";
	$category = $db->get_row($sql, $id);
}else{
   Location($_XFA['main'], 0);   
}

?>