<?php
	$id   = (int) $attributes['id'];
	$type = (int) $attributes['type'];
	
	// Проверка доступа
	if ($id && ($type == 2)) {
		if (!$auth_in->aclCheck($resourceId, EDIT)) {
			$ACL_ERROR = _("У вас нет прав на редактирование");
			return;
		}
	} else {
		if (!$auth_in->aclCheck($resourceId, CREATE)) {
			$ACL_ERROR = _("У вас нет прав на создание");
			return;
		}
	}
	
	// Список шаблонов
	$conteinerSet = &$cntTree->select(0, array('name', 'type_template'), NSTREE_AXIS_DESCENDANT);
	if(is_array($conteinerSet)){
		foreach($conteinerSet as $conteiner){
			if($conteiner['type_template'] != TE_VALUE_FEEDBACK){
				continue;
			}
//			// Если нет имени узла
//			$conteiner['name'] = (!$lng->Gettextlng($conteiner['name'])) ? _("Безымянный") : $lng->Gettextlng($conteiner['name']);
			$codeSet[] = $conteiner;
		}
	}
	
	if ($id) {
		$sql = sql_placeholder("SELECT * FROM ".CFG_DBTBL_MOD_FEEDBACK_GROUP." WHERE id=?", $id );
		$form_data = $db->get_row($sql);
		
		if(!is_array($form_data)){
			Location($_XFA['cat_main'], 0);
		}
	}

// Прочитаем список всех узлов
//$nodeSet = &$nsTree->selectNodes(0, 0, array('name'));
?>