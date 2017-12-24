<?php

	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, DELETE)){
		$ACL_ERROR .= _(" У вас нет прав на удаление");
		Location(sprintf($_XFA['mainf_priv'], ((strlen(trim($ACL_ERROR)) > 350) ? substr($ACL_ERROR, 0, 350)."..." : trim($ACL_ERROR))), 0);
		die;
	}
	
	$priv_id = intval($attributes['id']);
	$id_mod = intval($attributes['id_mod']);
	
	$FORM_ERROR = "";
	
	if ($priv_id && $id_mod) {
		// получим значение top_id для модуля
		$sql = sql_placeholder("
			SELECT top_id 
				FROM " . CFG_DBTBL_MODULE . "
			WHERE id = ?
		", $id_mod);
		$top_id = $db->get_one($sql);
		
		// удалим запись о правиле доступа для модуля
		$sql = sql_placeholder("
			DELETE FROM " . CFG_DBTBL_ACL_MOD_PRIV . " 
			WHERE module_id = ?
			  AND privilege_id = ?
		", $id_mod, $priv_id);
		$db->query($sql);

		// удалим из таблицы правил все записи, касающиеся удаленного правила доступа для модуля
		// и всех потомков по дереву ресурсов
		if ($top_id) {
			$rnodes = $aclTree->select($top_id, array(), NSTREE_AXIS_DESCENDANT_OR_SELF);
			foreach ($rnodes as $node) {
				$configTable = $auth_in->store->getConfig();
				$sql = sql_placeholder("
					DELETE FROM " . $configTable['ruleTable'] . " 
					WHERE resource_id = ?
					  AND privilege_id = ?
				", $node['id'], $priv_id);
				$db->query($sql);					
			}
		}
		
	}
	
	Location(sprintf($_XFA['main_priv'], $id_mod), 0);

?>