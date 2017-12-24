<?php
	$table_config = array(
		"id"				=> $tableID,
		"type"				=> 'list',
		"pager"				=> $datapager,
		"hide_pager"		=> false,
		"url"				=> sprintf($_XFA['cio_phones'], $id_client, $id_contact),
		"nodeSet"			=> $dataSet,
		"resID"				=> $resourceId,
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> true,
		"action_nodes"		=> true,
		"sorting"			=> true,
		"form_action"		=> sprintf($_XFA['cio_phones_delete'], 0, $id_client, $id_contact),
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('name' => 'phone', 'width' => '40%', 'title' => 'Телефон', 'html' => 'Телефон', 'sorting' => true),
			array('name' => 'mobile', 'width' => '40%', 'title' => 'Мобильный', 'html' => 'Мобильный', 'sorting' => true),
		),
		// Фунции логики вывода данных
		"data_nodes_cfg"	=> array(
			// Функция поля "Телефон"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["phone"];'
				)
			),
			// Функция поля "Мобильный"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["is_mobile"];'
				)
			)
		),
		// Настройка элементов действий над нодами (обязательны при action_nodes = true)
		"action_nodes_cgf" 	=> array(
			// EDIT
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['cio_phones_form'], 2, '!node_id!', $id_client, $id_contact),
				"has_id"	=> true,
				"img_src"	=> 'images/but/ed.gif',
				"title"		=> 'Редактировать'
			),
			// DELETE
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['cio_phones_delete'], '!node_id!', $id_client, $id_contact),
				"has_id"	=> true,
				"img_src"	=> 'images/but/del.gif',
				"title"		=> 'Удалить',
				"confirm"	=> 'Вы уверены, что хотите удалить телефон?'
			)
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['cio_phones_form'], 1, 0, $id_client, $id_contact),
				"html"		=> 'Добавить <br />контакт'
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> 'Удалить выбранные<br />телефоны',
				"confirm"	=> 'Вы уверены что хотите удалить выделенные телефоны?'
			)
		)
	);
	//				'	return (($node["is_moblie"] ? "<img src=\"images/but/yes.gif\" border=0 alt=\"Мобильный номер\" title=\"Мобильный номер\">" : "<img src=\"images/but/not.gif\" border=0 alt=\"Обычный номер\" title=\"Обычный номер\">");'

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
					'name'		=> 'phone',
					'label'		=> _('Телефон')
				)
			)
		);

		$dsp_helper->writeTableFilter($filter_config, $attributes);
		$dsp_helper->writeTable($table_config);
	?>