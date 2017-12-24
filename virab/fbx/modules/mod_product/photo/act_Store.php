<?php

	$attributes = inputCheckpoint($attributes, array(
		'id_product' => array(
			'type' => 'int'
		),
		'photo' => array(
			'type' => 'file'
		),
		'alt_text' => array(
	      'type' => 'string',
	      'trim' => true
		)
	));

	$id_product  = $attributes['id_product'];
	$photo = $attributes['photo'];
	$alt_text = $attributes['alt_text'];

	$result = uploadImageOperation($photo, array(
		'max_file_size'		=> $site_config['max_file_size'],
		'safe_original'		=> true,
		'photo_no_resize'	=> false,
		'image_array'		=> array (
			'thumb_image'	=> array (
				'width'			=> 80,
				'height'		=> 80,
				'quality'		=> 95,
				'boxing'		=> true,
				'boxing_bg_color' => array(255, 255, 255),
				'crop'			=> false
			),
			'image' => array (
				'width' => 340,
				'height' => 340,
				'quality' => 95,
				'boxing' => true,
				'boxing_bg_color' => array(255, 255, 255),
				'crop' => false
			)
		)
	));

	if (!($result['has_error'] !== FALSE && $result['has_error'] !== UIO_ERROR_LOAD)) {
		// реордер (установим новую фотографию на первое место)
		$db->query("
			UPDATE `" . CFG_DBTBL_MOD_PRODUCT_PHOTO . "`
			SET `pos`=`pos`+1
			WHERE `pos` >= 1
			  AND `id_product` = ?
		", $id_product );

		$db->insert(CFG_DBTBL_MOD_PRODUCT_PHOTO, array(
			'id_product' => $id_product,
			'path' => $result['image_array']['image'],
			'alt_text' => $alt_text,
			'path_orig' => $result['orig_image'],
			'tmb_path' => $result['image_array']['thumb_image'],
			'pos' => 1
		));
	} else {
		$imageUploadError .= "Ошибка загрузки изображения: " . $__UIO_ERROR_STRING[$result['has_error']] . "<br />";
		$params['str_error'] = $imageUploadError;
		Location(sprintf($_XFA['photof'], 1, $type, $id, serialize($params)), 0);
	}

	Location(sprintf($_XFA['photo'], $id_product), 0);
?>