<?php
	$table_config = array(
		"id"				=> $tableID,
		"type"				=> 'list',
		"pager"				=> $datapager,
		"hide_pager"		=> false,
		"url"				=> $_XFA['recommended'],
		"nodeSet"			=> $dataSet,
		"resID"				=> $resourceId,
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> true,
		"action_nodes"		=> true,
		"sorting"			=> true,
		"form_action"		=> sprintf($_XFA['recommended_delete'], 0, $id_product),
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('name' => 'name', 'width' => '45%', 'title' => 'Наименование продукта', 'html' => 'Наименование продукта', 'sorting' => true),
			array('name' => 'article', 'width' => '20%', 'title' => 'Артикул', 'html' => 'Артикул', 'sorting' => true),
			array('width' => '15%', 'title' => 'Фото продукта', 'html' => 'Фото продукта')
		),
		// Фунции логики вывода данных
		"data_nodes_cfg"	=> array(
			// Функция поля "Наименование продукта"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["name"];'
				)
			),
			// Функция поля "Артикул"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["article"];'
				)
			),
			// Функция поля "Фото продукта"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return "<img src=\"" . $node["main_foto50"] . "\" alt=\"\" border=\"0\" />";'
				)
			)
		),
		// Настройка элементов действий над нодами (обязательны при action_nodes = true)
		"action_nodes_cgf" 	=> array(
			// DELETE
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['recommended_delete'], '!node_id!', $id_product),
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
				"href"		=> sprintf($_XFA['recommended_form'], 1, 0, $id_product),
				"html"		=> 'Добавить <br />рекомендуемый продукт'
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> 'Удалить выбранные<br />рекомендуемые продукты',
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
		$filter_config = array(
			'tableID' => $tableID,
			'action' => $_XFA['filter_main'],
			'fields' => array(
				array(
					'typeField'	=> TFTEXTFIELD,
					'name'		=> 'name',
					'label'		=> _('Наименование')
				),
				array(
					'typeField'	=> TFTEXTFIELD,
					'name'		=> 'article',
					'label'		=> _('Артикул')
				)
			)
		);

		$dsp_helper->writeTableFilter($filter_config, $attributes);
		$dsp_helper->writeTable($table_config);
	?>