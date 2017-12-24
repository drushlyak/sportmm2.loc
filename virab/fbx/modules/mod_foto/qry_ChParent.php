<?php
	$id   = (int) $attributes['id'];
	
	// Проверка доступа
	$node = $fotoTree->getNode($id, array('res_id'));
	if ($id) {
		if (!$auth_in->aclCheck($node['res_id'], CHANGE_PARENT)) {
			$ACL_ERROR = _(" У вас нет прав на смену родителя");
			return;
		}
	}
	// Прочитаем список всех узлов
	$nodeSet = $fotoTree->selectNodes(0, 0, array('name'));
	
	// Прочитаем список дочерних узлов к этому, чтоб их исключить
	$exclude_nodeSet = $fotoTree->selectNodes($id, 0, array('name'));
	
	// Определим родителский элемент который является текущим
	$parentNode = $fotoTree->getParentNode($id);

?>