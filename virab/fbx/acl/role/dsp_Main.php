<?php

	$table_config = array(
		"id"				=> 't_mod_role_grid',
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
			array('width' => '60%', 'title' => _('Наименование роли'), 'html' => _('Наименование роли')),
			array('width' => '15%', 'title' => _('Количество правил для роли'), 'html' => _('Количество правил')),
			array('width' => '15%', 'title' => _('Количество пользователей с данной ролью'), 'html' => _('Количество пользователей'))
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "Наименование"
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
			// Функция поля "Количество правил"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'
						return (int) $node["rule_count"];
					'
				)
			),
			// Функция поля "Количество пользователей"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'
						return (int) $node["user_count"];
					'
				)
			)
		),
		// Настройка элементов действий над нодами (обязательны при action_nodes = true)
		"action_nodes_cgf" 	=> array(
			// Добавить роль-наследника
			array(
				"acl_rule"	=> CREATE,
				"res_innod"	=> false,
				"make_href" => create_function(
					'$node',
					'	global $_XFA;
						return sprintf($_XFA["form"], 1, $node["parent_id"], $node["id"]);'
				),
				"img_src"	=> 'images/but/newusr.gif',
				"title"		=> _('Добавить роль-наследника')
			),
			// Редактировать
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['form'], 2, 0, '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/ed.gif',
				"title"		=> _('Редактировать')
			),
			// Поменять родителя
			array(
				"acl_rule"	=> CHANGE_PARENT,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['ch_parent'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/edgr.gif',
				"title"		=> _('Поменять родительскую роль')
			),
			// Удалить
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['delete'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/del.gif',
				"title"		=> _('Удалить'),
				"confirm"	=> _('Вы уверены что хотите удалить данную роль?')
			)

		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['form'], 1, 0, 0),
				"html"		=> _('Добавить роль<br />верхнего уровня')
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