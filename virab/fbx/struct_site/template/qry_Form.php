<?php
$parent_id = intval($attributes['id']);
$typ = intval($attributes['typ']);
$id = intval($attributes['id']);
if(($typ == 2) && $id){
	$id = intval($attributes['id']);
	if($info = $cntTree->getNodeInfo($id)){
		$category = $cntTree->getNode($info['id'],
			array(
				'name',
				'id_te_value',
				'code',
				'id_executor',
				'type_template',
				'type_executor',
				'printable',
				'content',
				'is_selective',
				'res_id'
			)
		);
		$name = $category['name'];
		$category['name'] = $lng->Gettextlngall($category['name']);
		if(($category['type_template'] == TE_VALUE_EXECUTOR)
			&&  ($category['type_executor'] == TE_EXECUTOR_CODE)
		){
			$category['code'] = $lng->Gettextlngall($category['code']);
		}
	}else{
		Location($_XFA['main'], 0);
	}
}
$can_be_selective = ($id == 1 && $typ != 2)?true:false;
if(($typ == 2) || ($id > 1)){
	if($category['level'] == 1){
		$can_be_selective = true;
	}else{
		$can_be_selective = true;
		$parentNodes = $cntTree->select($id, array('type_template'), NSTREE_AXIS_ANCESTOR);
		$rootNode = $cntTree->getRootNodeInfo();
		foreach($parentNodes as $pNode){
			if($pNode['id'] == $rootNode['id']){
				continue;
			}
			if($pNode['type_template'] != TE_VALUE_FOLDER){
				$can_be_selective = false;
			}
		}
	}
}
// Проверка доступа
if(($typ == 2) && ($id)){
	if(!$auth_in->aclCheck($category['res_id'], EDIT)){
		$ACL_ERROR = _("У вас нет прав на редактирование");
		return;
	}
}else{
	if(!$auth_in->aclCheck($resourceId, CREATE)){
		$ACL_ERROR = _("У вас нет прав на создание");
		return;
	}
}
?>