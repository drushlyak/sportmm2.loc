<?php
	$table_config = array(
		"id"				=> 't_dict_type_delivery_grid',
		"type"				=> 'list',
		"pager"				=> $datapager,
		"hide_pager"		=> false,
		"url"				=> $_XFA['type_delivery'],
		"nodeSet"			=> $dataSet,
		"resID"				=> $resourceId,
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> true,
		"action_nodes"		=> true,
		"sorting"			=> true,
		"form_action"		=> sprintf($_XFA['type_delivery_delete'], 0, $id_product),
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('width' => '20%', 'title' => 'Название', 'html' => 'Название'),
			array('width' => '20%', 'title' => 'Время доставки', 'html' => 'Время доставки'),
			array('width' => '15%', 'title' => 'Время от текущего', 'html' => 'Время от текущего'),
			array('width' => '15%', 'title' => 'Период доставки, количество часов в течении которых возможна доставка (-1 - Самовывоз)', 'html' => 'Период доставки'),
			array('width' => '10%', 'title' => 'Стоимость', 'html' => 'Стоимость')
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "Название"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["name"];'
				)
			),
			// Функция поля "Время доставки"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["start_time"] . " - " . $node["end_time"];'
				)
			),
			// Функция поля "Время от текущего"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["start_for_today"];'
				)
			),
			// Функция поля "Период доставки"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["interval_hours"];'
				)
			),
			// Функция поля "Стоимость"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["price"];'
				)
			)
		),
		// Настройка элементов действий над нодами (обязательны при action_nodes = true)
		"action_nodes_cgf" 	=> array(
			// EDIT
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['type_delivery_form'], 2, '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/ed.gif',
				"title"		=> 'Редактировать'
			),
			// EDIT
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['place_delivery'], '!node_id!'),
				"has_id"	=> true,
				"url_id"	=> 'id_type',
				"img_src"	=> 'images/but/ico_places_delivery.gif',
				"title"		=> 'Места доставки'
			),
			// DELETE
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['type_delivery_delete'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/del.gif',
				"title"		=> 'Удалить',
				"confirm"	=> 'Вы уверены, что хотите удалить данный тип доставки?'
			)
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['type_delivery_form'], 1, 0),
				"html"		=> 'Создать новый<br>тип доставки'
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> 'Удалить выбранные<br>типы доставки',
				"confirm"	=> 'Вы уверены что хотите удалить выделенные типы доставки?'
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
