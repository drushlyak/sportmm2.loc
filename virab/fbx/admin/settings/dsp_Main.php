<?php
	$table_config = array(
		"id"				=> $tableID,
		"type"				=> 'list',
		"pager"				=> $datapager,
		"hide_pager"		=> false,
		"url"				=> sprintf($_XFA['main']),
		"nodeSet"			=> $dataSet,
		"resID"				=> $resourceId,
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> true,
		"action_nodes"		=> true,
		"sorting"			=> true,
		"form_action"		=> sprintf($_XFA['delete'], 0),
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('name' => 'var', 'width' => '20%', 'title' => _('Переменная'), 'html' => _('Переменная'), 'sorting' => true),
			array('name' => 'val', 'width' => '30%', 'title' => _('Превью значения (сокращенное до 50 символов)'), 'html' => _('Значение')),
			array('name' => 'des', 'width' => '30%', 'title' => _('Превью описания'), 'html' => _('Описание'))
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "Переменная"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return "<b>" . $node["config_name"] . "</b>";'
				)
			),
			// Функция поля "Значение"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	mb_internal_encoding("UTF-8");
						$txt = $node["config_value"];

						return "<b>" . ((strlen(trim($txt)) > 50) ?
							mb_substr($txt, 0, 50) . "..."
							:
							$txt ) . "</b>";
					'
				)
			),
			// Функция поля "Описание"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	mb_internal_encoding("UTF-8");
						$txt = $node["description"];

						return (strlen(trim($txt)) > 50) ?
							mb_substr($txt, 0, 50) . "..."
							:
							$txt;
					'
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
				"img_src"	=> 'images/but/del.gif',
				"title"		=> _('Удалить параметр'),
				"confirm"	=> _('Вы уверены, что хотите удалить данное значение?')
			)
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['form'], 1, 0),
				"html"		=> _('Добавить <br>переменную')
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> _('Удалить выбранные<br>переменные'),
				"confirm"	=> _('Вы уверены что хотите удалить выделенные переменные?')
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