<?php
// Проверка доступа 
if(!$attributes['acl']){
	print "Ты как сюда попал?";
	return;
}
$storeConf = $auth_in->acl->getStore()->getConfig();
$privs = array();
$role_id = $attributes['role_id'];
$data_id = $attributes['data_id'];

if($attributes['role_id'] == 0){
	$role_id = null;
}
if(isset($attributes['privAll'])){
	$val = $attributes['privAll'];
	if($val == 2){
		$sql = "
			SELECT p.id
			FROM ".CFG_DBTBL_ACL_MOD_PRIV." AS mp 
			INNER JOIN {$storeConf['privilegeTable']} AS p 
				ON mp.privilege_id = p.id
			WHERE mp.module_id={$attributes['module_id']}
		";
		$privs =  $db->get_hashtable($sql);
		foreach ($privs as $id => $val) {
			$auth_in->acl->removeAllow($role_id, $data_id, $id);
			$auth_in->acl->removeDeny($role_id, $data_id, $id);
		}
	}elseif($val == 1){
		$auth_in->acl->allow($role_id, $data_id);
	}elseif($val == 0){
		$auth_in->acl->deny($role_id, $data_id);
	}
}else{
	$privs = $attributes['priv'];
	foreach($privs as $id => $val ){
		if($val == 2){
			$auth_in->acl->removeAllow($role_id, $data_id, $id);
			$auth_in->acl->removeDeny($role_id, $data_id, $id);
		}elseif($val == 1){
			$auth_in->acl->removeDeny($role_id, $data_id, $id);
			$auth_in->acl->allow($role_id, $data_id, $id);
		}else{
			$auth_in->acl->removeAllow($role_id, $data_id, $id);
			$auth_in->acl->deny($role_id, $data_id, $id);
		}
	}
}

Location($_XFA['main'], 0);

?>