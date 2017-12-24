<?php

$id = intval($attributes['idp']);
$id1 = intval($attributes['id1']);
$id2 = intval($attributes['id2']);
// Проверка доступа
$node = $nsTree->getNode($id1, array('res_id'));
if(!$auth_in->aclCheck($node['res_id'], CHANGE_POSITION)){
	$ACL_ERROR .= _("У вас нет прав на изменение позиции");
	return false;
}     
$rsPages = $db->query("SELECT page FROM ".CFG_DBTBL_TE_EXECWCODE." WHERE id=".$id);
$page = $rsPages->fetch_assoc();
$pg1 = $page['page'];
$pg2 = ($pg1 <= 1) ? 1 : $pg1-1;
$rsPages = $db->query("SELECT id FROM ".CFG_DBTBL_TE_EXECWCODE." WHERE id_executor=$id2 AND id_map=$id1 AND page=".$pg2);
$page = $rsPages->fetch_assoc();
$rsPages = $db->query("UPDATE ".CFG_DBTBL_TE_EXECWCODE." SET page=$pg2 WHERE id=".$id);
$rsPages = $db->query("UPDATE ".CFG_DBTBL_TE_EXECWCODE." SET page=$pg1 WHERE id=".$page['id']);
Location(sprintf($_XFA['mainbig'], $id1, $id2), 0);

?>