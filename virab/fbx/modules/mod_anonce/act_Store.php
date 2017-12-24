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
		'url' => array(
			'type' => 'string',
			'trim' => true
		),
		'title' => array(
			'type' => 'string',
			'trim' => true
		),
		'address' => array(
			'type' => 'string',
			'trim' => true
		),
		'text' => array(
			'type' => 'html'
		),
		'main_foto' => array(
			'type' => 'file'
		)
	));

	$id  = $attributes['id'];
	$type = $attributes['type'];
	$url = $attributes['url'];
	$title = $attributes['title'];
	$address = $attributes['address'];
	$text = $attributes['text'];
	$is_active = $attributes['is_active'];
	$main_foto = $attributes['main_foto'];
	
	
	$params = array(
		'url' => $url,
		'title' => $title,
		'text' => $text,
		'address' => $address,
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
					
					'main_foto80' => array (
						'width' => 80,
						'height' => 80,
						'quality' => 100,
						'boxing' => false,
						'boxing_bg_color' => array(255, 255, 255),
						'crop' => true
					)
				)
			));
			
			if (!($result['has_error'] !== FALSE && $result['has_error'] !== UIO_ERROR_LOAD)) {
				$img_sql = ($result['image_array']) ? ", main_foto80 = '" . $result['image_array']['main_foto80'] . "'	   
												   , main_foto = '" . $result['orig_image'] . "'
												   " : "";
			} else {
				$imageUploadError .= "Ошибка загрузки изображения: " . $__UIO_ERROR_STRING[$result['has_error']] . "<br />";
				$params['str_error'] = $imageUploadError;
				Location(sprintf($_XFA['formf'], 1, $type, $id, serialize($params)), 0);
			}
		}
		
		//редактируем
		if ($type == 2 && $id) {
			$db->query("
					UPDATE " . CFG_DBTBL_MOD_ANONCE . "
						SET url = ?
						  , title = ?
						  , text = ?
						  , address = ?
						  , is_active = ?
						" . $img_sql . "
					WHERE id = ?  
				", $url, $title, $text, $address, $is_active, $id);
		} else {
		// Добавим

				$db->query("
					INSERT INTO " . CFG_DBTBL_MOD_ANONCE . "
						SET url = ?
						  , title = ?
						  , text = ?
						  , address = ?
						  , is_active = ?
						" . $img_sql . "  
				", $url, $title, $text, $address, $is_active);
		}

		Location($_XFA['main'], 0);

	} else {
		// Передача данных
		$params['str_error'] = $FORM_ERROR;
		Location(sprintf($_XFA['formf'], 1, serialize($params)), 0);
	}

?>