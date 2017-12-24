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
			if($conteiner['type_template'] != TE_VALUE_FOTO){
				continue;
			}
			$codeSet[] = $conteiner;
		}
	}

	if ($id) {
		if ($type == 2) {
			if ($info = $fotoTree->getNodeInfo($id)){
				$form_data = $fotoTree->getNode($info['id'], array('name'
																 , 'id_te_value'
																 , 'description'
																 , 'code'
																 , 'count_per_page'
																 , 'resize'
																 , 'crop_img'
																 , 'width_img'
																 , 'height_img'
																 , 'quality_img'
																 , 'auto_tmb'
																 , 'crop_tmb'
																 , 'width_tmb'
																 , 'height_tmb'
																 , 'quality_tmb'
																 , 'res_id'
																 , 'orig_img'));
			} else {
				Location($_XFA['cat_main'], 0);
			}
		} else {
			$parent_id = $id;
			$parent_info = $nsTree->getNodeInfo($parent_id);
			$parent = &$fotoTree->select($parent_id, array('name', 'res_id'), NSTREE_AXIS_SELF);
		}
	}
?>