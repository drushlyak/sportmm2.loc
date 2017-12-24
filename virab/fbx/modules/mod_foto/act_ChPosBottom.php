<?php
$id = intval($attributes['id']);
$node = $fotoTree->getNode($id, array('res_id'));
if(!$auth_in->aclCheck($node['res_id'], CHANGE_POSITION)){
	$ACL_ERROR .= _("У вас нет прав на изменение позиции");
	return false;
}
$fotoTree->swapSiblings($id, NSTREE_AXIS_FOLLOWING_SIBLING);
$sql = " 
	SELECT id 
	FROM ".$resTree->structTable." 
	WHERE data_id=?
";
$res_id =  $db->get_one($sql, $node['res_id']);
$resTree->swapSiblings($res_id, NSTREE_AXIS_FOLLOWING_SIBLING);
Location($_XFA['cat_main'], 0);

?>