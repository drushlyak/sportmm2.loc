<?php
	/**
	 * =====================================================================
	 * Обработка входных данных
	 * =====================================================================
	 */

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
		'code' => array(
			'type' => 'int'
		),
		'count_per_page' => array(
			'type' => 'int'
		)
	));

	$id  = $attributes['id'];
	$type = $attributes['type'];

	$name = $attributes['name'];
	$value = $attributes['value'];
	$code = $attributes['code'];
	$count_per_page = $attributes['count_per_page'];

	$params = array(
		'name' => $name,
		'value' => $value,
		'code' => $code,
		'count_per_page' => $count_per_page
	);

	/**
	 * =====================================================================
	 * Проверки
	 * =====================================================================
	 */

	// Имя
	if (empty($name)) {
		$FORM_ERROR = _("Необходимо указать наименование") . "<br />";
	}
	// Переменная
	if (empty($value)) {
		$FORM_ERROR = _("Необходимо указать переменную") . "<br />";
	} else {
		if ($type != 2 || !$id) {
			$sql = sql_placeholder("SELECT id FROM " . CFG_DBTBL_TE_VALUE . " WHERE name = ?", $value);
			$result = $db->get_one($sql);

			if ($result) {
				$FORM_ERROR = _("Переменная с таким именем уже существует. Укажите другое имя.") . "<br />";
			}
		}
	}
	// Код отображения
	if (empty($code)) {
		$FORM_ERROR = _("Необходимо выбрать код для отображения") . "<br />";
	}
	// Количество записей на страницу
	if (empty($count_per_page)) {
		$FORM_ERROR = _("Необходимо указать количество записей отображаемых на одной странице") . "<br />";
	}

	/**
	 * =====================================================================
	 * Обновление / создание
	 * =====================================================================
	 */
	if (!$FORM_ERROR) {
		$name = $lng->SetTextlng($attributes['name']);

		if ($type == 2 && $id) {
			// Редактируем
			$sql = sql_placeholder("
				UPDATE " . CFG_DBTBL_MOD_FEEDBACK_GROUP . "
		           	SET name = ?
		           	  , count_per_page = ?
		           	  , code = ?
 	           	WHERE id = ?
			", $name
			 , $count_per_page
			 , $code
			 , $id );

			$db->query($sql);
			$faq_group_id = $id;
		} else {
			// Записываем шаблонную переменную
			$sql = sql_placeholder("
				INSERT INTO ".CFG_DBTBL_TE_VALUE."
					SET name=?
					  , typ=?
					  , sys=1
			", $value, TE_VALUE_FEEDBACK);

			$db->query($sql);
			$te_value_id = $db->insert_id;

			// Выделяем новый id для дерева ресурсов
			$res_id = $auth_in->store->newResourceId();

			// Определяем родительский id для вставки элемента в дерево ресурсов
			$sql = sql_placeholder("SELECT top_id FROM ".CFG_DBTBL_MODULE." WHERE var = 'mod_feedback'");
			$res_top = $db->get_one($sql);

			// Вставляет новую запись в дерево ресурсов
			$resTree->appendChild($res_top, array(), $res_id);

			// Добавляем новый раздел
			$sql = sql_placeholder("
				INSERT INTO " . CFG_DBTBL_MOD_FEEDBACK_GROUP . "
		           	SET name = ?
		           	  , id_te_value = ?
		           	  , count_per_page = ?
		           	  , code = ?
		           	  , res_id = ?
			", $name
			 , $te_value_id
			 , $count_per_page
			 , $code
			 , $res_id);

			$db->query($sql);
			$feedback_group_id = $db->insert_id;
		}

		Location($_XFA['cat_main'], 0);
	} else {
		// Передача данных
		$params['str_error'] = $FORM_ERROR;
		Location(sprintf($_XFA['cat_formf'], 1, $type, $id, serialize($params)), 0);
	}

?>