<?php

	// Проверка доступа
	if (!$auth_in->aclCheck($resourceId, DELETE)) {
		$ACL_ERROR = _("У вас нет прав на удаление");
		return;
	}

	$id = (int) $attributes['id'];
	$did = $attributes['did'];
	$FORM_ERROR = "";

	function delElement($id) {
		global $db, $FORM_ERROR;

		if($id) {

			$db->delete(CFG_DBTBL_CONFIG, array( 'id' => $id ));

		} else {
			$FORM_ERROR .= _(" Отсутствует запись id=") . $id;
		}
		return true;
	}

	if (is_array($did)) {
		foreach ($did as $delel) {
			delElement($delel);
		}
	} elseif ($id) {
		delElement($id);
	}

	Location($_XFA['main'], 0);

?>