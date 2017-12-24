<?php

	$type = (int) $attributes['type'];
	$id = (int) $attributes['id'];
	$id_product  = (int)$attributes['id_product'];

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

	$form_data = false;
?>