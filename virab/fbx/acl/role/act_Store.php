<?php

	$attributes = inputCheckpoint($attributes, array(
		'id' => array(
			'type' => 'int'
		),
		'type' => array(
			'type' => 'int'
		),
		'parent_id' => array(
			'type' => 'int'
		),
		'name' => array(
			'type' => 'array',
			'trim' => true
		)
	));

	$id  = $attributes['id'];
	$type = $attributes['type'];
	$parent_id = $attributes['parent_id'];
	$name = $attributes['name'];

	$configTable = $auth_in->store->getConfig();

	$params = array(
		'name' => $name
	);

	/**
	 * =====================================================================
	 * Проверки
	 * =====================================================================
	 */

	// Наименование
	if ($name[$lng->deflt_lng] == '') {
		$FORM_ERROR = _("Необходимо указать наименование") . "<br />";
	}

	/**
	 * =====================================================================
	 * Обновление / создание
	 * =====================================================================
	 */
	if (!$FORM_ERROR) {
		$name = $lng->SetTextlng($attributes['name']);

		if ($type == 2 && $id) {
			// Редактирование
			 $db->update($configTable['roleTable'], array(
			 	'name' => $name
			 ), array(
				'id' => $id
			 ));
		} else {
			// Добавление
			$parents = $id ? array($id) : array();
			$auth_in->acl->addRole(new MilKit_Acl_Role($auth_in->store->newRoleId(), array('name' => $name)), $parents);
		}

		Location($_XFA['main'], 0);

	} else {
		// Передача данных
		$params['str_error'] = $FORM_ERROR;
		Location(sprintf($_XFA['formf'], 1, $type, $parent_id, $id, serialize($params)), 0);
	}

?>