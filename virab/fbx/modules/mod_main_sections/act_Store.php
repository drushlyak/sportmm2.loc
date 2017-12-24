<?php

	// Обработка входных данных
	$attributes = inputCheckpoint($attributes, array(
		'id' => array(
			'type' => 'int'
		),
		'type' => array(
			'type' => 'int'
		),
		'page' => array(
			'type' => 'int'
		),
		'url' => array(
			'type' => 'string',
			'trim' => true
		),
		'text' => array(
			'type' => 'string',
			'trim' => true
		),
		'products' => array(
			'type' => 'array'
		)
	));

	$id  = $attributes['id'];
	$type = $attributes['type'];
	$page = $attributes['page'];
	$url = $attributes['url'];
	$text = $attributes['text'];
	$products = $attributes['products'];

	// Проверки
	if (empty($url)) {
		$FORM_ERROR = "<br />" . _("Необходимо указать адрес ссылки") . "<br />";
	}
	if (empty($text)) {
		$FORM_ERROR = "<br />" . _("Необходимо указать текст ссылки") . "<br />";
	}
	

	if (!$FORM_ERROR) {
	
	
		

		if ($type == 2 && $id) {
			
		// удалим связанные товары
		$db->delete(CFG_DBTBL_MOD_MAIN_SECTIONS_PRODUCT, array(
			'id_section' => $id
		));

		// заново создадим список товаров
		if (is_array($products)) {
			
			foreach ($products as $id_product => $product_data) {
				$db->insert(CFG_DBTBL_MOD_MAIN_SECTIONS_PRODUCT, array(
					'id_section' => $id,
					'id_product' => $id_product
					
				));
				
			}
		}
		
			// Редактируем
			$sql = sql_placeholder("
				UPDATE " . CFG_DBTBL_MOD_MAIN_SECTIONS . "
		           	SET text = ?
					  , url = ?
					  , page = ?
					WHERE id = ?
			", $text
			 , $url
			 , $page
			 , $id );
			 
		} else {
		
			$ord = $db->get_one("SELECT MAX(ord) FROM " . CFG_DBTBL_MOD_MAIN_SECTIONS);
			$ord = $ord + 1;
			// Добавляем
			$sql = sql_placeholder("
				INSERT
				INTO " . CFG_DBTBL_MOD_MAIN_SECTIONS . "
		           	SET text = ?
					  , url = ?
					  , page = ?
					  , ord = ?
			", $text
			 , $url
			 , $page
			 , $ord);
		}
		
		$db->query($sql);
		
		$id = $db->insert_id;
		// создадим список товаров
		if (is_array($products)) {
			
			foreach ($products as $id_product => $product_data) {
				$db->insert(CFG_DBTBL_MOD_MAIN_SECTIONS_PRODUCT, array(
					'id_section' => $id,
					'id_product' => $id_product
					
				));
				
			}
		}
		
		Location($_XFA['main'], 0);
	} else {
		Location(sprintf($_XFA['formf'], $FORM_ERROR, $type, $id), 0);
	}

?>