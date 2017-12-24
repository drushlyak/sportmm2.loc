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
		),

		'subcategory_cpu_url' => array(
			'type' => 'string',
			'trim' => true
		),

		'subcategory_title' => array(
			'type' => 'string',
			'trim' => true
		),

		'subcategory_description' => array(
			'type' => 'string',
			'trim' => true
		),
		'subcategory_keywords' => array(
			'type' => 'string',
			'trim' => true
		),

		'subcategory_titleh1' => array(
			'type' => 'string',
			'trim' => true
		),

		'subcategory_image_1' => array(
			'type' => 'string',
			'trim' => true
		),

		'subcategory_video_1' => array(
			'type' => 'string',
			'trim' => true
		),

		'subcategory_video_2' => array(
			'type' => 'string',
			'trim' => true
		),

		'subcategory_video_3' => array(
			'type' => 'string',
			'trim' => true
		),

		'subcategory_video_4' => array(
			'type' => 'string',
			'trim' => true
		),

		'subcategory_video_5' => array(
			'type' => 'string',
			'trim' => true
		),

		'subcategory_video_6' => array(
			'type' => 'string',
			'trim' => true
		),

		'subcategory_video_7' => array(
			'type' => 'string',
			'trim' => true
		),

		'subcategory_video_8' => array(
			'type' => 'string',
			'trim' => true
		),

		'subcategory_video_9' => array(
			'type' => 'string',
			'trim' => true
		),

		'subcategory_text' => array(
			'type' => 'html',
			'trim' => true
		)
	));
	
	$id  = $attributes['id'];
	$type = $attributes['type'];
	$name = $attributes['name'];
	$subcategory_cpu_url = $attributes['subcategory_cpu_url'];
	$subcategory_title = $attributes['subcategory_title'];
	$subcategory_description = $attributes['subcategory_description'];
	$subcategory_keywords = $attributes['subcategory_keywords'];
	$subcategory_titleh1 = $attributes['subcategory_titleh1'];
	$subcategory_keywords = $attributes['subcategory_keywords'];
	$subcategory_titleh1 = $attributes['subcategory_titleh1'];
	$subcategory_image_1 = $attributes['subcategory_image_1'];
	$subcategory_video_1 = $attributes['subcategory_video_1'];
	$subcategory_video_2 = $attributes['subcategory_video_2'];
	$subcategory_video_3 = $attributes['subcategory_video_3'];
	$subcategory_video_4 = $attributes['subcategory_video_4'];
	$subcategory_video_5 = $attributes['subcategory_video_5'];
	$subcategory_video_6 = $attributes['subcategory_video_6'];
	$subcategory_video_7 = $attributes['subcategory_video_7'];
	$subcategory_video_8 = $attributes['subcategory_video_8'];
	$subcategory_video_9 = $attributes['subcategory_video_9'];
	$subcategory_text = $attributes['subcategory_text'];
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
					  , subcategory_cpu_url = ?
					  , subcategory_title = ?
					  , subcategory_description = ?
					  , subcategory_keywords = ?
					  , subcategory_titleh1 = ?
					  , subcategory_image_1 = ?
					  , subcategory_video_1 = ?
					  , subcategory_video_2 = ?
					  , subcategory_video_3 = ?
					  , subcategory_video_4 = ?
					  , subcategory_video_5 = ?
					  , subcategory_video_6 = ?
					  , subcategory_video_7 = ?
					  , subcategory_video_8 = ?
					  , subcategory_video_9 = ?
					  , subcategory_text = ?
					WHERE id = ?
			", $name
			 , $main_category
			 , $is_menu
			 , $is_menu_mc
			 , $flag
				, $subcategory_cpu_url
			 , $subcategory_title
			 , $subcategory_description
			 , $subcategory_keywords
				, $subcategory_titleh1
				, $subcategory_image_1
				, $subcategory_video_1
				, $subcategory_video_2
				, $subcategory_video_3
				, $subcategory_video_4
				, $subcategory_video_5
				, $subcategory_video_6
				, $subcategory_video_7
				, $subcategory_video_8
				, $subcategory_video_9
				, $subcategory_text
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
					  , subcategory_cpu_url = ?
					  , subcategory_title = ?
					  , subcategory_description = ?
					  , subcategory_keywords = ?
					  , subcategory_titleh1 = ?
					    , subcategory_image_1 = ?
					  , subcategory_video_1 = ?
					  , subcategory_video_2 = ?
					  , subcategory_video_3 = ?
					  , subcategory_video_4 = ?
					  , subcategory_video_5 = ?
					  , subcategory_video_6 = ?
					  , subcategory_video_7 = ?
					  , subcategory_video_8 = ?
					  , subcategory_video_9 = ?
					  , subcategory_text = ?			  
			", $name
			 , $main_category
			 , $is_menu
			 , $is_menu_mc
			 , $flag
			 , $ord 
			 , $subcategory_cpu_url
			 , $subcategory_title
			 , $subcategory_description
			 , $subcategory_keywords
				, $subcategory_titleh1
				, $subcategory_image_1
				, $subcategory_video_1
				, $subcategory_video_2
				, $subcategory_video_3
				, $subcategory_video_4
				, $subcategory_video_5
				, $subcategory_video_6
				, $subcategory_video_7
				, $subcategory_video_8
				, $subcategory_video_9
				, $subcategory_text
			 );
		}

		$db->query($sql);
		Location($_XFA['main'], 0);
	} else {
		Location(sprintf($_XFA['formf'], $FORM_ERROR, $type, $id), 0);
	}

?>