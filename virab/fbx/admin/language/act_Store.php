<?php

	$attributes = inputCheckpoint($attributes, array(
		'id' => array(
			'type' => 'int'
		),
		'type' => array(
			'type' => 'int'
		),

		'msgid' => array(
			'type' => 'string',
			'trim' => true
		),
		'lng_construct' => array(
			'type' => 'string',
			'trim' => true
		)
	));

	$id = $attributes['id'];
	$type = $attributes['type'];

	$msgid = $attributes['msgid'];
	$lng_construct = $attributes['lng_construct'];

	// передать идентификатор по которому из сессии вытащатся все переменные
	$params = array(
		'dataHash' => md5(date('d.m.Y H:s:i'))
	);

	$_SESSION['formdata'][$params['dataHash']] = array(
		'msgid' => $msgid,
		'lng_construct' => $lng_construct,

		'str_error' => ''
	);

	/**
	 * =====================================================================
	 * Проверки
	 * =====================================================================
	 */

	if ($lng_construct[$lng->deflt_lng] == '') {
		$FORM_ERROR .= _("Необходимо указать значение языковой переменной для языка по-умолчанию") . "<br />";
	}

	if (empty($msgid) && $type !== 2) {
		$FORM_ERROR .= _("Необходимо указать хэш") . "<br />";
	}

	/**
	 * =====================================================================
	 * Обновление / создание
	 * =====================================================================
	 */

	if (!$FORM_ERROR) {

		if ($type !== 2) {
			$lng_construct['msgid'] = $msgid;
		}

		$lng->set($lng_construct);

		Location($_XFA['main'], 0);

	} else {
		// Передача данных
		$_SESSION['formdata'][$params['dataHash']]['str_error'] = $FORM_ERROR;
		Location(sprintf($_XFA['formf'], 1, $type, $id, serialize($params)), 0);
	}

?>