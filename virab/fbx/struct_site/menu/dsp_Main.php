<?php

	$table_config = array(
		"id"				=> 't_menu_grid',
		"type"				=> 'tree',
		"url"				=> $_XFA['main'],
		"nodeSet"			=> $menuSet,
		"resID"				=> $resourceId,
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> true,
		"action_nodes"		=> true,
		"form_action"		=> sprintf($_XFA['delete'], 0),
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('width' => '50%', 'title' => _('Меню'), 'html' => _('Меню')),
			array('width' => '15%', 'title' => _('URL меню'), 'html' => _('URL')),
			array('width' => '15%', 'title' => _('Переменная'), 'html' => _('Переменная'))
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "Меню"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	global $lng;
						return $lng->Gettextlng($node["name"]);
					'
				)
			),
			// Функция поля "URL"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["url"];'
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
			)
		),
		// Настройка элементов действий над нодами (обязательны при action_nodes = true)
		"action_nodes_cgf" 	=> array(
			// Новый раздел меню
			array(
				"acl_rule"	=> CREATE,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['form'], 1, '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/newusr.gif',
				"title"		=> _('Новый раздел меню')
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
				"has_id"	=> true,
				"img_src"	=> 'images/but/edgr.gif',
				"title"		=> _('Поменять родителя')
			),
			// Переместить узел выше
			array(
				"acl_rule"	=> CHANGE_POSITION,
				"res_innod"	=> true,
				"href"		=> sprintf($_XFA['ch_pos_top'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/top.gif',
				"title"		=> _('Переместить узел выше')
			),
			// Переместить узел ниже
			array(
				"acl_rule"	=> CHANGE_POSITION,
				"res_innod"	=> true,
				"href"		=> sprintf($_XFA['ch_pos_bottom'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/bottom.gif',
				"title"		=> _('Переместить узел ниже')
			),
			// Удалить
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> true,
				"href"		=> sprintf($_XFA['delete'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/del.gif',
				"title"		=> _('Удалить'),
				"confirm"	=> _('Вы уверены что хотите удалить данный раздел меню?')
			)
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['form'], 1, $parent_id),
				"html"		=> _('Добавить<br>меню')
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> _('Удалить выбранные<br>разделы меню'),
				"confirm"	=> _('Вы уверены что хотите удалить выбранные разделы меню?')
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
