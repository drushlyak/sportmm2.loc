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
		"row_color"			=> 'color',
		"form_action"		=> sprintf($_XFA['delete'], 0),
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('name' => 'phone', 'width' => '20%', 'class' => 'header_table_left', 'title' => 'Телефон', 'html' => 'Телефон', 'sorting' => true),
			array('name' => 'email', 'width' => '20%', 'class' => 'header_table_left', 'title' => 'Email', 'html' => 'Email', 'sorting' => true),
			array('name' => 'f_name', 'width' => '30%', 'class' => 'header_table_left', 'title' => 'Фамилия', 'html' => 'Фамилия', 'sorting' => true),
			array('name' => 'i_name', 'width' => '10%', 'class' => 'header_table_left', 'title' => 'Имя', 'html' => 'Имя', 'sorting' => true)
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "Телефон"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["phone"];'
				)
			),
			// Функция поля "Email"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return "<a href=\"mailto:" . $node["email"] . "\">" . $node["email"] . "</a>";'
				)
			),
			// Функция поля "Фамилия"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["f_name"];'
				)
			),
			// Функция поля "Имя"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["i_name"];'
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
			
			// Заказы клиента
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['client_orders'], '!node_id!'),
				"has_id"	=> true,
				"url_id"	=> 'id_client',
				"img_src"	=> 'images/but/ico_orders.gif',
				"title"		=> 'Заказы клиента'
			),
			
			// DELETE
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['delete'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/del.gif',
				"title"		=> 'Удалить',
				"confirm"	=> 'Вы уверены, что хотите удалить данного пользователя?'
			)
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['form'], 1, 0),
				"html"		=> 'Создать нового<br>пользователя'
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> 'Удалить выбранных<br>пользователей',
				"confirm"	=> 'Вы уверены что хотите удалить выделенных пользователей?'
			)
		)
	);

?>
<style>
	.header_table_left {
		text-align:left !important;
	}
</style>

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
				'typeField'	=> TFSELECTDATASET,
				'name'		=> 'ph_person',
				'label'		=> _('Тип лица'),
				'multiple' => false,
				'empty' => false,
				'dataSet' => array(array('id' => 0, 'name' => 'Все'), array('id' => 1, 'name' => 'Юридическое лицо'), array('id' => 2, 'name' => 'Физическое лицо')),
				'params' => array('size' => '1'),
				'selected' => array((int) $attributes['ph_person'])
			),
			array(
				'typeField'	=> TFTEXTFIELD,
				'name'		=> 'phone',
				'label'		=> _('Телефон')
			),
			array(
				'typeField'	=> TFTEXTFIELD,
				'name'		=> 'email',
				'label'		=> _('Email')
			),
			array(
				'typeField'	=> TFTEXTFIELD,
				'name'		=> 'f_name',
				'label'		=> _('Фамилия')
			),
			array(
				'typeField'	=> TFTEXTFIELD,
				'name'		=> 'i_name',
				'label'		=> _('Имя')
			),
			array(
				'typeField'	=> TFTEXTFIELD,
				'name'		=> 'o_name',
				'label'		=> _('Отчество')
			)
		)
	);

	$dsp_helper->writeTableFilter($filter_config, $attributes);
	$dsp_helper->writeTable($table_config);
//	return $org_form . " " . $node["company_name"] . ((strlen($node["inn"]) + strlen($node["kpp"])) ? " (" . ((strlen($node["inn"])) ? "ИНН: " . $node["inn"] . ", " : "") . ((strlen($node["kpp"])) ? "КПП: " . $node["kpp"] : "") . ")" : "");

?>