<?php

	$id = (int) $attributes['id'];
	$did = $attributes['did'];

	$FORM_ERROR = "";

	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, DELETE)){
		$ACL_ERROR .= _("У вас нет прав на удаление");
		Location(sprintf($_XFA['mainf'], $ACL_ERROR), 0);
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
					FROM " . CFG_DBTBL_MOD_PRODUCT . "
				WHERE id = ?
			", $id );
			$node = $db->get_row($sql);
			if (is_array($node)) {
				// Удалим изображения
				clearImageOperation(array(
					'path_clean' => array($node['main_foto50'], $node['main_foto60'], $node['main_foto75'], $node['main_foto100'], $node['main_foto200'], $node['main_foto460'], $node['main_foto_orig'])
				));

				// Удалим все фотки
				$phs = $db->get_all("SELECT * FROM " . CFG_DBTBL_MOD_PRODUCT_PHOTO . " WHERE id_product = ?", $id);
				if (is_array($phs)) {
					foreach ($phs as $ph) {
						clearImageOperation(array(
							'path_clean' => array($ph['path'], $ph['tmb_path'], $ph['path_orig'])
						));
					}
				}

				// Удалим все связи
				$db->delete(CFG_DBTBL_MOD_PRODUCT_PHOTO, array('id_product' => $id));
				$db->delete(CFG_DBTBL_MOD_CATEGORY_PRODUCT, array('id_product' => $id));
				$db->delete(CFG_DBTBL_MOD_TYPE_VIEW_PRODUCT, array('id_product' => $id));

				$sql = sql_placeholder("
					DELETE
					FROM " . CFG_DBTBL_MOD_PRODUCT . "
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

	$FORM_ERROR ? Location(sprintf($_XFA['mainf'], $FORM_ERROR), 0) : Location($_XFA['main'], 0);

?>