<?php

$id = intval($attributes['idp']);
$id1 = ($attributes['id1']) ? intval($attributes['id1']) : $id1;
$id2 = ($attributes['id2']) ? intval($attributes['id2']) : $id2;
$typ = $attributes['typ'];
$parent = &$nsTree->select($id1, array('name'), NSTREE_AXIS_SELF);
$rsExect = &$cntTree->select($id2, array('name'), NSTREE_AXIS_SELF);
$exect = $rsExect[0];
if($typ == 2){
	$rsPages = $db->query("SELECT text FROM ".CFG_DBTBL_TE_EXECWCODE." WHERE id=".$id);
	$page = $rsPages->fetch_assoc();
	$page['text'] = $lng->Gettextlngall($page['text']);
}
// Проверка доступа
$parent_res = $nsTree->getNode($id1, array('res_id'));
if(($typ == 2) && ($id)){
	if(!$auth_in->aclCheck($parent_res['res_id'], EDIT)){
		$ACL_ERROR = _("У вас нет прав на редактирование");
		return;
	}
}else{
	if(!$auth_in->aclCheck($parent_res['res_id'], CREATE)){
		$ACL_ERROR = _("У вас нет прав на создание");
		return;
	}
}
?>