<?php
// Проверка доступа
if(!$auth_in->aclCheck($resourceId, CHANGE_POSITION)){
	$ACL_ERROR .= _("У вас нет прав на изменение позиции");
	return false;
}
$id = intval($attributes['id']);
$sTree->swapSiblings($id, NSTREE_AXIS_FOLLOWING_SIBLING);
Location($_XFA['main'], 0);

?>
