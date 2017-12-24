<?php

	$table_config = array(
		"id"				=> $tableID,
		"type"				=> 'list',
		"pager"				=> $datapager,
		"hide_pager"		=> false,
		"url"				=> $_XFA['main'],
		"nodeSet"			=> $dataSet,
		"resID"				=> $resourceId,
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> true,
		"action_nodes"		=> true,
		"sorting"			=> true,
		"form_action"		=> sprintf($_XFA['delete'], 0),
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('name' => 'name', 'width' => '20%', 'title' => 'ФИО', 'html' => 'ФИО', 'sorting' => true),
			array('name' => 'product', 'width' => '20%', 'title' => 'Продукт', 'html' => 'Продукт', 'sorting' => true),
			array('name' => 'add_date', 'width' => '20%', 'title' => 'Дата доставки', 'html' => 'Дата доставки', 'sorting' => true),
			array('width' => '20%',  'title' => 'Фото', 'html' => 'Фото')
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "ФИО"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["name"];'
				)
			),
			// Функция поля "Продукт"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["product"] . " (" . $node["product_art"] . ")&nbsp; &nbsp;<img src=\"" . $node["product_photo"] . "\" border=\"0\" alt=\"\" />";'
				)
			),
			// Функция поля "Дата доставки"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["add_date"];'
				)
			),
			// Функция поля "Фотография"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return "<img src=\"" . $node["tmb_photo"] . "\" border=\"0\" alt=\"\" />";'
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
				"title"		=> 'Редактировать'
			),
			// DELETE
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['delete'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/del.gif',
				"title"		=> 'Удалить',
				"confirm"	=> 'Вы уверены, что хотите удалить данное фото доставки?'
			)
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['form'], 1, 0),
				"html"		=> 'Создать новое<br>фото доставки'
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> 'Удалить выбранные<br>фото доставок',
				"confirm"	=> 'Вы уверены что хотите удалить фото доставок?'
			)
		)
	);

?><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="js/list.js"></script>

<?php if($attributes['str_error']): ?>
	<p class="cerr"><?=$attributes['str_error']?></p>
<?php endif;?>

<?php if (!$auth_in->isAllowed()): ?>
	<p class="cerr"><?=$ACL_ERROR?></p>
<?php return; endif; ?>

<?php
	$filter_config = array(
		'tableID' => $tableID,
		'action' => $_XFA['filter_main'],
		'fields' => array(
			array(
				'typeField'	=> TFTEXTFIELD,
				'name'		=> 'name',
				'label'		=> _('ФИО')
			)
		)
	);

	$dsp_helper->writeTableFilter($filter_config, $attributes);
	$dsp_helper->writeTable($table_config);
?>