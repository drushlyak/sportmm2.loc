<?php

	$table_config = array(
		"id"				=> 't_nav_grid',
		"type"				=> 'tree',
		"url"				=> $_XFA['main'],
		"nodeSet"			=> $nodeSet,
		"resID"				=> $resourceId,
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> true,
		"action_nodes"		=> true,
		"form_action"		=> sprintf($_XFA['delete'], 0),
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('width' => '30%', 'title' => _('Название'), 'html' => _('Название')),
			array('width' => '40%', 'title' => _('Описание'), 'html' => _('Описание')),
			array('width' => '10%', 'title' => _('Переменная'), 'html' => _('Переменная'))
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "Название"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	global $lng;
						return "<span title=\"" . $node[\'url\'] . "\">" . $lng->Gettextlng($node[\'title\']). "</span>";'
				)
			),
			// Функция поля "Описание"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	global $lng;
						return mb_substr($lng->Gettextlng($node["quick_help"]),0,45, "UTF-8") . "...";'
				)
			),
			// Функция поля "Переменная"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'return $node["var"];'
				)
			)
		),
		// Настройка элементов действий над нодами (обязательны при action_nodes = true)
		"action_nodes_cgf" 	=> array(
			// CREATE
			array(
				"acl_rule"	=> CREATE,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['form'], 1, '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/newusr.gif',
				"title"		=> _('Новый объект')
			),
			// EDIT
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['form'], 2, '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/ed.gif',
				"title"		=> _('Редактировать')
			),
			// CHANGE_PARENT
			array(
				"acl_rule"	=> CHANGE_PARENT,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['ch_parent'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/edgr.gif',
				"title"		=> _('Поменять родителя')
			),
			// CHANGE_POSITION_UP
			array(
				"acl_rule"	=> CHANGE_POSITION,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['ch_pos_top'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/top.gif',
				"title"		=> _('Переместить узел выше')
			),
			// CHANGE_POSITION_DOWN
			array(
				"acl_rule"	=> CHANGE_POSITION,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['ch_pos_bottom'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/bottom.gif',
				"title"		=> _('Переместить узел ниже')
			),
			// DELETE
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['delete'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/del.gif',
				"title"		=> _('Удалить'),
				"confirm"	=> _('Вы уверены, что хотите удалить данный объект?')
			)
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['form'], 1, 0),
				"html"		=> _('Создать раздел меню<br>верхнего уровня')
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> _('Удалить выбранные<br>разделы меню'),
				"confirm"	=> _('Вы уверены что хотите удалить выделенные разделы?')
			)
		)
	);

?><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<?php if($attributes['str_error']): ?>
	<p class="cerr"><?=$attributes['str_error']?></p>
<?php endif;?>

<?php if (!$auth_in->isAllowed()): ?>
	<p class="cerr"><?=$ACL_ERROR?></p>
<?php return; endif; ?>

<?php
	$dsp_helper->writeTable($table_config);
?>
