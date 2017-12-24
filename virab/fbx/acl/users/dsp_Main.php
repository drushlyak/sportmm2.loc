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
			array('name' => 'login', 'width' => '20%', 'title' => _('Логин'), 'html' => _('Логин'), 'sorting' => true),
			array('name' => 'fio', 'width' => '30%', 'title' => _('ФИО пользователя'), 'html' => _('ФИО')),
			array('name' => 'role', 'width' => '20%', 'title' => _('Роль пользователя'), 'html' => _('Роль'), 'sorting' => true),
			array('name' => 'email', 'width' => '15%', 'title' => _('Email'), 'html' => _('Email'))
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "Логин"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["login"];'
				)
			),
			// Функция поля "ФИО"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	global $lng; ' .
					'	return $lng->Gettextlng($node["full_name"]);'
				)
			),
			// Функция поля "Роль"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	global $lng; ' .
					'	return $lng->Gettextlng($node["role_name"]);'
				)
			),
			// Функция поля "Email"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["email"];'
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
				"html"		=> _('Добавить<br>пользователя')
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> _('Удалить выбранных<br>пользователей'),
				"confirm"	=> _('Вы уверены что хотите удалить выбранных пользователей?')
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
