<?php

// Проверка доступа
if(!$auth_in->aclCheck($resourceId, VIEW)){
	$ACL_ERROR = _(" У вас нет прав на просмотр");
	return;
}
$nodeSet = &$sTree->selectTNodes(0, array('title', 'quick_help', 'url', 'var', 'parent_id'));

?>