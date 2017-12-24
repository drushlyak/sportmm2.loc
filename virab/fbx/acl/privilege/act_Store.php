<?php

	$id             = intval($attributes['id']);
	$typ 			= intval($attributes['typ']);
	$var 			= $attributes['var'];
	
	$defl_name = trim($attributes['name'][$lng->deflt_lng]);
	if (!$defl_name) {
		$FORM_ERROR = _("Необходимо указать название роли для языка по-умолчанию");
	}

	$configTable = $auth_in->store->getConfig();

	// Проверка на дубликат var
	if ($db->get_one("SELECT COUNT(*) FROM {$configTable['privilegeTable']} WHERE var = ?", $var)) {
		$FORM_ERROR = _("Указанное наименование константы уже используется!");
	}
	
	if (!$FORM_ERROR) {
		$name = $lng->SetTextlng($attributes['name']);
		if ($typ != 2) {
			// добавляем новое значение
			$sql = sql_placeholder("
				INSERT INTO {$configTable['privilegeTable']}
					SET name = ?
					  , var = ?
			", $name, $var);
		} else {
			$sql = sql_placeholder("
				UPDATE {$configTable['privilegeTable']} 
					SET name = ?
					  , var = ?
				WHERE id = ?
			", $name, $var, $id);
		}
		
		$db->query($sql);		
		Location($_XFA['main'], 0);
	} else {
		Location(sprintf($_XFA['formf'], $typ, $id, $FORM_ERROR), 0);
	}

?>