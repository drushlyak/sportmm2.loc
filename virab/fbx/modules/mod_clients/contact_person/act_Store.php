<?php
	/**
	 * =====================================================================
	 * Обработка входных данных
	 * =====================================================================
	 */

	$attributes = inputCheckpoint($attributes, array(
		'id' => array(
			'type' => 'int'
		),
		'type' => array(
			'type' => 'int'
		),
		'id_client' => array(
			'type' => 'int'
		),
		'fio' => array(
			'type' => 'string',
			'trim' => true
		),
		'email' => array(
			'type' => 'string',
			'trim' => true
		)
	));

	$id  = $attributes['id'];
	$type  = $attributes['type'];
	$id_client  = $attributes['id_client'];
	$fio = $attributes['fio'];
	$email = $attributes['email'];

	$params = array(
		'fio' => $fio,
		'email' => $email
	);

	/**
	 * =====================================================================
	 * Проверки
	 * =====================================================================
	 */

	if (empty($fio)) {
		$FORM_ERROR .= _("Необходимо указать ФИО") . "<br />";
	}

	if (!$FORM_ERROR) {

		$new_data = array(
				'fio' 	=> $fio,
				'email' => $email
		);

		if ($type == 2 && $id) {
			$idClient = $db->update(CFG_DBTBL_MOD_CONTACT_PERSON, $new_data, array('id' => $id));
		} else {
			$idClient = $db->insert(CFG_DBTBL_MOD_CONTACT_PERSON, $new_data);

			$db->insert(CFG_DBTBL_MOD_CLIENT_CONTACT_PERSONS, array('id_contact_person' => $idClient, 'id_client' => $id_client));
		}

		Location(sprintf($_XFA['contact_person'], $id_client), 0);

	} else {
		// Передача данных
		$params['str_error'] = $FORM_ERROR;
		Location(sprintf($_XFA['contact_person_formf'], 1, $id_client, serialize($params)), 0);
	}

?>