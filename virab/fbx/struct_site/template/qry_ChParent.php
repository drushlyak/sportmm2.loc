<?php

$id = intval($attributes['id']);
$node = $cntTree->getNode($id, array('res_id'));
if(!$auth_in->aclCheck($node['res_id'], CHANGE_PARENT)){
	$ACL_ERROR = _("У вас нет прав на изменение родителя");
	return;
}
// Прочитаем список всех узлов
$tmpSet = $cntTree->selectNodes(0, 0, array('name', 'type_template'));
// Прочитаем список дочерних узлов к этому, чтоб их исключить
$exclude_nodeSet = $cntTree->selectNodes($id, 0, array('name'));
// Определим родителский элемент который является текущим
$parentNode = $cntTree->getParentNode($id);
$nodeSet = array();
if(is_array($tmpSet)){
	foreach($tmpSet as $tmp){
		if($tmp['type_template'] == TE_VALUE_FOLDER ){
			$nodeSet[] = $tmp;
	 	}
	}
}
?>