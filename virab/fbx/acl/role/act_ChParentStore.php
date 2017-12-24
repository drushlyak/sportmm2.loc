<?php

	$attributes = inputCheckpoint($attributes, array(
		'id' => array(
			'type' => 'int'
		),
		'parent_id' => array(
			'type' => 'int'
		),
		'is_main_role' => array(
			'type' => 'int'
		)
	));

	$id  = $attributes['id'];
	$parent_id = $attributes['parent_id'];
	$is_main_role = $attributes['is_main_role'];

	$configTable = $auth_in->store->getConfig();

	if ($id) {
		// удалим старую привязку к родителю
		$db->delete($configTable['roleRefTable'], array(
			'role_id' => $id
		));
	}

	if ($id && $parent_id && !$is_main_role) {

		$db->insert($configTable['roleRefTable'], array(
			'parent' => $parent_id,
			'role_id' => $id
		));
	}

	Location($_XFA['main'], 0);


?>