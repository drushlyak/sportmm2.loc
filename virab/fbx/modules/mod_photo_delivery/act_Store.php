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
		'photo' => array(
			'type' => 'file'
		),
		'add_date' => array(
			'type' => 'date'
		),
		'is_view' => array(
			'type' => 'int'
		),
		'products' => array(
			'type' => 'array'
		)
	));

	$id  = $attributes['id'];
	$type = $attributes['type'];
	$name = $attributes['name'];
	$photo = $attributes['photo'];
	$add_date = $attributes['add_date'];
	$is_view = $attributes['is_view'];
	$products = $attributes['products'];

	// Проверки
	if (empty($name)) {
		$FORM_ERROR = "<br />" . _("Необходимо указать название ФИО") . "<br />";
	}

	if (!$FORM_ERROR) {

		// Загрузка и рессайзы изображение
		$img_sql = '';
		if (is_array($photo) && !$photo['error']) {
			// Удалим старое изображение
			$photos = $db->get_row("SELECT photo, tmb_photo, orig_photo FROM " . CFG_DBTBL_MOD_PHOTO_DELIVERY . " WHERE id = ?", $id);
			($photos['photo']) ? @unlink(BASE_PATH . $photos['photo']) : null;
			($photos['tmb_photo']) ? @unlink(BASE_PATH . $photos['tmb_photo']) : null;
			($photos['orig_photo']) ? @unlink(BASE_PATH . $photos['orig_photo']) : null;

			$result = uploadImageOperation($photo, array(
				'max_file_size'   => $site_config['max_file_size'],
				'safe_original'	  => true,
				'photo_no_resize' => false,
				'image_array' => array (
					'photo' => array (
						'width' => 340,
						'height' => 340,
						'quality' => 95,
						'boxing' => true,
						'boxing_bg_color' => array(255, 255, 255),
						'crop' => false
					),
					'tmb_photo' => array (
						'width' => 176,
						'height' => 176,
						'quality' => 95,
						'boxing' => true,
						'boxing_bg_color' => array(255, 255, 255),
						'crop' => false
					)
				)
			));

			if (!($result['has_error'] !== FALSE && $result['has_error'] !== UIO_ERROR_LOAD)) {
				$img_sql = ($result['image_array']) ? ", photo = '" . $result['image_array']['photo'] . "'
												   , tmb_photo = '" . $result['image_array']['tmb_photo'] . "'
												   , orig_photo = '" . $result['orig_image'] . "'
												   " : "";
			} else {
				$imageUploadError .= "Ошибка загрузки изображения: " . $__UIO_ERROR_STRING[$result['has_error']] . "<br />";
				$params['str_error'] = $imageUploadError;
				Location(sprintf($_XFA['formf'], 1, $type, $id, serialize($params)), 0);
			}
		}

		if ($type == 2 && $id) {
			// Редактируем
			$sql = sql_placeholder("
				UPDATE " . CFG_DBTBL_MOD_PHOTO_DELIVERY . "
		           	SET name = ?
					  , is_view = ?
					  , add_date = ?
					  " . $img_sql . "
 	           	WHERE id = ?
			", $name
			 , $is_view
			 , $add_date
			 , $id );
		} else {
			// Добавляем
			$sql = sql_placeholder("
				INSERT
				INTO " . CFG_DBTBL_MOD_PHOTO_DELIVERY . "
		           	SET name = ?
					  , is_view = ?
					  , add_date = ?
					  " . $img_sql . "
			", $name
			 , $is_view
			 , $add_date );
		}

		$db->query($sql);
		$new_id = ($type == 2 && $id) ? $id : $db->insert_id;

		// Очистим продукты в доставке
		$db->query("DELETE FROM " . CFG_DBTBL_MOD_PHOTO_DELIVERY_PRODUCT . " WHERE id_photo_delivery = ?", $new_id);

		// Добавим продукты в доставку
		if (is_array($products)) {
			foreach($products as $key => $val) {
				$db->query("
					INSERT INTO " . CFG_DBTBL_MOD_PHOTO_DELIVERY_PRODUCT . "
						SET id_product = ?
						  , id_photo_delivery = ?
				", $val, $new_id);
			}
		}

		Location($_XFA['main'], 0);
	} else {
		Location(sprintf($_XFA['formf'], $FORM_ERROR, $type, $id), 0);
	}

?>