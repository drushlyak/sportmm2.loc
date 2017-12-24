<?php

	$type = (int) $attributes['type'];
	$id = (int) $attributes['id'];

	// Проверка доступа
	if ($id) {
		if(!$auth_in->aclCheck($resourceId, EDIT)){
			$ACL_ERROR = _("У вас нет прав на редактирование");
			return;
		}
	} else {
		if (!$auth_in->aclCheck($resourceId, CREATE)){
			$ACL_ERROR = _("У вас нет прав на добавление");
			return;
		}
	}

	if ($type === 2) {
		$name_value = $db->get_one("
			SELECT name_value
				FROM " . CFG_DBTBL_LANGUAGE . "
			WHERE id = ?
		", $id);

		if ($name_value) {
			$form_data = array(
				'msgid' => $name_value
			);

			$form_datas = $db->get_all("
				SELECT *
					FROM " . CFG_DBTBL_LANGUAGE . "
				WHERE name_value = ?
			", $name_value );

			if (is_array($form_datas)) {
				foreach ($form_datas as $fd) {
					$form_data[$fd['id_dict_language']] = $fd['text'];
				}
			}
		}

	} else {
		$form_data = false;
	}

	$languageSet = $db->get_all("SELECT * FROM " . CFG_DBTBL_DICT_LANGUAGE);

?>