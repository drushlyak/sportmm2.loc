<?php

	$table_config = array(
		"id"				=> 't_dict_color_grid',
		"type"				=> 'list',
		"url"				=> $_XFA['main'],
		"nodeSet"			=> $dataSet,
		"resID"				=> $resourceId,
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> true,
		"action_nodes"		=> true,
		"form_action"		=> sprintf($_XFA['delete'], 0),
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('width' => '30%', 'title' => 'Название раздела', 'html' => 'Название раздела'),
			array('width' => '30%', 'title' => 'URL', 'html' => 'URL')
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "Название раздела"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["text"];'
				)
			),
			// Функция поля "URL"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["url"];'
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
			// Переместить узел выше
			array(
				"acl_rule"	=> CHANGE_POSITION,
				"res_innod"	=> true,				
				"href"		=> sprintf($_XFA['ch_pos_top'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/top.gif',
				"title"		=> 'Переместить раздел выше'
			),
			// Переместить узел ниже
			array(
				"acl_rule"	=> CHANGE_POSITION,
				"res_innod"	=> true,				
				"href"		=> sprintf($_XFA['ch_pos_bottom'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/bottom.gif',
				"title"		=> 'Переместить раздел ниже'
			),
			// DELETE
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['delete'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/del.gif',
				"title"		=> 'Удалить',
				"confirm"	=> 'Вы уверены, что хотите удалить данный раздел?'
			)
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['form'], 1, 0),
				"html"		=> 'Создать новый<br>раздел'
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> 'Удалить выбранные<br>цвета',
				"confirm"	=> 'Вы уверены что хотите удалить выделенные разделы?'
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
	$dsp_helper = new DspHelper();
	$dsp_helper->write_table($table_config);
?>
