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
		'bonus' => array(
			'type' => 'int'
		),
		'type_bonus_action' => array(
			'type' => 'int'
		),
		'id_order' => array(
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
	$bonus = $attributes['bonus'];
	$id_order = $attributes['id_order'];
	$type_bonus_action = $attributes['type_bonus_action'];
	$comment = $attributes['comment'];

	$params = array(
		'bonus' => $bonus,
		'type_bonus_action' => $type_bonus_action,
		'id_order' => $id_order,
		'comment' => $comment
	);

	/**
	 * =====================================================================
	 * Проверки
	 * =====================================================================
	 */

	if (empty($bonus)) {
		$FORM_ERROR .= _("Необходимо указать количество бонусов") . "<br />";
	}

	if (!$FORM_ERROR) {

		$new_data = array(
				'bonus' => $bonus,
				'type_bonus_action' => $type_bonus_action,
				'comment' => $comment,
				'id_client' => $id_client,
				'id_order' => $id_order,
				'idate' => date("Y.m.d H:i:s")
		);

		if ($type == 2 && $id) {
			$idClient = $db->update(CFG_DBTBL_MOD_CLIENT_BONUS, $new_data, array('id' => $id));
		} else {
			$idClient = $db->insert(CFG_DBTBL_MOD_CLIENT_BONUS, $new_data);
		}

		Location(sprintf($_XFA['bonus'], $id_client), 0);

	} else {
		// Передача данных
		$params['str_error'] = $FORM_ERROR;
		Location(sprintf($_XFA['bonus_formf'], 1, $id_client, serialize($params)), 0);
	}

?>