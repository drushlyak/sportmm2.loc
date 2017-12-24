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
		)
	));

	$id  = $attributes['id'];
	$type = $attributes['type'];
	$name = $attributes['name'];
	

	// Проверки
	if (empty($name)) {
		$FORM_ERROR = "<br />" . _("Необходимо указать название коллекции") . "<br />";
	}
	

	if (!$FORM_ERROR) {

		if ($type == 2 && $id) {
			// Редактируем
			$sql = sql_placeholder("
				UPDATE " . CFG_DBTBL_DICT_COLLECTIONS . "
		           	SET name = ?
					  
					WHERE id = ?
			", $name
			 , $id );
		} else {
			// Добавляем
			$sql = sql_placeholder("
				INSERT
				INTO " . CFG_DBTBL_DICT_COLLECTIONS . "
		           	SET name = ?
					  
			", $name);
		}

		$db->query($sql);
		Location($_XFA['main'], 0);
	} else {
		Location(sprintf($_XFA['formf'], $FORM_ERROR, $type, $id), 0);
	}

?>