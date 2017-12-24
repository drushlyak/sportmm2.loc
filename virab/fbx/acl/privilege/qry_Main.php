<?php
	// Проверка доступа
	if (!$auth_in->aclCheck($resourceId, VIEW)) {
		$ACL_ERROR = _("У вас нет прав на просмотр");
		return;
	}
	$configTable = $auth_in->store->getConfig();

	$nodeSet = $db->get_all("
		SELECT id
			 , name
			 , var
		FROM " . $configTable['privilegeTable'] . "
	");

?>