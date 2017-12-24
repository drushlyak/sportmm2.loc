<?php

	// Обработка входных данных

	global $site_config;
	$attributes = inputCheckpoint($attributes, array(
		'id' => array(
			'type' => 'int'
		),
		'type' => array(
			'type' => 'int'
		),
		'id_category' => array(
			'type' => 'int'
		),
		'_day' => array(
			'type' => 'int'
		),
		'_month' => array(
			'type' => 'int'
		),
		'_year' => array(
			'type' => 'int'
		),
		'_hour' => array(
			'type' => 'int'
		),
		'_minute' => array(
			'type' => 'int'
		),
		'_second' => array(
			'type' => 'int'
		),
		'title' => array(
			'type' => 'string',
			'trim' => true
		),
		'descr' => array(
			'type' => 'string',
			'trim' => true
		),
		'chpu' => array(
			'type' => 'string',
			'trim' => true
		),
		'name' => array(
			'type' => 'string',
			'trim' => true
		),
		'text' => array(
			'type' => 'html'
		),
		'anonce_text' => array(
			'type' => 'string',
			'trim' => true
		),
		'main_foto' => array(
			'type' => 'file'
		),
		'a_tags' => array(
	      'type' => 'string',
	      'trim' => true
	    )
		
		
	));

	
	$id  = $attributes['id'];
	$type = $attributes['type'];
	$id_category  = $attributes['id_category'];
	$title = $attributes['title'];
	$name = $attributes['name'];
	$text = $attributes['text'];
	$anonce_text = $attributes['anonce_text'];
	$main_foto = $attributes['main_foto'];
	$chpu  = $attributes['chpu'];
	$descr  = $attributes['descr'];
	$a_tags = $attributes['a_tags'];
	$i_date = $attributes['_year'] . '-' . $attributes['_month'] . '-' . $attributes['_day'] . ' ' . $attributes['_hour'] . ':' . $attributes['_minute'] . ':' . $attributes['_second'];
	

	
	$params = array(
		'chpu' => $chpu,
		'name' => $name,
		'title' => $title,
		'text' => $text,
		'descr' => $descr,
		'anonce_text' => $anonce_text		
	);

	/**
	 * =====================================================================
	 * Проверки
	 * =====================================================================
	 */


	if (empty($name)) {
		$FORM_ERROR .= _("Необходимо указать наименование") . "<br />";
	}
	
	if (!$FORM_ERROR) {

	// Загрузка и рессайзы изображение
		$img_array = array();
		if (is_array($main_foto) && !$main_foto['error']) {
			// Удалим старое изображение
			$main_fotos = $db->get_row("SELECT main_foto, main_foto_orig FROM " . CFG_DBTBL_MOD_ARTICLES . " WHERE id = ?", $id);
			($main_fotos['main_foto']) ? @unlink(BASE_PATH . $main_fotos['main_foto']) : null;
			($main_fotos['main_foto_orig']) ? @unlink(BASE_PATH . $main_fotos['main_foto_orig']) : null;

			$result = uploadImageOperation($main_foto, array(
				'max_file_size'   => $site_config['max_file_size'],
				'safe_original'	  => true,
				'photo_no_resize' => false,
				'image_array' => array (
					'main_foto' => array (
						'width' => 78,
						'height' => 78,
						'quality' => 90,
						'boxing' => false,
						'boxing_bg_color' => array(255, 255, 255),
						'crop' => true
					)
				)
			));

			if (!($result['has_error'] !== FALSE && $result['has_error'] !== UIO_ERROR_LOAD)) {
				if($result['image_array']) {
					$img_array = array (
						'main_foto' => $result['image_array']['main_foto'],
						'main_foto_orig' => $result['orig_image']
					);
				} else {
					$img_array = array();
				}
			} else {
				$imageUploadError .= "Ошибка загрузки изображения: " . $__UIO_ERROR_STRING[$result['has_error']] . "<br />";
				$params['str_error'] = $imageUploadError;
				Location(sprintf($_XFA['articles_formf'], 1, $type, $id_category, $id, serialize($params)), 0);
			}
		}

		while (true) {
		 $id_ = $db->get_one("SELECT id FROM " . CFG_DBTBL_MOD_ARTICLES . " WHERE chpu = ?", $chpu);
			if ($id_ && $id_ != $id) {
				$chpu .= '_';
				continue;
			}
			break;
		}
		
	
		$new_data = array(
				'chpu' 				=> $chpu,
				'name' 				=> $name,
				'title' 			=> $title,
				'text' 				=> $text,
				'anonce_text'       => $anonce_text,
				'i_date'			=> $i_date,
				'id_category'		=> $id_category,
				'descr'				=> $descr
			);
			
		$new_data = array_merge($new_data, $img_array);
		
		if ($type == 2 && $id) {
			$idClient = $db->update(CFG_DBTBL_MOD_ARTICLES, $new_data, array('id' => $id));
		} else {
			$idClient = $db->insert(CFG_DBTBL_MOD_ARTICLES, $new_data);
		}
		
		// Пропишем теги
		$tags_all = $db->get_all("SELECT id, name FROM " . CFG_DBTBL_MOD_TAGS);
		if (is_array($tags_all)) {
			foreach ($tags_all as $tag) {
				$tagsSetF[] = $tag;
				$tagsSet[] = $tag['name'];
			}
		}
		
		unset($tags_id);
		if (strlen($a_tags)) {
			$tags = explode(' ', trim($a_tags));
			if (is_array($tags)) {
				foreach ($tags as $tag) {
					if (in_array($tag, $tagsSet)) {
						if (is_array($tagsSetF)) {
							foreach ($tagsSetF as $tagF) {
								if ($tagF['name'] == $tag) {
									$tags_id[] = $tagF['id'];
								}
							}
						}
					} else {
						$db->query("INSERT INTO " . CFG_DBTBL_MOD_TAGS . " SET name = ?", $tag);
						$tags_id[] = $db->insert_id;
					}
				}
			}
		}
		
		if($type == 2)
			$db->query("DELETE FROM " . CFG_DBTBL_MOD_ARTICLES_TAGS . " WHERE id_articles = ?", $id);

		if (is_array($tags_id)) {
			foreach ($tags_id as $tag) {
				$db->query("INSERT INTO " . CFG_DBTBL_MOD_ARTICLES_TAGS . " SET id_articles = ?, id_tag = ?", $id, $tag);
			}
		}
		

		Location(sprintf($_XFA['articles'], $id_category), 0);
	} else {
		Location(sprintf($_XFA['articles_formf'], $FORM_ERROR, $type, $id_category, $id), 0);
	}

?>