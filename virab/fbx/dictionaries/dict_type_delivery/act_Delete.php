<?php

	$id = (int) $attributes['id'];
	$did = $attributes['did'];
	$id_city = (int) $attributes['id_city'];

	$FORM_ERROR = "";

	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, DELETE)){
		$ACL_ERROR .= _("У вас нет прав на удаление");
		Location(sprintf($_XFA['type_deliveryf'], $ACL_ERROR, $id_city), 0);
		die;
	}

	/**
	 * Удаление элемента справочника
	 *
	 * @param int $id
	 * @return boolean
	 */
	function delElement($id) {
		global $db, $lng, $FORM_ERROR;

		if ($id) {
			$sql = sql_placeholder("
				SELECT *
					FROM " . CFG_DBTBL_DICT_TYPE_DELIVERY . "
				WHERE id = ?
			", $id );
			$node = $db->get_row($sql);
			if (is_array($node)) {
				$sql = sql_placeholder("
					DELETE
					FROM " . CFG_DBTBL_DICT_TYPE_DELIVERY . "
					WHERE id = ?
				", $id );
				$db->query($sql);
			}
		} else {
			$FORM_ERROR .= _("Не определен идентификатор записи");
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

	$FORM_ERROR ? Location(sprintf($_XFA['main'], $FORM_ERROR), 0) : Location($_XFA['type_delivery'], 0);

?>