<?php

	$id = (int) $attributes['id'];
	
	$operation = (int) $attributes['operation']; // 0 - удаление, 1 - установка

	

	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, EDIT)){
		$ACL_ERROR .= _("У вас нет прав на удаление");
		Location(sprintf($_XFA['mainf'], $ACL_ERROR), 0);
		die;
	}

	if ($operation) {
		
		$db->query("
			UPDATE " . CFG_DBTBL_MOD_MAIN_CATEGORY . "
				SET is_active = 1
			WHERE id = ?
		", $id );

	} else {
		// удаление
		$db->query("
			UPDATE " . CFG_DBTBL_MOD_MAIN_CATEGORY . "
				SET is_active = 0
			WHERE id = ?
		", $id );
	}

	Location($_XFA['main'], 0);