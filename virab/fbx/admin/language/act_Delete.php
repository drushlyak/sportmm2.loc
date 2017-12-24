<?php

	$id = (int) $attributes['id'];
	$did = $attributes['did'];
	$FORM_ERROR = "";

	// Проверка доступа
	if (!$auth_in->aclCheck($resourceId, DELETE)) {
		$ACL_ERROR .= _("У вас нет прав на удаление");
		Location(sprintf($_XFA['mainf'], $ACL_ERROR), 0);
		die;
	}


	function delElement($id) {
		global $db, $lng, $FORM_ERROR;

		if ($id) {

			$db->delete(CFG_DBTBL_LANGUAGE, array(
				'id' => $id
			));
			$lng->clearCacheValue($db->get_one("SELECT name_value FROM " . CFG_DBTBL_LANGUAGE . " WHERE id = ?", $id));

		} else {
			$FORM_ERROR .= _("Не определен id записи");
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

	Location(sprintf($_XFA['mainf'], $FORM_ERROR), 0);

?>