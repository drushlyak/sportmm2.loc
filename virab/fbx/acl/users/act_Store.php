<?php

	$attributes = inputCheckpoint($attributes, array(
		'id' => array(
			'type' => 'int'
		),
		'type' => array(
			'type' => 'int'
		),

		'login' => array(
			'type' => 'string',
			'trim' => true
		),
		'full_name' => array(
			'type' => 'string',
			'trim' => true
		),
		'password' => array(
			'type' => 'string'
		),
		'password_check' => array(
			'type' => 'string'
		),
		'email' => array(
			'type' => 'string',
			'trim' => true
		),
		'role_id' => array(
			'type' => 'int'
		)
	));

	$id  = $attributes['id'];
	$type = $attributes['type'];

	$login = $attributes['login'];
	$full_name = $attributes['full_name'];
	$password = $attributes['password'];
	$password_check = $attributes['password_check'];
	$email = $attributes['email'];
	$role_id = $attributes['role_id'];

	// передать идентификатор по которому из сессии вытащатся все переменные
	$params_hash = md5(date('d.m.Y H:s:i'));

	$_SESSION['formdata'][$params_hash] = array(
		'login'			=> $login,
		'full_name' 	=> $full_name,
		'email' 		=> $email,
		'role_id' 		=> $role_id
	);

	/**
	 * =====================================================================
	 * Проверки
	 * =====================================================================
	 */

	if (empty($login)) {
		$FORM_ERROR .= _("Необходимо указать логин пользователя") . "<br />";
	}
//	if (!validLogin($login)) {
//		$FORM_ERROR .= _("Неверный формат логина. Разрешены только латинские буквы, цифры и _") . "<br />";
//	}
	if ($type == 1 && $db->get_one(sql_placeholder("SELECT id FROM " . CFG_DBTBL_UDATA . " WHERE login = ?", $login))) {
		$FORM_ERROR .= _("Данный логин уже используется") . "<br />";
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

	if (empty($email)) {
		$FORM_ERROR .= _("Необходимо указать email пользователя") . "<br />";
	}
// elseif (!validEmail($email)) {
//      	$FORM_ERROR .= _("Неверный формат email адреса") . "<br />";
//	}

	if ($full_name[$lng->deflt_lng] == '') {
		$FORM_ERROR .= _("Необходимо указать ФИО пользователя") . "<br />";
	}

	if ($role_id === 0) {
		$FORM_ERROR .= _("Необходимо выбрать роль для пользователя") . "<br />";
	}


	/**
	 * =====================================================================
	 * Обновление / создание
	 * =====================================================================
	 */

	if (!$FORM_ERROR) {
		$full_name = $lng->Settextlng($full_name);

		if ($type == 2 && $id) {
			// Редактируем
			if (!empty($password)) {
				// с паролем
				$db->update(CFG_DBTBL_UDATA, array(
					'login' => $login,
					'password' => md5($password),
					'role_id' => $role_id,
					'full_name' => $full_name,
					'email' => $email
				), array(
					'id' => $id
				));
			} else {
				$db->update(CFG_DBTBL_UDATA, array(
					'login' => $login,
					'role_id' => $role_id,
					'full_name' => $full_name,
					'email' => $email
				), array(
					'id' => $id
				));
			}

		} else {
			// Добавляем
			$db->insert(CFG_DBTBL_UDATA, array(
				'login' => $login,
				'password' => md5($password),
				'role_id' => $role_id,
				'full_name' => $full_name,
				'email' => $email
			));
		}

		Location($_XFA['main'], 0);

	} else {
		// Передача данных
		$_SESSION['formdata'][$params_hash]['str_error'] = $FORM_ERROR;
		Location(sprintf($_XFA['formf'], 1, $type, $id, $params_hash), 0);
	}

?>
