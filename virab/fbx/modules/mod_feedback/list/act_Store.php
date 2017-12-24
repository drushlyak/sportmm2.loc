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
		'id_feedback' => array(
			'type' => 'int'
		),
		'type' => array(
			'type' => 'int'
		),

		'text' => array(
			'type' => 'string',
			'trim' => true
		),
		'author_name' => array(
			'type' => 'string',
			'trim' => true
		),
		'priz_active' => array(
			'type' => 'int'
		),
		'author_mail' => array(
			'type' => 'string',
			'trim' => true
		),
		'id_product' => array(
			'type' => 'int'
		)
	));

	$id  	= $attributes['id'];
	$id_feedback = $attributes['id_feedback'];
	$type 	= $attributes['type'];

	$text = $attributes['text'];
	$author_name = $attributes['author_name'];
	$author_mail = $attributes['author_mail'];
	$priz_active = $attributes['priz_active'];

	$id_product = $attributes['id_product'];

	$params = array(
		'text' => $text,
		'author_name' => $author_name,
		'author_mail' => $author_mail
	);

	/**
	 * =====================================================================
	 * Проверки
	 * =====================================================================
	 */

	// Имя
	if (empty($text)) {
		$FORM_ERROR = _("Необходимо указать отзыв") . "<br />";
	}

	/**
	 * =====================================================================
	 * Обновление / создание
	 * =====================================================================
	 */
	if (!$FORM_ERROR) {
		$text = $lng->SetTextlng($attributes['text']);
		$author_name = $lng->SetTextlng($attributes['author_name']);

		if ($type == 2 && $id) {
			$insert_id = $id;

			$db->update(
				CFG_DBTBL_MOD_FEEDBACK_TEXT,
				array(
					'text' => $text,
					'author_name' => $author_name,
					'priz_active' => $priz_active,
					'author_mail' => $author_mail,
					'id_product' => $id_product
				),
				array(
					'id' => $id
				)
			);

		} else {
			$insert_id = $db->insert(
				CFG_DBTBL_MOD_FEEDBACK_TEXT,
				array(
					'text' => $text,
					'author_name' => $author_name,
					'priz_active' => $priz_active,
					'author_mail' => $author_mail,
					'id_product' => $id_product,
					'idate' => date('Y-m-d H:i:s'),
					'group_id' => $id_feedback
				)
			);
		}

		Location(sprintf($_XFA['main'], $id_feedback), 0);
	} else {
		// Передача данных
		$params['str_error'] = $FORM_ERROR;
		Location(sprintf($_XFA['formf'], $id_feedback, 1, $type, $id, serialize($params)), 0);
	}
?>