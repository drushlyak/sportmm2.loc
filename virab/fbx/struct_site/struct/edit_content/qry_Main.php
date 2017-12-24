<?php
	function getPath($id_node) {
		global $cntTree, $lng;

		$res = ""; $res_arr = array();
		$pp = $cntTree->select($id_node, array("name"), NSTREE_AXIS_ANCESTOR_OR_SELF);
		foreach ($pp as $i => $node) {
			if ($i == 0) continue;
			array_push($res_arr, $lng->Gettextlng($node['name']));
		}
		return $res . join(' - ', $res_arr);
	}

	$id1 = intval($attributes['id1']);

	// Проверка доступа
	$parent = $nsTree->getNode($id1, array('res_id'));
	if(!$auth_in->aclCheck($parent['res_id'], VIEW)){
		$ACL_ERROR = _("У вас нет прав на просмотр");
		return;
	}
	if($info = $nsTree->getNodeInfo($id1)){
		$category = $nsTree->getNode($info['id'], array('id_contaner', 'name'));
	}else{
		Location($_XFA['main'], 0);
	}
	$elem_executor[0] = 0;
	$conteinerSet = $cntTree->selectNodes(
		$category['id_contaner'], 0,
		array(
			'name',
			'type_template',
			'type_executor',
			'printable',
			'id_te_value'
		)
	);

	foreach($conteinerSet as $contaner){
		if(
			$contaner['type_template'] == TE_VALUE_EXECUTOR
			&& (
				($__TYPE_EXECUTOR[$contaner['type_executor']]['form'] == TE_EXECUTOR_SCREEN_WYSIWYG)
				|| ($__TYPE_EXECUTOR[$contaner['type_executor']]['form'] == TE_EXECUTOR_SIMPLE)
			)
		){
			// получим путь для данного узла
			$contaner['path'] = getPath($contaner['id']);
			// получим название переменной
			$contaner['te_var'] = $db->get_one("SELECT name FROM " . CFG_DBTBL_TE_VALUE . " WHERE id = ?", $contaner['id_te_value']);
	 		$Executor[] = $contaner;
	 	}
	}
	$id = $Executor[0]['id'];
	$typ = $Executor[0]['type_executor'];
	$count_records = count($Executor);
	$count_pg = 20;
	$pg = 0;
	$record_now = $pg * $count_pg;

?>