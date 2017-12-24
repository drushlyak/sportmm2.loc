<?php

	// Проверка доступа
	if (!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _("У вас нет прав на просмотр");
		return;
	}

	$id_mod = (int) $attributes['id_mod'];

	$configTable = $auth_in->store->getConfig();
	$sql = sql_placeholder("
		SELECT ap.`id`
			 , ap.`name`
		     , ap.`var`
			FROM " . CFG_DBTBL_ACL_MOD_PRIV . " AS amp
		    JOIN " . $configTable['privilegeTable'] . " AS ap ON ap.`id` = amp.`privilege_id`
		WHERE amp.`module_id` = ?
	", $id_mod);
	$nodeSet = $db->get_all($sql);

?>