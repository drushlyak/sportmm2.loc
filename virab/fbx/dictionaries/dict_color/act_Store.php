<?php

	// Обработка входных данных
	$attributes = inputCheckpoint($attributes, array(
		'id' => array(
			'type' => 'int'
		),
		'type' => array(
			'type' => 'int'
		),
		'name' => array(
			'type' => 'string',
			'trim' => true
		),
		'name_many' => array(
			'type' => 'string',
			'trim' => true
		),
		'color' => array(
			'type' => 'string',
			'trim' => true
		)
	));

	$id  = $attributes['id'];
	$type = $attributes['type'];
	$name = $attributes['name'];
	$name_many = $attributes['name_many'];
	$color = $attributes['color'];

	// Проверки
	if (empty($name)) {
		$FORM_ERROR = "<br />" . _("Необходимо указать название цвета") . "<br />";
	}
	if (empty($name_many)) {
		$FORM_ERROR = "<br />" . _("Необходимо указать название цвета для множественного числа") . "<br />";
	}
	if (strlen($color) != 7) {
		$FORM_ERROR = "<br />" . _("Необходимо указать код цвета в формате: #XXXXXX") . "<br />";
	}

	if (!$FORM_ERROR) {

		if ($type == 2 && $id) {
			// Редактируем
			$sql = sql_placeholder("
				UPDATE " . CFG_DBTBL_DICT_COLOR . "
		           	SET name = ?
					  , name_many = ?
					  , color = ?
					WHERE id = ?
			", $name
			 , $name_many
			 , $color
			 , $id );
		} else {
			// Добавляем
			$sql = sql_placeholder("
				INSERT
				INTO " . CFG_DBTBL_DICT_COLOR . "
		           	SET name = ?
					  , name_many = ?
		           	  , color = ?
			", $name
			 , $name_many
			 , $color );
		}

		$db->query($sql);
		Location($_XFA['main'], 0);
	} else {
		Location(sprintf($_XFA['formf'], $FORM_ERROR, $type, $id), 0);
	}

?>