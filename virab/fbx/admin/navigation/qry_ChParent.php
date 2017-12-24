<?php
if(!$auth_in->aclCheck($resourceId, CHANGE_PARENT)){
	$ACL_ERROR = _("У вас нет прав на изменение родителя");
	return;
}
$id = intval($attributes['id']);
// Текущий узел
$category = &$sTree->select($id, array('title'), NSTREE_AXIS_SELF);
$nodeSet = &$sTree->selectTNodes(0, array('title'));
$exclude_nodeSet = &$sTree->selectTNodes($id, array());
$parentNode = $sTree->getParentNode($id);
$p_id = $parentNode['id'];

?>
