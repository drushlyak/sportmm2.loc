<?php
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _(" У вас нет прав на просмотр данного раздела");
		return;
	}

	$tmpSet = $fotoTree->select(0,  
		array(
			'name', 
			'id_te_value', 
			'description', 
			'res_id'
		), NSTREE_AXIS_DESCENDANT
	);

	$parent_id = $tmpSet[0]['id'];
	$parents = array();
	$parents[0] = 0;
	$nodeSet = array();

	if (is_array($tmpSet)) {
		foreach ($tmpSet as $tmp) {
			if ($tmp['has_children'] == 0) {
				$tmp['can_delete'] = true;
			} else {
				$tmp['can_delete'] = false;
			}
			$tmp['can_move'] = true;
			
			$nodeSet[] = $tmp;
		}
	}
?>