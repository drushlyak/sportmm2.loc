<?php
	/**
	 * =====================================================================
	 * Обработка входных данных
	 * =====================================================================
	 */

	$attributes = inputCheckpoint($attributes, array(
		'id_product' => array(
			'type' => 'int'
		),
		'products' => array(
			'type' => 'array'
		)
	));

	$id_product  = $attributes['id_product'];
	$products = $attributes['products'];

	$params = array(
		'products' => $products
	);

	/**
	 * =====================================================================
	 * Проверки
	 * =====================================================================
	 */

	if (!is_array($products)) {
		$FORM_ERROR .= _("Необходимо выбрать продукты") . "<br />";
	}

	if (!$FORM_ERROR) {

		// Добавим продукты
		if (is_array($products)) {
			foreach($products as $key => $val) {
				$db->query("
					INSERT INTO " . CFG_DBTBL_MOD_PRODUCT_RECOMMENDED . "
						SET id_recommended_product = ?
						  , id_product_itself = ?
				", $val, $id_product);
			}
		}

		Location(sprintf($_XFA['recommended'], $id_product), 0);

	} else {
		// Передача данных
		$params['str_error'] = $FORM_ERROR;
		Location(sprintf($_XFA['recommended_formf'], 1, $id_product, serialize($params)), 0);
	}

?>