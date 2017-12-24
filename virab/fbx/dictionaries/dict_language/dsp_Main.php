<?php
	$table_config = array(
		"id"				=> 't_dict_language_list',
		"type"				=> 'list',
		"hide_pager"		=> true,
		"url"				=> sprintf($_XFA['main']),
		"nodeSet"			=> $dataSet,
		"resID"				=> $resourceId,
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> true,
		"action_nodes"		=> true,
		"form_action"		=> sprintf($_XFA['delete'], 0),
		"row_color"			=> 'color',
		"enable_select_el"	=> create_function(
		 		'$node',
		 		' return !((bool) $node["deflt"]);'
		),
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('width' => '40%', 'title' => _('Язык'), 'html' => _('Язык')),
			array('width' => '15%', 'title' => _('Сигнатура языка'), 'html' => _('Сигнатура')),
			array('width' => '10%', 'title' => _('Флаг языка'), 'html' => _('Флаг')),
			array('width' => '10%', 'title' => _('Локаль языка'), 'html' => _('Локаль языка')),
			array('width' => '15%', 'title' => _('Язык по-умолчанию'), 'html' => _('Язык по-умолчанию')),
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "Язык"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	global $lng; ' .
					'	return $lng->Gettextlng($node["name"]);'
				)
			),
			// Функция поля "Сигнатура"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return "<b>" . $node["ind_name"] . "</b>";'
				)
			),
			// Функция поля "Флаг языка"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["flag"] ? "<img src=\"" . $node["flag"] . "\" border=0>" : "-";'
				)
			),
			// Функция поля "Локаль"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["locale"];'
				)
			),
			// Функция поля "Язык по-умолчанию"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return "<img src=\"images/but/" . ($node["deflt"] ? "yes" : "not") . ".gif\" border=0>";'
				)
			)
		),
		// Настройка элементов действий над нодами (обязательны при action_nodes = true)
		"action_nodes_cgf" 	=> array(
			// EDIT
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['form'], 2, '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/ed.gif',
				"title"		=> _('Редактировать')
			),
			// DELETE
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['delete'], '!node_id!'),
				"has_id"	=> true,
				"check_func"=> create_function(
		 				'$node',
		 				'return !((bool) $node["deflt"] || (bool) $node["deflt_msgid_gettext"]);'
		 		),
				"img_src"	=> 'images/but/del.gif',
				"title"		=> _('Удалить параметр'),
				"confirm"	=> _('Вы уверены, что хотите удалить данный язык?')
			)
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['form'], 1, 0),
				"html"		=> _('Добавить новый<br>язык')
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> _('Удалить выбранные<br>языки'),
				"confirm"	=> _('Вы уверены что хотите удалить выделенные языки?')
			)
		)
	);
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	

	<?php if($attributes['str_error']): ?>
		<p class="cerr"><?=$attributes['str_error']?></p>
	<?php endif;?>

	<?php if (!$auth_in->isAllowed()): ?>
		<p class="cerr"><?=$ACL_ERROR?></p>
	<?php return; endif; ?>

<?php
	$dsp_helper->writeTable($table_config);
?>