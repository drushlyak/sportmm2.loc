<?php

	// Проверка доступа
	if (!$auth_in->aclCheck($resourceId, EDIT)){
		$ACL_ERROR = _("У вас нет прав на редактирование");
		return;
	}

	$id_mod = intval($attributes['id_mod']);

	$configTable = $auth_in->store->getConfig();

	// запросим массив неиспользованных для данного mod_id привилегий
	$sql = sql_placeholder("
		SELECT ap.`id`
			 , ap.`name`
		     , ap.`var`
			FROM " . $configTable['privilegeTable'] . " AS ap
		WHERE ap.`id` NOT IN (
		    	SELECT amp.`privilege_id`
		        	FROM " . CFG_DBTBL_ACL_MOD_PRIV . " AS amp
		        WHERE amp.`module_id` = ?
		    )
	", $id_mod);
	$privSet = $db->get_all($sql);

?>