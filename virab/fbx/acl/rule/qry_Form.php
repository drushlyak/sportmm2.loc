<?php

$id = $attributes['resource_id'];
$module_id = $attributes['module_id'];
$role_id = $attributes['role_id'];
if($id){
	$node = $resTree->getNodeInfo($id);
	$data_id = $node['data_id'];
	$configTable = $auth_in->store->getConfig();
	$sql = sql_placeholder("
		SELECT id, privilege_id 
		FROM ".CFG_DBTBL_ACL_MOD_PRIV." 
		WHERE module_id=? ORDER BY id", 
		$module_id
	); 
	$tmpSet = $db->get_all($sql);
	$privSet = array();
	if ($tmpSet) {
		foreach($tmpSet as $tmp){
			$sql = sql_placeholder("
				SELECT id, name, var 
				FROM {$configTable['privilegeTable']} WHERE id=? ORDER BY id", $tmp['privilege_id']
			); 
			$privSet[] = $db->get_row($sql);
		}
	}
	$sql = "SELECT id, name FROM {$configTable['roleTable']}";
	$roleSet = $db->get_all($sql);
}else{
	Location($_XFA['main'], 0);
}
// Проверка доступа
if($id){
	if(!$auth_in->aclCheck($resourceId, EDIT)){
		$ACL_ERROR = _("У вас нет прав на редактирование");
		return;
	}
}
?>