<?php

	// Обработка входных данных
	$attributes = inputCheckpoint($attributes, array(
		'id' => array(
			'type' => 'int'
		),
		'type' => array(
			'type' => 'int'
		),
		'phone' => array(
			'type' => 'string',
			'trim' => true
		),

		'email' => array(
			'type' => 'string',
			'trim' => true
		),
		'password' => array(
			'type' => 'string',
			'trim' => true
		),
		'password_check' => array(
			'type' => 'string',
			'trim' => true
		),
		'f_name' => array(
			'type' => 'string',
			'trim' => true
		),
		'i_name' => array(
			'type' => 'string',
			'trim' => true
		),
		'receive_mail' => array(
			'type' => 'int'
		)
		
		
	));
	
	$id  = $attributes['id'];
	$type = $attributes['type'];
	$phone = $attributes['phone'];
	$email = $attributes['email'];
	$password = $attributes['password'];
	$password_check = $attributes['password_check'];
	$f_name = $attributes['f_name'];
	$i_name = $attributes['i_name'];
	$receive_mail = $attributes['receive_mail'];

	$params = array(
		'phone' => $phone,
		'email' => $email,
		'f_name' => $f_name,
		'i_name' => $i_name,
		'password' => $password,
		'password_check' => $password_check,
		'receive_mail' => $receive_mail		
	);

	/**
	 * =====================================================================
	 * Проверки
	 * =====================================================================
	 */

	// Основной телефон (логин)
	if (empty($phone)) {
		$FORM_ERROR .= _("Необходимо указать номер телефона") . "<br />";
	}
	if ($type == 1 && $db->get_one(sql_placeholder("SELECT id FROM " . CFG_DBTBL_MOD_CLIENT . " WHERE email = ?", $email))) {
		$FORM_ERROR = _("Данный телефон уже используется") . "<br />";
	}
	// Пароль
	if ($type == 2 && (!empty($password) && !empty($password_check)) && $password !== $password_check) {
		$FORM_ERROR .= _("Пароли не совпадают") . "<br />";
	} elseif ($type == 1 && (empty($password) || empty($password_check))) {
		$FORM_ERROR .= _("Необходимо заполнить поле пароля") . "<br />";
	} elseif ($type == 1 && $password !== $password_check) {
		$FORM_ERROR .= _("Пароли не совпадают") . "<br />";
	} elseif (strlen($password) < 5 && strlen($password) != 0) {
		$FORM_ERROR .= _("Длина пароля должна быть не менее 5 символов") . "<br />";
	}
	// Email
	if (empty($email)) {
		$FORM_ERROR .= _("Необходимо указать email адрес") . "<br />";
	} elseif (!validEmail($email)) {
      	$FORM_ERROR .= _("Неверный формат email адреса") . "<br />";
	}
	

	if (!$FORM_ERROR) {

		$new_data = array(
				'phone' 			=> $phone,
				'email' 			=> $email,
				'f_name' 			=> $f_name,
				'i_name' 			=> $i_name,
				'receive_mail' 		=> $receive_mail				
			);
			if (!empty($password)) {
				$new_data = array_merge($new_data, array('password' => md5($password)));
			}

		if ($type == 2 && $id) {
			$idClient = $db->update(CFG_DBTBL_MOD_CLIENT, $new_data, array('id' => $id));
		} else {
			$new_data = array_merge($new_data, array('date_reg' => date('Y-d-m h:i:s')));
			$idClient = $db->insert(CFG_DBTBL_MOD_CLIENT, $new_data);
		}

		Location($_XFA['main'], 0);
	} else {
		Location(sprintf($_XFA['formf'], $FORM_ERROR, $type, $id), 0);
	}

?>