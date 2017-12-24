<?
$id = intval($attributes['id']);
if($id){
	$sql = sql_placeholder("
		SELECT *
		FROM ".CFG_DBTBL_TE_VALUE."
		WHERE id=?",
		$id
	);
	$category = $db->get_row($sql);
	if(!$category){
		Location($_XFA['main'], 0);
	}else{
		$category['description'] = $lng->Gettextlngall($category['description']);
	}
}
// Проверка доступа
if($id){
	if(!$auth_in->aclCheck($resourceId, EDIT)){
		$ACL_ERROR = _("У вас нет прав на редактирование");
		return;
	}
}else{
	if(!$auth_in->aclCheck($resourceId, CREATE)){
		$ACL_ERROR = _(" У вас нет прав на добавление");
		return;
	}
}
?>