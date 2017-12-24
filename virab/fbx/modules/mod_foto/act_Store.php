<?php
	/**
	 * =====================================================================
	 * Обработка входных данных
	 * =====================================================================
	 */
//print_r($attributes);
//die();
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
		'value' => array(
			'type' => 'string',
			'trim' => true
		),
		'description' => array(
			'type' => 'string',
			'trim' => true
		),
		'code' => array(
			'type' => 'int'
		),
		'count_per_page' => array(
			'type' => 'int'
		),
		'resize' => array(
			'type' => 'int'
		),
		'crop_img' => array(
			'type' => 'int'
		),
		'width_img' => array(
			'type' => 'int'
		),
		'height_img' => array(
			'type' => 'int'
		),
		'quality_img' => array(
			'type' => 'int'
		),
		'auto_tmb' => array(
			'type' => 'int'
		),
		'crop_tmb' => array(
			'type' => 'int'
		),
		'width_tmb' => array(
			'type' => 'int'
		),
		'height_tmb' => array(
			'type' => 'int'
		),
		'quality_tmb' => array(
			'type' => 'int'
		),
		'orig_img' => array(
			'type' => 'int'
		)
	));

	$id  = $attributes['id'];
	$type = $attributes['type'];

	$name = $attributes['name'];
	$description = $attributes['description'];
	$value = $attributes['value'];
	$code = $attributes['code'];
	$count_per_page = $attributes['count_per_page'];
	$resize = $attributes['resize'];
	$crop_img = $attributes['crop_img'];
	$width_img = $attributes['width_img'];
	$height_img = $attributes['height_img'];
	$quality_img = $attributes['quality_img'];
	$auto_tmb = $attributes['auto_tmb'];
	$crop_tmb = $attributes['crop_tmb'];
	$width_tmb = $attributes['width_tmb'];
	$height_tmb = $attributes['height_tmb'];
	$quality_tmb = $attributes['quality_tmb'];
	$orig_img = $attributes['orig_img'];

	$params = array(
		'name' => $name,
		'description' => $description,
		'value' => $value,
		'code' => $code,
		'count_per_page' => $count_per_page,
		'resize' => $resize,
		'crop_img' => $crop_img,
		'width_img' => $width_img,
		'height_img' => $height_img,
		'quality_img' => $quality_img,
		'auto_tmb' => $auto_tmb,
		'crop_tmb' => $crop_tmb,
		'width_tmb' => $width_tmb,
		'height_tmb' => $height_tmb,
		'quality_tmb' => $quality_tmb,
		'orig_img' => $orig_img
	);

	/*if ($resize) {
		$auto_tmb = 0;
	}*/

	/**
	 * =====================================================================
	 * Проверки
	 * =====================================================================
	 */
	// Имя
	if (empty($name)) {
		$FORM_ERROR .= _("Необходимо указать наименование") . "<br />";
	}
	// Переменная
	if (empty($value)) {
		$FORM_ERROR .= _("Необходимо указать переменную") . "<br />";
	} else {
		if ($type != 2 || !$id) {
			$sql = sql_placeholder("SELECT id FROM " . CFG_DBTBL_TE_VALUE . " WHERE name = ?", $value);
			$result = $db->get_one($sql);

			if ($result) {
				$FORM_ERROR .= _("Переменная с таким именем уже существует. Укажите другое имя.") . "<br />";
			}
		}
	}
	// Код отображения
	if (empty($code)) {
		$FORM_ERROR .= _("Необходимо выбрать код для отображения") . "<br />";
	}
	// Количество записей на страницу
	if (empty($count_per_page)) {
		$FORM_ERROR .= _("Необходимо указать количество записей отображаемых на одной странице") . "<br />";
	}
	// Если изменять размер
	if (empty($orig_img)) {
		if (empty($width_img) || empty ($height_img) || empty($quality_img)) {
			$FORM_ERROR .= _("Необходимо указать параметры для изменяемого изображения") . "<br />";
		}
		
	}
	if (empty($width_tmb) || empty ($height_tmb) || empty($quality_tmb)) {
			$FORM_ERROR .= _("Необходимо указать параметры для миниатюры изменяемого изображения") . "<br />";
		}
	// Ресайз изображения
	
		
		
		
	
	/**
	 * =====================================================================
	 * Обновление / создание
	 * =====================================================================
	 */
	if (!$FORM_ERROR) {
		$name = $lng->SetTextlng($attributes['name']);
		$description = $lng->SetTextlng($attributes['description']);

		if ($type == 2 && $id) {
			// Редактируем
			$fotoTree->updateNode($id, array('id_te_value'		=> getTeValueId($value)
										   , 'name'				=> $name
										   , 'description'		=> $description
										   , 'code'				=> $code
										   , 'count_per_page'	=> $count_per_page
										  
										   , 'crop_img'			=> $crop_img
										   , 'width_img'		=> $width_img
										   , 'height_img'		=> $height_img
										   , 'quality_img'		=> $quality_img
										   
										   , 'crop_tmb'			=> $crop_tmb
										   , 'width_tmb'		=> $width_tmb
										   , 'height_tmb'		=> $height_tmb
										   , 'quality_tmb'		=> $quality_tmb
										   , 'orig_img'			=> $orig_img

			), 0);
			
		} else {
			if ($id > 1) {
				$top_id = $id;
			} else {
				$rootNode = $fotoTree->getRootNodeInfo();
				$top_id = $rootNode['id'];
			}

			// Записываем шаблонную переменную
			$sql = sql_placeholder("
				INSERT INTO ".CFG_DBTBL_TE_VALUE."
					SET name=?
					  , typ=?
					  , sys=1
			", $value, TE_VALUE_FOTO);

			$db->query($sql);
			$te_value_id = $db->insert_id;

			// Выделяем новый id для дерева ресурсов
			$res_id = $auth_in->store->newResourceId();

			// Определяем родительский id для вставки элемента в дерево ресурсов
			if ($id > 1) {
				$res = $fotoTree->getNode($id, array('res_id'));
				$sql = sql_placeholder("
					SELECT id
					FROM ".$resTree->structTable."
					WHERE data_id=?
				", $res['res_id']);
				$res_top = $db->get_one($sql);
			} else {
				$sql = sql_placeholder("SELECT top_id FROM " . CFG_DBTBL_MODULE . " WHERE var = ?", $module_var);
				$res_top = $db->get_one($sql);
			}

			// Вставляет новую запись в дерево ресурсов
			$resTree->appendChild($res_top, array(), $res_id);

			// Добавляем новый раздел
			$res = $fotoTree->appendChild($top_id, array('id_te_value'    => getTeValueId($value)
										   , 'name'				=> $name
										   , 'description'		=> $description
										   , 'code'				=> $code
										   , 'count_per_page'	=> $count_per_page
										   
										   , 'crop_img'			=> $crop_img
										   , 'width_img'		=> $width_img
										   , 'height_img'		=> $height_img
										   , 'quality_img'		=> $quality_img
										   
										   , 'crop_tmb'			=> $crop_tmb
										   , 'width_tmb'		=> $width_tmb
										   , 'height_tmb'		=> $height_tmb
										   , 'quality_tmb'		=> $quality_tmb
										   , 'res_id'			=> $res_id
										   , 'orig_img'			=> $orig_img
			), 0);
		}

		Location($_XFA['cat_main'], 0);
	} else {
		// Передача данных
		$params['str_error'] = $FORM_ERROR;
		Location(sprintf($_XFA['cat_formf'], 1, $type, $id, serialize($params)), 0);
	}

?>