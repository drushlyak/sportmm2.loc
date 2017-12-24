<?php

	$id = (int) $attributes['id'];
	$id_category = (int) $attributes['id_category'];
	$did = $attributes['did'];

	$FORM_ERROR = "";

	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, DELETE)){
		$ACL_ERROR .= _("У вас нет прав на удаление");
		Location(sprintf($_XFA['articlesf'], $id_category, $ACL_ERROR), 0);
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
					FROM " . CFG_DBTBL_MOD_ARTICLES . "
				WHERE id = ?
			", $id );
			$node = $db->get_row($sql);
			if (is_array($node)) {
				$sql = sql_placeholder("
					DELETE
					FROM " . CFG_DBTBL_MOD_ARTICLES . "
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

	$FORM_ERROR ? Location(sprintf($_XFA['articlesf'], $id_category, $FORM_ERROR), 0) : Location(sprintf($_XFA['articles'], $id_category), 0);

?>