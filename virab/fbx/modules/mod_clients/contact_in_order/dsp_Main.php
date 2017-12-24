<?php
	$table_config = array(
		"id"				=> $tableID,
		"type"				=> 'list',
		"pager"				=> $datapager,
		"hide_pager"		=> false,
		"url"				=> sprintf($_XFA['contact_in_order'], $id_client),
		"nodeSet"			=> $dataSet,
		"resID"				=> $resourceId,
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> true,
		"action_nodes"		=> true,
		"sorting"			=> true,
		"form_action"		=> sprintf($_XFA['contact_in_order_delete'], 0, $id_client),
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('name' => 'fio', 'width' => '80%', 'title' => 'ФИО', 'html' => 'ФИО', 'sorting' => true)
		),
		// Фунции логики вывода данных
		"data_nodes_cfg"	=> array(
			// Функция поля "ФИО"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["fio"];'
				)
			)
		),
		// Настройка элементов действий над нодами (обязательны при action_nodes = true)
		"action_nodes_cgf" 	=> array(
			// EDIT
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['contact_in_order_form'], 2, '!node_id!', $id_client),
				"has_id"	=> true,
				"img_src"	=> 'images/but/ed.gif',
				"title"		=> 'Редактировать'
			),
			// Адреса получателя
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['cio_address'], $id_client, '!node_id!'),
				"has_id"	=> true,
				"url_id"	=> 'id_contact',
				"img_src"	=> 'images/but/ico_my_contacts_adresa.gif',
				"title"		=> 'Адреса получателя'
			),
			// Телефоны получателя
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['cio_phones'], $id_client, '!node_id!'),
				"has_id"	=> true,
				"url_id"	=> 'id_contact',
				"img_src"	=> 'images/but/ico_phones.gif',
				"title"		=> 'Телефоны получателя'
			),
			// DELETE
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['contact_in_order_delete'], '!node_id!', $id_client),
				"has_id"	=> true,
				"img_src"	=> 'images/but/del.gif',
				"title"		=> 'Удалить',
				"confirm"	=> 'Вы уверены, что хотите удалить контакт?'
			)
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['contact_in_order_form'], 1, 0, $id_client),
				"html"		=> 'Добавить <br />контакт'
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> 'Удалить выбранные<br />контакты',
				"confirm"	=> 'Вы уверены что хотите удалить выделенные контакты?'
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
					'name'		=> 'fio',
					'label'		=> _('ФИО')
				)
			)
		);

		$dsp_helper->writeTableFilter($filter_config, $attributes);
		$dsp_helper->writeTable($table_config);
	?>