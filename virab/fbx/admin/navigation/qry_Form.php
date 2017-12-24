<?php
$id = intval($attributes['id']);
$typ = intval($attributes['typ']);

if($id){
	if($typ == 2){
		if(!$category = $sTree->getNode($id, array('title', 'url', 'quick_help', 'menu', 'edt'))){
			Location($_XFA['main'], 0);
		}
		$title = $category['title'];
		$category['title'] = $lng->Gettextlngall($category['title']);
		$category['quick_help'] = $lng->Gettextlngall($category['quick_help']);
	}
}
if($typ == 1){
	$parent_id = $id;
// Родительский узел
	$parent = &$sTree->select($parent_id, array('title'), NSTREE_AXIS_SELF);
}
// Проверка доступа
if($typ == 2){
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