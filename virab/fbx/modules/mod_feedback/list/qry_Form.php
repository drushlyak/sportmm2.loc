<?php
	$id   	= (int)$attributes['id'];
	$id_feedback  = (int) $attributes['id_feedback'];
	$type 	= (int) $attributes['type'];
	
	// Проверка доступа
	if ($id && ($type == 2)) {
		$sql = sql_placeholder("SELECT res_id FROM " . CFG_DBTBL_MOD_FEEDBACK_GROUP . " WHERE id=?", $id_feedback);
		$res_id = $db->get_one($sql);
		
		if (!$auth_in->aclCheck($res_id, EDIT)) {
			$ACL_ERROR = _("У вас нет прав на редактирование");
			return;
		}
	} else {
		if (!$auth_in->aclCheck($resourceId, CREATE)) {
			$ACL_ERROR = _("У вас нет прав на создание");
			return;
		}
	}

	if ($id) {
		$form_data = $db->get_row("
		SELECT mf.*,
				mp.name AS product_name
			FROM " . CFG_DBTBL_MOD_FEEDBACK_TEXT . " mf
				LEFT JOIN " . CFG_DBTBL_MOD_PRODUCT . " mp ON mf.id_product = mp.id
		WHERE mf.id = ?", $id);
		
		if(!is_array($form_data)){
			Location(sprintf($_XFA['main'], $id_feedback), 0);
		}
		$form_data['text'] = $lng->Gettextlngall($form_data['text']);
	}
