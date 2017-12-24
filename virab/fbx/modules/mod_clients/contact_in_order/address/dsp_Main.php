<?php
	$table_config = array(
		"id"				=> $tableID,
		"type"				=> 'list',
		"pager"				=> $datapager,
		"hide_pager"		=> false,
		"url"				=> sprintf($_XFA['cio_address'], $id_client, $id_contact),
		"nodeSet"			=> $dataSet,
		"resID"				=> $resourceId,
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> true,
		"action_nodes"		=> true,
		"sorting"			=> true,
		"form_action"		=> sprintf($_XFA['cio_address_delete'], 0, $id_client, $id_contact),
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('name' => 'type', 'width' => '20%', 'title' => 'Тип адреса', 'html' => 'Тип адреса', 'sorting' => true),
			array('name' => 'address', 'width' => '60%', 'title' => 'Адрес', 'html' => 'Адрес', 'sorting' => true),
		),
		// Фунции логики вывода данных
		"data_nodes_cfg"	=> array(
			// Функция поля "Тип адреса"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	switch ($node["type_of_address"]) {
							case 1:
								$type_of_address_str = "Рабочий";
								break;
							case 2:
								$type_of_address_str = "Домашний";
								break;
							case 3:
								$type_of_address_str = "Частный дом";
								break;
							case 4:
								$type_of_address_str = "Гостиница";
								break;
							case 4:
								$type_of_address_str = "Больница";
								break;
						}
						return $type_of_address_str;'
				)
			),
			// Функция поля "Адрес"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	$addr = formatAddressString($node);

						return $addr;'

				)
			)
		),
		// Настройка элементов действий над нодами (обязательны при action_nodes = true)
		"action_nodes_cgf" 	=> array(
			// EDIT
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['cio_address_form'], 2, '!node_id!', $id_client, $id_contact),
				"has_id"	=> true,
				"img_src"	=> 'images/but/ed.gif',
				"title"		=> 'Редактировать'
			),
			// DELETE
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['cio_address_delete'], '!node_id!', $id_client, $id_contact),
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
				"href"		=> sprintf($_XFA['cio_address_form'], 1, 0, $id_client, $id_contact),
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
					'name'		=> 'city',
					'label'		=> _('Город')
				),
				array(
					'typeField'	=> TFTEXTFIELD,
					'name'		=> 'street',
					'label'		=> _('Улица')
				),
				array(
					'typeField'	=> TFTEXTFIELD,
					'name'		=> 'house',
					'label'		=> _('Дом')
				)
			)
		);

		$dsp_helper->writeTableFilter($filter_config, $attributes);
		$dsp_helper->writeTable($table_config);
	?>