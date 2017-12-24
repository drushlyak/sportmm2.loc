<?php
// @FIXME: Перестроить дерево ресурсов
// Проверка доступа
$node = $mnTree->getNode($id, array('res_id'));
if(!$auth_in->aclCheck($node['res_id'], CHANGE_POSITION)){
	$ACL_ERROR .= _("У вас нет прав на изменение позиции");
	return false;
}
$id = intval($attributes['id']);
$mnTree->swapSiblings($id, NSTREE_AXIS_PRECENDING_SIBLING);
$sql = " 
	SELECT id 
	FROM ".$resTree->structTable." 
	WHERE data_id=?
";
$res_id =  $db->get_one($sql, $node['res_id']);
$resTree->swapSiblings($res_id, NSTREE_AXIS_PRECENDING_SIBLING);

Location($_XFA['main'], 0);

?>