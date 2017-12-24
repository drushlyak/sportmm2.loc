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
		global $db, $FORM_ERROR;

		if ($id) {
			$db->delete(CFG_DBTBL_MOD_ORDER_ADDITIONAL_INFO, array( 'id_order' => $id ));
			$db->delete(CFG_DBTBL_MOD_ORDER_ADDRESS_PAYMENT, array( 'id_order' => $id ));
			$db->delete(CFG_DBTBL_MOD_ORDER_USED_DISCONT_CARDS, array( 'id_order' => $id ));
			$db->delete(CFG_DBTBL_MOD_ORDER_PRODUCT, array( 'id_order' => $id ));
			$db->delete(CFG_DBTBL_MOD_ORDER_DECLINED_ORDER, array( 'id_order' => $id ));
			$db->delete(CFG_DBTBL_MOD_ORDER_DELIVERY, array( 'id_order' => $id ));

			$db->delete(CFG_DBTBL_MOD_ORDER, array( 'id' => $id ));
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