<?php

$id = intval($attributes['id']);
$node = $nsTree->getNode($id, array('res_id'));
if(!$auth_in->aclCheck($node['res_id'], CHANGE_PARENT)){
	$ACL_ERROR = _("У вас нет прав на изменение родителя");
	return;
}

// Прочитаем список всех узлов
$nodeSet = $nsTree->selectNodes(0, 0, array('name'));
// Прочитаем список дочерних узлов к этому, чтоб их исключить
$exclude_nodeSet = &$nsTree->selectNodes($id, 0, array('name'));
// Определим родителский элемент который является текущим
$parentNode = $nsTree->getParentNode($id);

?>