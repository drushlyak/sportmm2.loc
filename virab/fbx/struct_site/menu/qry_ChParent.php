<?php
// Проверка прав доступа
$node = $mnTree->getNode($id, array('res_id'));
if(!$auth_in->aclCheck($node['res_id'], CHANGE_PARENT)){
	$ACL_ERROR = _("У вас нет прав на изменение родителя");
	return;
}

$id = intval($attributes['id']);
// Прочитаем список всех узлов
$nodeSet = &$mnTree->selectNodes(0, 0, array('name'));
// Прочитаем список дочерних узлов к этому, чтоб их исключить
$exclude_nodeSet = &$mnTree->selectNodes($id, 0, array('name'));
// Определим родителский элемент который является текущим
$parentNode = $mnTree->getParentNode($id);

?>