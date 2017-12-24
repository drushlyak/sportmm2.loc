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
		'discount' => array(
			'type' => 'int'
		),
		'comment' => array(
			'type' => 'string',
			'trim' => true
		)
	));

	$id  = $attributes['id'];
	$type  = $attributes['type'];
	$id_client  = $attributes['id_client'];
	$discount = $attributes['discount'];
	$comment = $attributes['comment'];

	$params = array(
		'discount' => $discount,
		'comment' => $comment
	);

	/**
	 * =====================================================================
	 * Проверки
	 * =====================================================================
	 */

	if (empty($discount)) {
		$FORM_ERROR .= _("Необходимо указать размер скидки") . "<br />";
	}

	if (!$FORM_ERROR) {

		$new_data = array(
				'discount' => $discount,
				'comment' => $comment,
				'id_client' => $id_client,
				'idate' => date("Y.m.d H:i:s")
		);

		if ($type == 2 && $id) {
			$idClient = $db->update(CFG_DBTBL_MOD_CLIENT_DISCOUNT, $new_data, array('id' => $id));
		} else {
			$idClient = $db->insert(CFG_DBTBL_MOD_CLIENT_DISCOUNT, $new_data);
		}

		Location(sprintf($_XFA['discount'], $id_client), 0);

	} else {
		// Передача данных
		$params['str_error'] = $FORM_ERROR;
		Location(sprintf($_XFA['discount_formf'], 1, $id_client, serialize($params)), 0);
	}

?>