<?php

	$table_config = array(
		"id"				=> 't_site_template_grid',
		"type"				=> 'tree',
		"url"				=> $_XFA['main'],
		"nodeSet"			=> $nodeSet,
		"resID"				=> $resourceId,
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> false,
		"action_nodes"		=> true,
		"form_action"		=> sprintf($_XFA['delete'], 0),
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('width' => '50%', 'title' => _('Шаблон'), 'html' => _('Шаблон')),
			array('width' => '5%', 'title' => _('Переменная'), 'html' => _('Переменная')),
			array('width' => '15%', 'title' => _('Тип'), 'html' => _('Тип')),
			array('width' => '5%', 'title' => _('Статус'), 'html' => _('Статус')),
			array('width' => '5%', 'title' => _('Селективный'), 'html' => _('Селек.'))
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "Шаблон"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	global $lng, $__TYPE_EXECUTOR;
						// Отметим цветом те шаблоны, которые имеют необходимую, но незаполненную область
						if ($node["name"]) {
							if (
								$node["code"] ||
								(
									($node["type_template"] == TE_VALUE_EXECUTOR) &&
									($__TYPE_EXECUTOR[$node["type_executor"]]["form"] == TE_EXECUTOR_SCREEN_WYSIWYG)
								)
								|| ($node["type_template"] == TE_VALUE_FOLDER)
								|| ($node["type_template"] == TE_VALUE_SELECT)
							) {
								$name = $lng->Gettextlng($node["name"]);
							} else {
								$name = "<span class=\"template_error\" title=\"' . _("Необходимо определить параметры данного контейнера, так как без этого не сможет построиться шаблон!") . '\">" . $lng->Gettextlng($node["name"]) . "</span>";
							}

							// копия -->
	        				if ($node["double_id"] && ($node["double_id"] != $node["id"])) {
								$name = "<span class=\"template_copy\" title=\"' . _("Будьте внимательны. Это копия!") . '\">" . $lng->Gettextlng($node["name"]) . "</span>";
							}
						} else {
							$name = "<span class=\"template_error\" title=\"' . _("Необходимо определить параметры данного контейнера, так как без этого не сможет построиться шаблон!") . '\">' . _("Неопределенный контейнер") . '</span>";

							// копия незаданного шаблона -->
	        				if ($node["double_id"] && ($node["double_id"] != $node["id"])) {
								$name = "<span class=\"template_error\" title=\"' . _("Будьте внимательны. Это копия!") . '\">' . _("Копия неопределенного контейнера") . '</span>";
							}
						}
						return $name;
					'
				)
			),
			// Функция поля "Переменная"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return getTeValueName($node["id_te_value"]);'
				)
			),
			// Функция поля "Тип"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	global $__TYPE_EXECUTOR, $__TYPE_TE_VALUE;
						$title = ($node["type_template"] == TE_VALUE_EXECUTOR && $node["type_executor"] == TE_EXECUTOR_FILE) ? $node["code"] : "";
						return "<span title=\"" . $title . "\">" . (($node["type_template"] == TE_VALUE_EXECUTOR) ? $__TYPE_EXECUTOR[$node["type_executor"]]["text"] : $__TYPE_TE_VALUE[$node["type_template"]]["text"]) . "</span>";
					'
				)
			),
			// Функция поля "Статус"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	$res_str = "";

						if ($node["printable"])
							$res_str .= "<img src=\"images/but/printable_yes.gif\" border=0 alt=\"' . _("Страница будет иметь пункт для вывода ее на печать") . '\" title=\"' . _("Страница будет иметь пункт для вывода ее на печать") . '\">";
						else
							$res_str .= "<img src=\"images/but/printable_not.gif\" border=0 alt=\"' . _("Страница недоступна для печати") . '\" title=\"' . _("Страница недоступна для печати ") . '\">";

						if ($node["double_id"] && ($node["double_id"] != $node["id"])) {
							$res_str .= "<img src=\"images/but/copy_template.gif\" border=0 alt=\"' . _("Копия шаблона") . '\" title=\"' . _("Копия шаблона") . '\">";
						}
						return $res_str;
					'
				)
			),
			// Функция поля "Селективный"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["is_selective"] ? "<img src=\"images/but/yes.gif\" border=0>" : "<img src=\"images/but/nor.gif\" border=0>" ;'
				)
			)
		),
		// Настройка элементов действий над нодами (обязательны при action_nodes = true)
		"action_nodes_cgf" 	=> array(
			// Добавить папку
			array(
				"acl_rule"	=> CREATE,
				"res_innod"	=> true,
				"href"		=> sprintf($_XFA['form'], 1, '!node_id!'),
				"check_func"=> create_function(
					'$node',
					'return ($node["type_template"] == TE_VALUE_FOLDER);'
				),
				"has_id"	=> true,
				"img_src"	=> 'images/but/newusr.gif',
				"title"		=> _('Добавить элемент в папку')
			),
			// Редактировать коды текстов в БД для этого исполнителя
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> true,
				"href"		=> sprintf($_XFA['mainwcode'], '!node_id!'),
				"check_func"=> create_function(
					'$node',
					'	global $__TYPE_EXECUTOR;
						return ($node["type_template"] == TE_VALUE_EXECUTOR && $__TYPE_EXECUTOR[$node["type_executor"]]["form"] == TE_EXECUTOR_SCREEN_WYSIWYG);'
				),
				"has_id"	=> true,
				"img_src"	=> 'images/but/addbaserecord.gif',
				"title"		=> _('Редактировать коды текстов в БД для этого исполнителя')
			),
			// Редактировать
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> true,
				"href"		=> sprintf($_XFA['form'], 2, '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/ed.gif',
				"title"		=> _('Редактировать')
			),
			// Поменять родителя
			array(
				"acl_rule"	=> CHANGE_PARENT,
				"res_innod"	=> true,
				"href"		=> sprintf($_XFA['ch_parent'], '!node_id!'),
				"check_func"=> create_function(
					'$node',
					'return $node["can_move"];'
				),
				"has_id"	=> true,
				"img_src"	=> 'images/but/edgr.gif',
				"title"		=> _('Поменять родителя')
			),
			// Удалить
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> true,
				"href"		=> sprintf($_XFA['delete'], '!node_id!'),
				"check_func"=> create_function(
					'$node',
					'return $node["can_delete"];'
				),
				"has_id"	=> true,
				"img_src"	=> 'images/but/del.gif',
				"title"		=> _('Удалить'),
				"confirm"	=> _('Вы уверены что хотите удалить данный шаблон?')
			)
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['form'], 1, $parent_id),
				"html"		=> _('Добавить<br>шаблон')
			)
		)
	);

?><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


<?php if(isset($attributes['str_error']) && $attributes['str_error']): ?>
	<p class="cerr"><?=$attributes['str_error']?></p>
<?php endif;?>

<?php if (!$auth_in->isAllowed()): ?>
	<p class="cerr"><?=$ACL_ERROR?></p>
<?php return; endif; ?>

<?php
	$dsp_helper->writeTable($table_config);
?>
