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
		'is_menu' => array(
			'type' => 'int'
		),
        'is_menu_mc' => array(
            'type' => 'int'
        ),
		'main_category' => array(
			'type' => 'int'
		)
	));

	$id  = $attributes['id'];
	$type = $attributes['type'];
	$name = $attributes['name'];
	$is_menu = $attributes['is_menu'];
    $is_menu_mc = $attributes['is_menu_mc'];
	$main_category = $attributes['main_category'];
	$flag = 0;

	// Проверки
	if (empty($name)) {
		$FORM_ERROR = "<br />" . _("Необходимо указать название категории") . "<br />";
	}
	if (!$main_category) {
		$FORM_ERROR = "<br />" . _("Необходимо выбрать основную категорию") . "<br />";
	}

	if (!$FORM_ERROR) {
	
		$ord = $db->get_one("SELECT MAX(ord) FROM " . CFG_DBTBL_DICT_CATEGORY);
		$ord = $ord + 1;

		if ($type == 2 && $id) {
			// Редактируем
			$sql = sql_placeholder("
				UPDATE " . CFG_DBTBL_DICT_CATEGORY . "
		           	SET name = ?
					  , id_main_category = ?
					  , is_menu = ?
                      , is_menu_mc = ?
					  , flag = ?
					WHERE id = ?
			", $name
			 , $main_category
			 , $is_menu
             , $is_menu_mc
			 , $flag
			 , $id );
		} else {
			// Добавляем
			$sql = sql_placeholder("
				INSERT
				INTO " . CFG_DBTBL_DICT_CATEGORY . "
		           	SET name = ?
					  , id_main_category = ?
					  , is_menu = ?
                      , is_menu_mc = ?
					  , flag = ?
					  , ord = ?
			", $name
			 , $main_category
			 , $is_menu
             , $is_menu_mc
			 , $flag
			 , $ord );
		}

		$db->query($sql);
		Location($_XFA['main'], 0);
	} else {
		Location(sprintf($_XFA['formf'], $FORM_ERROR, $type, $id), 0);
	}

?>