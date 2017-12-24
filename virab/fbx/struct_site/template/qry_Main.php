<?php
// Проверка доступа
if(!$auth_in->aclCheck($resourceId, VIEW)){
	$ACL_ERROR = _("У вас нет прав на просмотр");
	return;
}
$tmpSet = $cntTree->select(0,
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
		'double_id',
		'res_id'
	), NSTREE_AXIS_DESCENDANT
);

$parent_id = 1;
$parents = array();
$parents[0] = 0;
$nodeSet = array();

// Перебираем весь массив шаблонов для того, чтоб определить, каким из них можно сменить родителя
if(is_array($tmpSet)){
	foreach($tmpSet as $tmp){
		$tmp['can_delete'] = false;
		if(($parents[$tmp['level']-1] == TE_VALUE_FOLDER) || ($tmp['level'] == 1)){
			if($tmp['type_template'] != TE_VALUE_FOLDER){
				$tmp['can_delete'] = true;
			}else{
				if($tmp['has_children'] == 0){
					$tmp['can_delete'] = true;
				}else{
					// Список всех дочерних
					$childSet = $cntTree->select($tmp['id'], array('type_template'), NSTREE_AXIS_DESCENDANT);
					if(is_array($childSet)) {
						foreach($childSet as $child){
							if($child['type_template'] != TE_VALUE_FOLDER){
								$tmp['can_delete'] = false;
								break;
							}else{
								$tmp['can_delete'] = true;
							}
						}
					}
				}
			}
			$tmp['can_move'] = true;
		}else{
			$tmp['can_move'] = false;
		}
		$parents[$tmp['level']] = $tmp['type_template'];
		$nodeSet[] = $tmp;
	}
}



?>