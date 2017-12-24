<?php
$id = ($attributes['id']) ? intval($attributes['id']) : $id;
// Проверка доступа
$node = $nsTree->getNode($id, array('res_id'));
if(!$auth_in->aclCheck($node['res_id'], EDIT)){
	$ACL_ERROR = _("У вас нет прав на редактирование");
	return;
}
if($info = $nsTree->getNodeInfo($id1)){
	$category = $nsTree->getNode($id, array('id_contaner', 'name'));
}else{
	Location($_XFA['main'], 0);
}
// Список селективных шаблонов
$conteinerSet = $cntTree->selectNodes(0, 0,
	array(
		'name',
		'is_selective',
		'res_id'
	)
);
$contSet = array();
foreach($conteinerSet as $contaner){
	if($contaner['is_selective']){
 		$contSet[] = $contaner;
 	}
}
//  Получаем список всех комбинируемых для текущего шаблона
$conteinerSet = $cntTree->select($category['id_contaner'],
	array(
		'name',
		'type_template',
		'type_executor',
		'printable',
		'is_selective'
	),
	NSTREE_AXIS_DESCENDANT
);
foreach($conteinerSet as $contaner){
	if($contaner['type_template'] == TE_VALUE_SELECT){
 		$selectSet[] = $contaner;
 	}
}
$nodeSet = array();
if(is_array($selectSet)){
	foreach($selectSet as $select){
		$sql = "
			SELECT *
			FROM ".CFG_DBTBL_TE_SELECTIVE_TMPL."
			WHERE
				selective_id=?
				AND map_id=?
		";
		$tmp = $db->get_row($sql, $select['id'], $id);
		if(is_array($tmp)){
			$nodeSet[$select['id']] = $tmp;
		}else{
			$nodeSet[$select['id']] = array();
		}
		$nodeSet[$select['id']]['name'] = $select['name'];
	}
}
?>