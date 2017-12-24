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
		'phone' => array(
			'type' => 'string',
			'trim' => true
		),
		'is_mobile' => array(
			'type' => 'int'
		)
	));

	$id  = $attributes['id'];
	$type  = $attributes['type'];
	$id_client  = $attributes['id_client'];
	$phone = $attributes['phone'];
	$is_mobile = $attributes['is_mobile'];

	$params = array(
		'phone' => $phone,
		'is_mobile' => $is_mobile
	);

	/**
	 * =====================================================================
	 * Проверки
	 * =====================================================================
	 */

	if (empty($phone)) {
		$FORM_ERROR .= _("Необходимо указать номер телефона") . "<br />";
	}

	if (!$FORM_ERROR) {

		$new_data = array(
				'phone' 	=> $phone,
				'is_mobile' => $is_mobile
		);

		if ($type == 2 && $id) {
			$idClient = $db->update(CFG_DBTBL_MOD_PHONES_STORAGE, $new_data, array('id' => $id));
		} else {
			$idClient = $db->insert(CFG_DBTBL_MOD_PHONES_STORAGE, $new_data);

			$db->insert(CFG_DBTBL_MOD_CLIENT_PHONES, array('id_phone_storage' => $idClient, 'id_client' => $id_client));
		}

		Location(sprintf($_XFA['phones'], $id_client), 0);

	} else {
		// Передача данных
		$params['str_error'] = $FORM_ERROR;
		Location(sprintf($_XFA['phones_formf'], 1, $id_client, serialize($params)), 0);
	}

?>