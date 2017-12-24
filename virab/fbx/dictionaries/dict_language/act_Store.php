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
			'type' => 'array'
		),
		'ind_name' => array(
			'type' => 'string',
			'trim' => true
		),
		'deflt' => array(
			'type' => 'int'
		),
		'locale' => array(
			'type' => 'string',
			'trim' => true
		),
		'flag' => array(
			'type' => 'int'
		)
	));

	$id  = $attributes['id'];
	$type = $attributes['type'];
	$name = $attributes['name'];
	$ind_name = $attributes['ind_name'];
	$deflt = $attributes['deflt'];
	$locale = $attributes['locale'];
	$flag = $attributes['flag'];

	$params = array(
		'name' => $name,
		'ind_name' => $ind_name,
		'deflt' => $deflt,
		'locale' => $locale,
		'flag' => $flag
	);

	// Проверки
	if (empty($name[$lng->deflt_lng])) {
		$FORM_ERROR .= _("Необходимо указать название для языка по-умолчанию")  . "<br />";
	}
	if (empty($ind_name)) {
		$FORM_ERROR .= _("Необходимо указать сигнатуру языка") . "<br />";
	}
	if (empty($locale)) {
		$FORM_ERROR .= _("Необходимо указать локаль языка") . "<br />";
	}
	if ($flag === 0) {
		$FORM_ERROR .= _("Необходимо выбрать флаг") . "<br />";
	}

	if (!$FORM_ERROR) {
		$name = $lng->Settextlng($name);

		// если меняется язык по-умолчанию очистим метку о текущем дефолте
		if ($deflt) {
			$ss = $db->update(CFG_DBTBL_DICT_LANGUAGE, array(
				'deflt' => '0'
			), array(
				'deflt' => '1'
			));
		}

		if ($type == 2 && $id) {
			// Редактируем
			$db->update(CFG_DBTBL_DICT_LANGUAGE, array(
				'name' => $name,
				'ind_name' => $ind_name,
				'deflt' => $deflt,
				'locale' => $locale,
				'flag' => $__COUNTRY_FLAGS[$flag]['path']
			), array(
				'id' => $id
			));
		} else {
			// Добавляем
			$db->insert(CFG_DBTBL_DICT_LANGUAGE, array(
				'name' => $name,
				'ind_name' => $ind_name,
				'deflt' => $deflt,
				'locale' => $locale,
				'flag' => $__COUNTRY_FLAGS[$flag]['path']
			));
		}

		Location($_XFA['main'], 0);
	} else {
		// Передача данных
		$params['str_error'] = $FORM_ERROR;
		Location(sprintf($_XFA['formf'], 1, $type, $id, serialize($params)), 0);
	}

?>