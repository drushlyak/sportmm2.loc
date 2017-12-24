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
		)
	));

	$id  = $attributes['id'];
	$type  = $attributes['type'];
	$id_client  = $attributes['id_client'];
	$fio = $attributes['fio'];

	$params = array(
		'fio' => $fio
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
				'fio' 	=> $fio
		);

		if ($type == 2 && $id) {
			$idClient = $db->update(CFG_DBTBL_MOD_CONTACT_IN_ORDER, $new_data, array('id' => $id));
		} else {
			$idClient = $db->insert(CFG_DBTBL_MOD_CONTACT_IN_ORDER, $new_data);

			$db->insert(CFG_DBTBL_MOD_CLIENT_RECIPIENT, array('id_contact_in_order' => $idClient, 'id_client' => $id_client));
		}

		Location(sprintf($_XFA['contact_in_order'], $id_client), 0);

	} else {
		// Передача данных
		$params['str_error'] = $FORM_ERROR;
		Location(sprintf($_XFA['contact_in_order_formf'], 1, $id_client, serialize($params)), 0);
	}

?>