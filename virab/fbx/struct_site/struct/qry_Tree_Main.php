<?php
	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _("У вас нет прав на просмотр");
		return;
	}
	$nodeSet   = $nsTree->select(0, array('name', 'chpu', 'id_contaner', 'enable', 'printable', 'wile', 'res_id'), NSTREE_AXIS_DESCENDANT);
	$parent_id = 1; //$nodeSet[0]['id'];
	$sql = sql_placeholder("SELECT id FROM $resTree->structTable
		WHERE data_id=?", $nodeSet[0]['res_id']
	);
	$parent_res = $db->get_one($sql);

?>