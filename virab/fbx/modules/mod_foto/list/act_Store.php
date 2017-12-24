<?php

	
	$attributes = inputCheckpoint($attributes, array(
		'id_fotogr' => array(
			'type' => 'int'
		),
		'photo' => array(
			'type' => 'file'
		),
		'alt_text' => array(
	      'type' => 'string',
	      'trim' => true
		),
		'value' => array(
			'type' => 'string',
			'trim' => true
		)
	));

	$id_fotogr  = $attributes['id_fotogr'];
	$photo = $attributes['photo'];
	$alt_text = $attributes['alt_text'];

	
	//вытаскиваем параметры для работы с изображением и миниатюрой
	$img_param = $db->get_row("SELECT * FROM " . CFG_DBTBL_MOD_FOTO_GRDATA . " WHERE id = ?", $id_fotogr);
	
	$crop_img = $img_param['crop_img'] ? true : false;
	$crop_tmb = $img_param['crop_tmb'] ? true : false;
	
	$result = uploadImageOperation($photo, array(
		'max_file_size'		=> $site_config['max_file_size'],
		'safe_original'		=> true,
		'photo_no_resize'	=> false,
		'image_array'		=> array (
			'thumb_image'	=> array (
				'width'			=> $img_param['width_tmb'],
				'height'		=> $img_param['height_tmb'],
				'quality'		=> $img_param['quality_tmb'],
				'boxing'		=> false,
				'boxing_bg_color' => array(255, 255, 255),
				'crop'			=> $crop_tmb
			),
			'image' => array (
				'width' => $img_param['width_img'],
				'height' => $img_param['width_img'],
				'quality' => $img_param['width_img'],
				'boxing' => true,
				'boxing_bg_color' => array(255, 255, 255),
				'crop' => $crop_img
			)
		)
	));
	
	$arr = array(
			'id_fotogr' => $id_fotogr,
			'path' => $result['image_array']['image'],
			'alt_text' => $alt_text,
			'path_orig' => $result['orig_image'],
			'tmb_path' => $result['image_array']['thumb_image'],
			'pos' => 1
		);
	//print_r($arr);
	//die();

	if (!($result['has_error'] !== FALSE && $result['has_error'] !== UIO_ERROR_LOAD)) {
		// реордер (установим новую фотографию на первое место)
		$db->query("
			UPDATE `" . CFG_DBTBL_MOD_EVENT_PHOTO . "`
			SET `pos`=`pos`+1
			WHERE `pos` >= 1
			  AND `id_fotogr` = ?
		", $id_fotogr );

		$sql = sql_placeholder("INSERT INTO " . CFG_DBTBL_MOD_EVENT_PHOTO . "
								SET id_fotogr = ?
								   ,path = ?
								   ,alt_text = ?
								   ,path_orig = ?
								   ,tmb_path = ?
								   ,pos = 1
							", $id_fotogr
							 , $result['image_array']['image']
							 , $alt_text
							 , $result['orig_image']
							 , $result['image_array']['thumb_image']);
		
		$db->query($sql);
		
		/*$db->insert(CFG_DBTBL_MOD_EVENT_FOTO, array(
			'id_fotogr' => $id_fotogr,
			'path' => $result['image_array']['image'],
			'alt_text' => $alt_text,
			'path_orig' => $result['orig_image'],
			'tmb_path' => $result['image_array']['thumb_image'],
			'pos' => 1
		));*/
	} else {
		$imageUploadError .= "Ошибка загрузки изображения: " . $__UIO_ERROR_STRING[$result['has_error']] . "<br />";
		$params['str_error'] = $imageUploadError;
		Location(sprintf($_XFA['photof'], 1, $type, $id, serialize($params)), 0);
	}

	Location(sprintf($_XFA['photo'], $id_fotogr), 0);
?>