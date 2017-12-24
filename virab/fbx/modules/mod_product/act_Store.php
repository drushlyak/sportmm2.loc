<?php

	// Обработка входных данных
	//print_r($attributes);
	//die();
	$attributes = inputCheckpoint($attributes, array(
		'id' => array(
			'type' => 'int'
		),
		'type' => array(
			'type' => 'int'
		),
		'article' => array(
			'type' => 'string',
			'trim' => true
		),
	    'name' => array(
	      'type' => 'string',
	      'trim' => true
	    ),
	    'chpu' => array(
	      'type' => 'string',
	      'trim' => true
	    ),
	    'alternative_title' => array(
	      'type' => 'string',
	      'trim' => true
	    ),
		'description_for_product' => array( //добавлено
	      'type' => 'string',
	      'trim' => true
	    ),
		'keywords_for_product' => array( //добавлено
	      'type' => 'string',
	      'trim' => true
	    ),
	    'a_tags' => array(
	      'type' => 'string',
	      'trim' => true
	    ),
		'main_foto' => array(
			'type' => 'file'
		),
	    'alt_text' => array(
	      'type' => 'string',
	      'trim' => true
	    ),
		'cost_excess' => array(
			'type' => 'int'
		),	
		'description' => array(
			'type' => 'html',
			'trim' => true
		),
		'description_table' => array(
			'type' => 'html',
			'trim' => true
		),
		'ordr' => array(
			'type' => 'int'
		),
		'is_new' => array(
			'type' => 'int'
		),
		'is_hit' => array(
			'type' => 'int'
		),
		'is_promo' => array(
			'type' => 'int'
		),
		'is_view_main' => array(
			'type' => 'int'
		),
		'is_active' => array(
			'type' => 'int'
		),
		'categories' => array(
			'type' => 'array'
		),
		'num_stock' => array(
			'type' => 'int'
		),
		'id_producer' => array(
			'type' => 'int'
		),
	    'producer' => array(
	      'type' => 'string',
	      'trim' => true
	    ),    
		'discount' => array(
			'type' => 'int'
		)
	));

	


	//categories

	$id  = $attributes['id'];
	$type = $attributes['type'];
	$article = $attributes['article'];
	$name = $attributes['name'];
  	$chpu = $attributes['chpu'];
	$alternative_title = $attributes['alternative_title'];
  	$description_for_product = $attributes['description_for_product']; //добавлено
	$keywords_for_product = $attributes['keywords_for_product'];//добавлено
	$a_tags = $attributes['a_tags'];
  	$producer = $attributes['producer'];
  	$id_producer = $attributes['id_producer'];
  	$i_date = $attributes['i_date'];

	$main_foto = $attributes['main_foto'];
	$alt_text = $attributes['alt_text'];

	$cost_excess = $attributes['cost_excess'];
	$discount = $attributes['discount'];

	//$weight_size = $attributes['weight_size'];

	$description_table = $attributes['description_table'];
	$description = $attributes['description'];
	$ordr = $attributes['ordr'];
	$is_new = $attributes['is_new'];
	$is_hit = $attributes['is_hit'];
	$is_promo = $attributes['is_promo'];
	$is_view_main = $attributes['is_view_main'];
	$is_active = $attributes['is_active'];
	//$delivery = $attributes['delivery'];
	$num_stock = $attributes['num_stock'];


	//$colors = $attributes['colors'];
	$categories = $attributes['categories'];
	//$itemCountByID = $attributes['itemCountByID'];
	//$viewOptionByID = $attributes['viewOptionByID'];

	
	// Проверки
	if (empty($name)) {
		$FORM_ERROR = "<br />" . _("Необходимо указать название продукции") . "<br />";
	}
		if (!$cost_excess || $cost_excess < 0) {
		$FORM_ERROR = "<br />" . _("Необходимо указать цену за единицу продкуции сверх минимальной цены") . "<br />";
	}

	if (!$FORM_ERROR) {

		// Загрузка и рессайзы изображение
		$img_sql = '';
		if (is_array($main_foto) && !$main_foto['error']) {
			// Удалим старое изображение
			$main_fotos = $db->get_row("SELECT main_foto50, main_foto80, main_foto176, main_foto340, main_foto_orig FROM " . CFG_DBTBL_MOD_PRODUCT . " WHERE id = ?", $id);
			($main_fotos['main_foto50']) ? @unlink(BASE_PATH . $main_fotos['main_foto50']) : null;		
			($main_fotos['main_foto80']) ? @unlink(BASE_PATH . $main_fotos['main_foto80']) : null;
			($main_fotos['main_foto176']) ? @unlink(BASE_PATH . $main_fotos['main_foto176']) : null;		
			($main_fotos['main_foto340']) ? @unlink(BASE_PATH . $main_fotos['main_foto340']) : null;			
			($main_fotos['main_foto_orig']) ? @unlink(BASE_PATH . $main_fotos['main_foto_orig']) : null;

			$result = uploadImageOperation($main_foto, array(
				'max_file_size'   => $site_config['max_file_size'],
				'safe_original'	  => true,
				'photo_no_resize' => false,
				'image_array' => array (
					'main_foto50' => array (
						'width' => 50,
						'height' => 50,
						'quality' => 95,
						'boxing' => true,
						'boxing_bg_color' => array(255, 255, 255),
						'crop' => false
					),
					'main_foto80' => array (
						'width' => 80,
						'height' => 80,
						'quality' => 95,
						'boxing' => true,
						'boxing_bg_color' => array(255, 255, 255),
						'crop' => false
					),
					'main_foto176' => array (
						'width' => 176,
						'height' => 176,
						'quality' => 95,
						'boxing' => true,
						'boxing_bg_color' => array(255, 255, 255),
						'crop' => false
					),					
					'main_foto340' => array (
						'width' => 342,
						'height' => 342,
						'quality' => 100,
						'boxing' => true,
						'boxing_bg_color' => array(255, 255, 255),
						'crop' => false
					)
				)
			));

			if (!($result['has_error'] !== FALSE && $result['has_error'] !== UIO_ERROR_LOAD)) {
				$img_sql = ($result['image_array']) ? ", main_foto50 = '" . $result['image_array']['main_foto50'] . "'
												   , main_foto80 = '" . $result['image_array']['main_foto80'] . "'
												   , main_foto176 = '" . $result['image_array']['main_foto176'] . "' 
												   , main_foto340 = '" . $result['image_array']['main_foto340'] . "'	   
												   , main_foto_orig = '" . $result['orig_image'] . "'
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
				UPDATE " . CFG_DBTBL_MOD_PRODUCT . "
		           	SET name = ?
					  , article = ?
					  , alternative_title = ?
					  , alt_text = ?
					  , cost_excess = ?
					  , discount = ?
					  , description = ?
					  , description_table = ?
					  , producer = ?
					  , id_producer = ?
					  , ordr = ?
					  , is_view_main = ?
					  , is_active = ?
					  , chpu = ?
					  , num_stock = ?
					  , description_for_product = ? 
					  , keywords_for_product = ? 
					  " . $img_sql . "
 	           	WHERE id = ?
			", $name
			 , $article
			 , $alternative_title
			 , $alt_text
			 , $cost_excess
			 , $discount
			 , $description
			 , $description_table
			 , $producer
			 , $id_producer
			 , $ordr
			 , $is_view_main
			 , $is_active
			 , $chpu
			 , $num_stock
			 , $description_for_product
			 , $keywords_for_product
			 , $id );
		} else {
			while (true) {
				if ($db->get_one("SELECT id FROM " . CFG_DBTBL_MOD_PRODUCT . " WHERE chpu = ?", $chpu)) {
					$chpu .= '_';
					continue;
				}
				break;
			}

			// Добавляем
			$sql = sql_placeholder("
				INSERT
				INTO " . CFG_DBTBL_MOD_PRODUCT . "
		           	SET name = ?
					  , article = ?
					  , alternative_title = ?
					  , alt_text = ?
					  , cost_excess = ?
					  , discount = ?
					  , description = ?
					  , description_table = ?
					  , producer = ?
					  , id_producer = ?
					  , ordr = ?
					  , is_view_main = ?
					  , is_active = ?
            		  , chpu = ?
					  , num_stock = ?
					  , description_for_product = ? 
					  , keywords_for_product = ? 
			", $name
			 , $article
			 , $alternative_title
			 , $alt_text
			 , $cost_excess
			 , $discount
			 , $description
			 , $description_table
			 , $producer
			 , $id_producer
			 , $ordr
			 , $is_view_main
			 , $is_active
			 , $chpu
			 , $num_stock
			 , $description_for_product
			 , $keywords_for_product
			 
			 );
		}

		//echo $sql;
		//die();
		
		$db->query($sql);
		$new_id = ($type == 2 && $id) ? $id : $db->insert_id;

		
		// Категории
		// Очистим категории к которым имеет отношение продукт
		$db->query("DELETE FROM " . CFG_DBTBL_MOD_CATEGORY_PRODUCT . " WHERE id_product = ?", $new_id);

		// Добавим продукт к категориям
		if (is_array($categories)) {
			foreach($categories as $key => $val) {
				if ($val > 1000) {
					continue;
				}
				$db->query("
					INSERT INTO " . CFG_DBTBL_MOD_CATEGORY_PRODUCT . "
						SET id_category = ?
						  , id_product = ?
				", $val, $new_id);
			}
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
			$db->query("DELETE FROM " . CFG_DBTBL_MOD_PRODUCT_TAGS . " WHERE id_product = ?", $id);

		if (is_array($tags_id)) {
			foreach ($tags_id as $tag) {
				$db->query("INSERT INTO " . CFG_DBTBL_MOD_PRODUCT_TAGS . " SET id_product = ?, id_tag = ?", $new_id, $tag);
			}
		}

		// Установим признак всех
		$db->query("INSERT INTO " . CFG_DBTBL_MOD_TYPE_VIEW_PRODUCT . " SET id_product = ?, id_type_view = 1", $new_id);

		// Установим или удалим признак нового
		if ($is_new) {
			$db->query("INSERT INTO " . CFG_DBTBL_MOD_TYPE_VIEW_PRODUCT . " SET id_product = ?, id_type_view = 2", $new_id);
		} else {
			$db->query("DELETE FROM " . CFG_DBTBL_MOD_TYPE_VIEW_PRODUCT . " WHERE id_product = ? AND id_type_view = 2", $new_id);
		}

		// Установим или удалим признак хита
		if ($is_hit) {
			$db->query("INSERT INTO " . CFG_DBTBL_MOD_TYPE_VIEW_PRODUCT . " SET id_product = ?, id_type_view = 3", $new_id);
		} else {
			$db->query("DELETE FROM " . CFG_DBTBL_MOD_TYPE_VIEW_PRODUCT . " WHERE id_product = ? AND id_type_view = 3", $new_id);
		}
		
		// Установим или удалим признак акционного товара
		if ($is_promo) {
			$db->query("INSERT INTO " . CFG_DBTBL_MOD_TYPE_VIEW_PRODUCT . " SET id_product = ?, id_type_view = 4", $new_id);
		} else {
			$db->query("DELETE FROM " . CFG_DBTBL_MOD_TYPE_VIEW_PRODUCT . " WHERE id_product = ? AND id_type_view = 4", $new_id);
		}

		Location($_XFA['main'], 0);
	} else {
		Location(sprintf($_XFA['formf'], $FORM_ERROR, $type, $id), 0);
	}

?>