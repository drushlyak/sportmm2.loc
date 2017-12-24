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
        'count_per_page' => array(
            'type' => 'int'
        ),
        'value' => array(
            'type' => 'string',
            'trim' => true
	    )
	));

	$id  = $attributes['id'];
	$type = $attributes['type'];

	$name = $attributes['name'];
    $value = $attributes['value'];
    $count_per_page = $attributes['count_per_page'];

	// Проверки
	if (empty($name)) {
		$FORM_ERROR = "<br />" . _("Необходимо указать наименование") . "<br />";
	}
	if (!$FORM_ERROR) {

		if ($type == 2 && $id) {
			// Редактируем
			$sql = sql_placeholder("
				UPDATE " . CFG_DBTBL_MOD_PHOTO_GRDATA . "
		           	SET name = ?, count_per_page = ?, id_te_value = ?
 	           	WHERE id = ?
			" , $name
			 , $count_per_page
			 , getTeValueId($value)
			 , $id );
		} else {
            // Записываем шаблонную переменную
            $sql = sql_placeholder("
                INSERT INTO ".CFG_DBTBL_TE_VALUE."
                    SET name=?
                      , typ=?
                      , sys=1
            ", $value, TE_VALUE_FOTO);

            $db->query($sql);
            $te_value_id = $db->insert_id;

			// Добавляем
			$sql = sql_placeholder("
				INSERT
				INTO " . CFG_DBTBL_MOD_PHOTO_GRDATA . "
		           	SET name = ?, count_per_page = ?, id_te_value = ?
			" , $name, $count_per_page, getTeValueId($value));
		}

		$db->query($sql);
        
		Location($_XFA['main'], 0);
	} else {
		Location(sprintf($_XFA['formf'], $FORM_ERROR, $type, $id), 0);
	}

?>