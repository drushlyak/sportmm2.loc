<?php

$typ = intval($attributes['typ']);
$id =   $id = intval($attributes['id']);
if($attributes['id']){
	$parent = $mnTree->select($id, array('name'), NSTREE_AXIS_SELF);
	if($typ == 2){
		if($info = $mnTree->getNodeInfo($id)){
			$category = $mnTree->getNode($info['id'], array('name', 'id_te_value', 'id_node', 'url', 'template_id'));
			$name = $category['name'];
			$category['name'] = $lng->Gettextlngall($category['name']);
		}else{
			Location($_XFA['main'], 0);
		}
	}else{
		$parent_id = $id;
	}
}
// Список доступных языков
$rsLang = $db->query(sql_placeholder("SELECT language_id FROM ".CFG_DBTBL_PAGELNG." WHERE page_id=? AND page_menu=?", $id, ACC_LNG_MENU));
if($rsLang->num_rows){
	while($lang = $rsLang->fetch_assoc()){
		$lngSet[] = $lang['language_id'];
	}
}
// Прочитаем список всех узлов
$nodeSet = &$nsTree->selectNodes(0, 0, array('name'));
// Список шаблонов
$conteinerSet = &$cntTree->select(0, array('name'), NSTREE_AXIS_CHILD);
// Проверка доступа
if($typ == 2){
	if(!$auth_in->aclCheck($resourceId, EDIT)){
		$ACL_ERROR = _("У вас нет прав на редактирование");
		return;
	}
}else{
	if(!$auth_in->aclCheck($resourceId, CREATE)){
		$ACL_ERROR = _("У вас нет прав на добавление");
		return;
	}
}
?>