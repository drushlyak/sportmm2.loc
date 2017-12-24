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
			'type' => 'string',
			'trim' => true
		)	
		
		
	));
	print_r($attributes);
	
	
	$id  = $attributes['id'];
	$type = $attributes['type'];
	$name = $attributes['name'];
	
	

	$params = array(
		'name' => $name	
	);

	/**
	 * =====================================================================
	 * Проверки
	 * =====================================================================
	 */


	if (empty($name)) {
		$FORM_ERROR .= _("Необходимо указать название категории") . "<br />";
	}
	
	if (!$FORM_ERROR) {

		$new_data = array(
				'name' 			=> $name
			);
			

		if ($type == 2 && $id) {
			$idClient = $db->update(CFG_DBTBL_MOD_CATEGORY_ARTICLES, $new_data, array('id' => $id));
		} else {
			$idClient = $db->insert(CFG_DBTBL_MOD_CATEGORY_ARTICLES, $new_data);
		}

		Location($_XFA['main'], 0);
	} else {
		Location(sprintf($_XFA['formf'], $FORM_ERROR, $type, $id), 0);
	}

?>