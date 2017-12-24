<?php

	$table_config = array(
		"id"				=> 't_privilege_grid',
		"type"				=> 'list',
		"hide_pager"		=> true,
		"url"				=> $_XFA['main'],
		"nodeSet"			=> $nodeSet,
		"resID"				=> $resourceId,
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> false,
		"action_nodes"		=> true,
		"form_action"		=> "",
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('width' => '60%', 'title' => _('Наименование привилегии'), 'html' => _('Привилегия')),
			array('width' => '20%', 'title' => _('Константа'), 'html' => _('Константа'))
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "Привилегия"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	global $lng;
						return "&nbsp;" . $lng->Gettextlng($node["name"]);'
				)
			),
			// Функция поля "Константа"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return strtoupper($node["var"]);'
				)
			)
		),
		// Настройка элементов действий над нодами (обязательны при action_nodes = true)
		"action_nodes_cgf" 	=> array(
			// Редактировать
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['form'], 2, '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/ed.gif',
				"title"		=> _('Редактировать')
			),
			// Удалить
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['delete'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/del.gif',
				"confirm"	=> _('Вы уверены, что хотите удалить данную привилегию?'),
				"title"		=> _('Удалить')
			)
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['form'], 1, 0),
				"html"		=> _('Создать<br />привилегию')
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
