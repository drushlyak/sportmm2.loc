<?php

	// Обработка входных данных
	$attributes = inputCheckpoint($attributes, array(
		'id' => array(
			'type' => 'int'
		),
		'type' => array(
			'type' => 'int'
		),
		'id_city' => array(
			'type' => 'int'
		),
		'name' => array(
			'type' => 'string',
			'trim' => true
		),
		'start_time' => array(
			'type' => 'string',
			'trim' => true
		),
		'end_time' => array(
			'type' => 'string',
			'trim' => true
		),
		'start_for_today' => array(
			'type' => 'string',
			'trim' => true
		),
		'interval_hours' => array(
			'type' => 'string',
			'trim' => true
		),
		'price' => array(
			'type' => 'string',
			'trim' => true
		),
		'description' => array(
			'type' => 'string',
			'trim' => true
		)
	));

	$id  = $attributes['id'];
	$type = $attributes['type'];
	$id_city = $attributes['id_city'];
	$name = $attributes['name'];
	$start_time = $attributes['start_time'];
	$end_time = $attributes['end_time'];
	$start_for_today = $attributes['start_for_today'];
	$interval_hours = $attributes['interval_hours'];
	$price = $attributes['price'];
	$description = $attributes['description'];

	// Проверки
	if (empty($name)) {
		$FORM_ERROR = "<br />" . _("Необходимо указать название типа доставки") . "<br />";
	}

	if (!$FORM_ERROR) {

		if ($type == 2 && $id) {
			// Редактируем
			$sql = sql_placeholder("
				UPDATE " . CFG_DBTBL_DICT_TYPE_DELIVERY . "
		           	SET name = ?
		           	  , start_time = ?
		           	  , end_time = ?
		           	  , start_for_today = ?
		           	  , interval_hours = ?
		           	  , price = ?
		           	  , description = ?
		        	WHERE id = ?
			", $name
			 , $start_time
			 , $end_time
			 , $start_for_today
			 , $interval_hours
			 , $price
			 , $description
			 , $id );
		} else {
			// Добавляем
			$sql = sql_placeholder("
				INSERT
				INTO " . CFG_DBTBL_DICT_TYPE_DELIVERY . "
		           	SET name = ?
		           	  , start_time = ?
		           	  , end_time = ?
		           	  , start_for_today = ?
		           	  , interval_hours = ?
		           	  , price = ?
		           	  , description = ?
		    ", $name
			 , $start_time
			 , $end_time
			 , $start_for_today
			 , $interval_hours
			 , $price
			 , $description );
		}
		
		$db->query_($sql);
		Location($_XFA['main'], 0);
	} else {
		Location(sprintf($_XFA['type_delivery_formf'], $FORM_ERROR, $type, $id), 0);
	}

?>