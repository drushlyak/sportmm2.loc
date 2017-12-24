<?php

$id  = intval($attributes['id']);
$id1 = intval($attributes['id1']);
$typ = intval($attributes['typ']);
// Проверка доступа
$node =  $cntTree->getNode($id1, array('name','res_id', 'type_executor'));
if(($typ == 2) && ($id)){
	if(!$auth_in->aclCheck($node['res_id'], EDIT)){
		$ACL_ERROR = _("У вас нет прав на редактирование");
		return;
	}
}else{
	if(!$auth_in->aclCheck($node['res_id'], CREATE)){
		$ACL_ERROR = _("У вас нет прав на создание");
		return;
	}
}
// WYSIWYG коды для данного исполнителя
if($node['type_executor'] == TE_EXECUTOR_SCREEN_WYSIWYG){
	$type_executor = TE_EXECUTOR_SCREEN_WYSIWYG;
	$tbl = CFG_DBTBL_TE_EXECWCODE;
}else{
	$tbl = CFG_DBTBL_TE_EXECSCODE;
	$type_executor = TE_EXECUTOR_SIMPLE;
}
if($id){
	$sql = sql_placeholder("
		SELECT id_map
		FROM ".$tbl."
		WHERE id=?",
		$id
	);
	$page = $db->get_one($sql);
	$sql = sql_placeholder("
		SELECT *
		FROM ".CFG_DBTBL_TE_EXECWCODE."
		WHERE id_executor = ?
			AND id_map = ?",
		$id1, $page
	);
	$pageSet = $db->get_all($sql);
}else{

}
// Выбираем все странцы
$tmpSet = $nsTree->select(0, array('name', 'id_contaner'), NSTREE_AXIS_DESCENDANT);
// Строим дерево id-шников.. содержащих текущий элемент
$parentSet = $cntTree->select($id1, array(), NSTREE_AXIS_ANCESTOR_OR_SELF);
$parents = array();
if(is_array($parentSet)){
	foreach($parentSet as $parent){
		$parents[] = $parent['id'];
	}
}
$nsSet = array();
if(is_array($tmpSet)){
	foreach($tmpSet as $tmp){
		if(in_array($tmp['id_contaner'], $parents)){
			$nsSet[] = $tmp;
		}
	}
}
?>