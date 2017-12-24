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
		"row_color"			=> 'color',
		"sorting"			=> true,
		"form_action"		=> sprintf($_XFA['delete'], 0),
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('name' => 'is_active', 'width' => '25%', 'title' => 'Показывать', 'html' => 'Показывать', 'sorting' => true),
			array('name' => 'name', 'width' => '20%', 'title' => 'Title', 'html' => 'Title', 'sorting' => true),
			array('width' => '35%', 'title' => 'Фото', 'html' => 'Фото')
		),
		// Фунции логики вывода данных
		"data_nodes_cfg"	=> array(
			// Функция поля "Наименование продукта"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return ($node["is_active"]) ? "Да" : "Нет";'
				)
			),
			// Функция поля "Артикул"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["name"];'
				)
			),
			// Функция поля "Фото продукта"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return "<img src=\"" . $node["main_foto"] . "\" alt=\"\" border=\"0\" />";'
				)
			)
		),
		// Настройка элементов действий над нодами (обязательны при action_nodes = true)
		
		"action_nodes_cgf" 	=> array(
			// активировать анонс
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> false,
				"make_href" => create_function(
					'$node',
					'	global $_XFA, $id_record;

		 				return ($node["is_active"]) ? 
		 					sprintf($_XFA["record_active"], $node["id"], 0)
		 					:
		 					sprintf($_XFA["record_active"], $node["id"], 1);
		 			'
				),
				"img_src"	=> 'images/but/asterisk.gif',
				"title"		=> _('Активировать или удалить публикацию')
			),
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
				"confirm"	=> 'Вы уверены, что хотите удалить запись?'
			)
		),
		
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['form'], 1, 0),
				"html"		=> 'Добавить <br />логотип'
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> 'Удалить выбранные<br />логотипы',
				"confirm"	=> 'Вы уверены что хотите удалить выделенные записи?'
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
		
		$dsp_helper->writeTable($table_config);
	?>