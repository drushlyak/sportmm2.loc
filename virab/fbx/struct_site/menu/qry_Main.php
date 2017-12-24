<?php
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _("У вас нет прав на просмотр");
		return;
	}

	$menuSet = $mnTree->select(0,
		array(
			'name',
			'id_te_value',
			'id_node',
			'url',
			'template_id'
		), NSTREE_AXIS_DESCENDANT
	);

	$parent_id = 1;

?>