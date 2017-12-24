<?php

	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _("У вас нет прав на просмотр");
		return;
	}

	$configTable = $auth_in->store->getConfig();

	/**
	 * Получение потомков
	 */
	function selectChildren($id, $level) {
		global $resNode, $db, $elvl, $elCount, $configTable;

		$nodeSet = $db->get_all("
			SELECT role_id
				FROM {$configTable['roleRefTable']}
			WHERE parent = ?
		", $id);

		if (is_array($nodeSet)) {
			foreach ($nodeSet as $node) {
				$nodeChild = $db->get_all("
					SELECT id
						 , name
						FROM {$configTable['roleTable']}
					WHERE id = ?
				", $node['role_id']);

				if ($nodeChild) {
					foreach ($nodeChild as $child) {
						$elvl[$elCount++] = $level;
						$resNode[] = $child;
						selectChildren($child['id'], $level+1);
					}
				}
			}
		}
	}

	$nodeSet = $db->get_all("
		SELECT t1.id as id
			 , t1.name as name
			 , t2.parent as parent
		FROM {$configTable['roleTable']} AS t1
			LEFT JOIN {$configTable['roleRefTable']} AS t2 ON (t1.id = t2.role_id)
		ORDER BY id
	");

	$resNode = array(); $elvl = array(); $elCount = 1;

	if ($nodeSet) {
		foreach ($nodeSet as $node) {
			$level = 1;
			if (!$node['parent']) {
				$resNode[] = $node;
				$elvl[$elCount++] = $level;
				selectChildren($node['id'], $level+1);
			}
		}
	}

	$nodeSet = array(); $elCount = 1;

	foreach ($resNode as $node) {
		$nodeSet[] = array(
			'id' 			=> $node['id'],
			'name' 			=> $node['name'],
			'level' 		=> $elvl[$elCount++],
			'rule_count'	=> ($db->get_one("SELECT COUNT(*) FROM ( SELECT role_id FROM {$configTable['ruleTable']} WHERE role_id = ? GROUP BY resource_id) AS roles", $node['id'])),
			'user_count'	=> ($db->get_one("SELECT COUNT(*) FROM " . CFG_DBTBL_UDATA . " WHERE role_id = ?", $node['id'])),
			'has_children' 	=> (($db->get_one("SELECT id FROM {$configTable['roleRefTable']} WHERE parent = ?", $node['id'])) ? "1" : "0"),
			'parent_id'		=> ($db->get_one("SELECT parent FROM {$configTable['roleRefTable']} WHERE role_id = ?", $node['id']))
		);
	}

?>