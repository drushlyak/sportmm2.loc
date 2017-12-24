<?php

	$attributes = inputCheckpoint($attributes, array(
		'id' => array(
			'type' => 'int'
		),
		'type' => array(
			'type' => 'int'
		),

		'config_name' => array(
			'type' => 'string',
			'trim' => true
		),
		'config_value' => array(
			'type' => 'string',
			'trim' => true
		),
		'description' => array(
			'type' => 'string',
			'trim' => true
		),

		'category_cpu_url' => array(
			'type' => 'string',
			'trim' => true
		),

		'category_title' => array(
			'type' => 'string',
			'trim' => true
		),
		'category_description' => array(
			'type' => 'string',
			'trim' => true
		),
		'category_keywords' => array(
			'type' => 'string',
			'trim' => true
		),

		'category_titleh1' => array(
			'type' => 'string',
			'trim' => true
		),

		'category_image_1' => array(
			'type' => 'string',
			'trim' => true
		),

		'category_video_1' => array(
			'type' => 'string',
			'trim' => true
		),

		'category_video_2' => array(
			'type' => 'string',
			'trim' => true
		),

		'category_video_3' => array(
			'type' => 'string',
			'trim' => true
		),

		'category_video_4' => array(
			'type' => 'string',
			'trim' => true
		),

		'category_video_5' => array(
			'type' => 'string',
			'trim' => true
		),

		'category_video_6' => array(
			'type' => 'string',
			'trim' => true
		),

		'category_video_7' => array(
			'type' => 'string',
			'trim' => true
		),

		'category_video_8' => array(
			'type' => 'string',
			'trim' => true
		),

		'category_video_9' => array(
			'type' => 'string',
			'trim' => true
		),

		'category_text' => array(
			'type' => 'html',
			'trim' => true
		)
		
		));

	$id  = $attributes['id'];
	$type = $attributes['type'];

	$config_name = $attributes['config_name'];
	$config_value = $attributes['config_value'];
	$description = $attributes['description'];
	$category_cpu_url = $attributes['category_cpu_url'];
	$category_title = $attributes['category_title'];
	$category_description = $attributes['category_description'];
	$category_keywords = $attributes['category_keywords'];
	$category_titleh1 = $attributes['category_titleh1'];
	$category_image_1 = $attributes['category_image_1'];
	$category_video_1 = $attributes['category_video_1'];
	$category_video_2 = $attributes['category_video_2'];
	$category_video_3 = $attributes['category_video_3'];
	$category_video_4 = $attributes['category_video_4'];
	$category_video_5 = $attributes['category_video_5'];
	$category_video_6 = $attributes['category_video_6'];
	$category_video_7 = $attributes['category_video_7'];
	$category_video_8 = $attributes['category_video_8'];
	$category_video_9 = $attributes['category_video_9'];
	$category_text = $attributes['category_text'];
	

	// передать идентификатор по которому из сессии вытащатся все переменные
	$params_hash = md5(date('d.m.Y H:s:i'));

	$_SESSION['formdata'][$params_hash] = array(
		'config_name'	=> $config_name,
		'config_value' 	=> $config_value,
		'description'	=> $description,
		'category_cpu_url'	=> $category_cpu_url,
		'category_title'	=> $category_title,
		'category_description' 	=> $category_description,
		'category_keywords'	=> $category_keywords,
		'category_titleh1'	=> $category_titleh1,
		'category_image_1'	=> $category_image_1,
		'category_video_1' => $category_video_1,
		'category_video_2' => $category_video_2,
		'category_video_3' => $category_video_3,
		'category_video_4' => $category_video_4,
		'category_video_5' => $category_video_5,
		'category_video_6' => $category_video_6,
		'category_video_7' => $category_video_7,
		'category_video_8' => $category_video_8,
		'category_video_9' => $category_video_9,
		'category_text'	=> $category_text
		);

	/**
	 * =====================================================================
	 * Проверки
	 * =====================================================================
	 */

	if (empty($config_name)) {
		$FORM_ERROR .= _("Необходимо указать наименование переменной") . "<br />";
	} elseif (strpos($config_name, " ") !== false) {
		$FORM_ERROR .= _("Переменная не должна содержать символ пробела!") . "<br />";
	} elseif (!preg_match('/^[\w]*$/si', $config_name)) {
		$FORM_ERROR .= _("Переменная должна содержать только латиницу или цифры!") . "<br />";
	} elseif (preg_match('/^[\d]/i', substr($config_name, 0, 1))) {
		$FORM_ERROR .= _("Переменная не должна начинаться с цифры!") . "<br />";
	}

	if (empty($config_value)) {
		$FORM_ERROR .= _("Необходимо указать значение переменной") . "<br />";
	}


	/**
	 * =====================================================================
	 * Обновление / создание
	 * =====================================================================
	 */

	if (!$FORM_ERROR) {
		$full_name = $lng->Settextlng($full_name);

		if ($type == 2 && $id) {
			// Редактируем
			$db->update(CFG_DBTBL_CONFIG, array(
				'config_name' => $config_name,
				'config_value' => $config_value,
				'description' => $description,
				'category_cpu_url' => $category_cpu_url,
				'category_title' => $category_title,
				'category_description' => $category_description,
				'category_keywords' => $category_keywords,
				'category_titleh1' => $category_titleh1,
				'category_image_1' => $category_image_1,
				'category_video_1' => $category_video_1,
				'category_video_2' => $category_video_2,
				'category_video_3' => $category_video_3,
				'category_video_4' => $category_video_4,
				'category_video_5' => $category_video_5,
				'category_video_6' => $category_video_6,
				'category_video_7' => $category_video_7,
				'category_video_8' => $category_video_8,
				'category_video_9' => $category_video_9,
				'category_text' => $category_text
				
				
			), array(
				'id' => $id
			));
		} else {
			// Добавляем
			$db->insert(CFG_DBTBL_CONFIG, array(
				'config_name' => $config_name,
				'config_value' => $config_value,
				'description' => $description,
				'category_cpu_url' => $category_cpu_url,
				'category_title' => $category_title,
				'category_description' => $category_description,
				'category_keywords' => $category_keywords,
				'category_titleh1' => $category_titleh1,
				'category_image_1' => $category_image_1,
				'category_video_1' => $category_video_1,
				'category_video_2' => $category_video_2,
				'category_video_3' => $category_video_3,
				'category_video_4' => $category_video_4,
				'category_video_5' => $category_video_5,
				'category_video_6' => $category_video_6,
				'category_video_7' => $category_video_7,
				'category_video_8' => $category_video_8,
				'category_video_9' => $category_video_9,
				'category_text' => $category_text
				
				
				
			));
		}

		Location($_XFA['main'], 0);

	} else {
		// Передача данных
		$_SESSION['formdata'][$params_hash]['str_error'] = $FORM_ERROR;
		Location(sprintf($_XFA['formf'], 1, $type, $id, $params_hash), 0);
	}

?>