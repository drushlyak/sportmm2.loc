<?php

	// Инициация дерева
	$resTree = $auth_in->store->getResourceTree();
	if ($attributes['module_id']) {
		$module_id = $attributes['module_id'];
		$role_id = $attributes['role_id'];
	} elseif ($attributes['userdata']) {
		parse_str($attributes['userdata']);
	}
	
	$sql = sql_placeholder("SELECT top_id FROM ".CFG_DBTBL_MODULE." WHERE id=?", $module_id);
	$top_id = $db->get_one($sql);
	
	$top = $top_id;
	
	if ($attributes['id']) {
		$top_id = $attributes['id'];
	}
	
	if (!isset($axis)) {
		$axis = $attributes['axis'];
	}
	
	$axesMap = array(
		'child-or-self' => NSTREE_AXIS_CHILD_OR_SELF,
		'child' 		=> NSTREE_AXIS_CHILD
	);
	$axis = key_exists($axis, $axesMap) ? $axesMap[$axis] : NSTREE_AXIS_CHILD_OR_SELF;
	
	$root = $resTree->getNodeInfo($top);	
	$nodeSet = $resTree->select($top_id, array(), NSTREE_AXIS_CHILD);
	
	if ($axis == NSTREE_AXIS_CHILD_OR_SELF) {
		array_unshift($nodeSet, $root);
	}
	
	foreach ($nodeSet as &$node) {
		$node['level'] -= $root['level'];
	}
	
	$configTable = $auth_in->store->getConfig();
	
	$sql = sql_placeholder("
		SELECT 
			m.*, 
			da.file AS inc_file, 
			da.sys_mod AS sys_mod
		FROM ".CFG_DBTBL_MODULE." AS m
		JOIN  ".CFG_DBTBL_DSP_ACL." AS da 
			ON (m.id = da.mod_id)
		WHERE m.id=?", 
		$module_id
	);
	
	$res = $db->get_row($sql);
	$module = $res['var'];
	$top_id = $res['top_id'];
	$mod_name = $res['name'];
	
	/*
	 * Поключение файла для отображение элементов в списке доступных ресурсов.
	 * Файл устанавливает с модулем, если модуль не является системным либо хранится в 
	 * LIB_PATH."/dsp_acl/" 
	 */ 
	$inc_file = $res['inc_file'];
	if ($res['sys_mod']) {
		$file = LIB_PATH."/dsp_acl/".$inc_file;
	} elseif($res['mod_type'] == 0) {
		$file = MODULE_PATH."/".$module."/".$inc_file;
	} elseif($res['mod_type'] == 1) {
		$file = DICT_PATH."/".$module."/".$inc_file;
	}
	
	include($file);
	createDisplayAcl(&$nodeSet);
	
	// удалим ноды без name поля (это те, у которых res_id = 0)
	$tmpSET = array();
	foreach($nodeSet as $nv) {
		if ($nv['name']) {
			$tmpSET[] = $nv;
		}
	}
	$nodeSet = $tmpSET;
	
	$sql = sql_placeholder("
		SELECT id, privilege_id FROM ".CFG_DBTBL_ACL_MOD_PRIV." WHERE module_id=? ORDER BY id", $module_id
	); 
	$tmpSet = $db->get_all($sql);
	$privSet = array();
	
	if ($tmpSet) {
		foreach($tmpSet as $tmp){
			$sql = sql_placeholder("
				SELECT id, name, var FROM {$configTable['privilegeTable']} WHERE id=? ORDER BY id", $tmp['privilege_id']
			); 
			$privSet[] = $db->get_row($sql);
		}
	}

?>