<?php

	$attributes = inputCheckpoint($attributes, array(
		'id' => array(
			'type' => 'int'
		),
		'did' => array(
			'type' => 'array'
		),
		'id_client' => array(
			'type' => 'int'
		)
	));

	$id = $attributes['id'];
	$did = $attributes['did'];
	$id_client = $attributes['id_client'];

	$FORM_ERROR = "";

	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, DELETE)){
		$ACL_ERROR .= _("У вас нет прав на удаление");
		Location(sprintf($_XFA['contact_person'], $id_client, $ACL_ERROR), 0);
		die;
	}

	/**
	 * Удаление элемента
	 *
	 * @param int $id
	 * @return boolean
	 */
	function delElement($id) {
		global $db, $FORM_ERROR;

		if ($id) {
			$sql = sql_placeholder("DELETE FROM " . CFG_DBTBL_MOD_CONTACT_PERSON . " WHERE id = ?", $id );
			$db->query($sql);
			$sql = sql_placeholder("DELETE FROM " . CFG_DBTBL_MOD_CLIENT_CONTACT_PERSONS . " WHERE id_contact_person = ?", $id );
			$db->query($sql);
		} else {
			$FORM_ERROR .= _("Не определен идентификатор записи");
		}
		return true;
	}

	if (is_array($did) && count($did) > 0) {
		foreach ($did as $delel) {
			delElement($delel);
		}
	} elseif ($id) {
		delElement($id);
	}

	Location(sprintf($_XFA['contact_person'], $id_client, $FORM_ERROR), 0);

?>