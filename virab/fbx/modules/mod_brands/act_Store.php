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
		'is_active' => array(
			'type' => 'int'
		),

		'name' => array(
			'type' => 'string',
			'trim' => true
		),
		'main_foto' => array(
			'type' => 'file'
		),
		'main_foto_black' => array(
			'type' => 'file'
		),

		'brand_cpu_url' => array(
		'type' => 'string',
		'trim' => true
		),

		'brand_title' => array(
		'type' => 'string',
		'trim' => true
		),
		'brand_description' => array(
		'type' => 'string',
		'trim' => true
		),
		'brand_keywords' => array(
		'type' => 'string',
		'trim' => true
		),
		'brand_text' => array(
		'type' => 'html',
		'trim' => true
		)
	));

	$id  = $attributes['id'];
	$type = $attributes['type'];
	$name = $attributes['name'];
	$is_active = $attributes['is_active'];
	$main_foto = $attributes['main_foto'];
	$main_foto_black = $attributes['main_foto_black'];
	$brand_cpu_url = $attributes['brand_cpu_url'];
	$brand_title = $attributes['brand_title'];
	$brand_description = $attributes['brand_description'];
	$brand_keywords = $attributes['brand_keywords'];
	$brand_text = $attributes['brand_text'];
	
	
	$params = array(
		'name' => $name,
		'is_active' => $is_active
	);

	/**
	 * =====================================================================
	 * Проверки
	 * =====================================================================
	 */

	if (!is_array($main_foto)) {
		$FORM_ERROR .= _("Необходимо выбрать фото") . "<br />";
	}

	if (!$FORM_ERROR) {
		//print_r($main_foto);
		//die();
		// Загрузка и рессайзы изображение
		$img_sql = '';
		if (is_array($main_foto) && !$main_foto['error']) {
			

			$result = uploadImageOperation($main_foto, array(
				'max_file_size'   => $site_config['max_file_size'],
				'safe_original'	  => true,
				'photo_no_resize' => false,
				'image_array' => array (
					
					
				)
			));

			if (!($result['has_error'] !== FALSE && $result['has_error'] !== UIO_ERROR_LOAD)) {
				$img_sql = ", main_foto = '" . $result['orig_image'] . "'";
			} else {
				$imageUploadError .= "Ошибка загрузки изображения: " . $__UIO_ERROR_STRING[$result['has_error']] . "<br />";
				$params['str_error'] = $imageUploadError;
				Location(sprintf($_XFA['formf'], 1, $type, $id, serialize($params)), 0);
			}
		}
		
		$img_sql_black = '';
		if (is_array($main_foto_black) && !$main_foto_black['error']) {
			

			$result = uploadImageOperation($main_foto_black, array(
				'max_file_size'   => $site_config['max_file_size'],
				'safe_original'	  => true,
				'photo_no_resize' => false,
				'image_array' => array (
					
					
				)
			));

			if (!($result['has_error'] !== FALSE && $result['has_error'] !== UIO_ERROR_LOAD)) {
				$img_sql_black = ", main_foto_black = '" . $result['orig_image'] . "'";
			} else {
				$imageUploadError .= "Ошибка загрузки изображения: " . $__UIO_ERROR_STRING[$result['has_error']] . "<br />";
				$params['str_error'] = $imageUploadError;
				Location(sprintf($_XFA['formf'], 1, $type, $id, serialize($params)), 0);
			}
		}
		
		//редактируем
		if ($type == 2 && $id) {
			$db->query("
					UPDATE " . CFG_DBTBL_MOD_BRANDS . "
						SET name = ?
						  , is_active = ?
						" . $img_sql . $img_sql_black . "
						, brand_cpu_url = ?
						, brand_title = ?
						, brand_description = ?
						, brand_keywords = ?
						, brand_text = ?
					WHERE id = ?  
				", $name
				, $is_active
				, $brand_cpu_url
				, $brand_title
				, $brand_description
				, $brand_keywords
				, $brand_text
				, $id
				);
		} else {
		// Добавим

				$db->query("
					INSERT INTO " . CFG_DBTBL_MOD_BRANDS . "
						SET name = ?
						  , is_active = ?
						" . $img_sql . $img_sql_black . " 
						, brand_cpu_url = ?
						, brand_title = ?
						, brand_description = ?
						, brand_keywords = ?
						, brand_text = ?
					", $name
					, $is_active
					, $brand_cpu_url
					, $brand_title
					, $brand_description
					, $brand_keywords
					, $brand_text
				);
		}

		Location($_XFA['main'], 0);

	} else {
		// Передача данных
		$params['str_error'] = $FORM_ERROR;
		Location(sprintf($_XFA['formf'], 1, serialize($params)), 0);
	}

?>