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
			array('name' => 'id', 'width' => '3%', 'class' => 'header_table_left', 'title' => 'ID', 'html' => 'ID', 'sorting' => true),
			array('name' => 'number', 'width' => '5%', 'class' => 'header_table_left', 'title' => 'Номер заказа', 'html' => 'Номер'),
			array('width' => '11%', 'class' => 'header_table_left', 'title' => 'ФИО клиента', 'html' => 'ФИО клиента'),
			array('width' => '11%', 'class' => 'header_table_left', 'title' => 'Город', 'html' => 'Город'),
			array('name' => 'state', 'width' => '5%', 'class' => 'header_table_left', 'title' => 'Состояние', 'html' => 'Состояние', 'sorting' => true),
			array('name' => 'date_order', 'width' => '8%', 'class' => 'header_table_left', 'title' => 'Дата заказа', 'html' => 'Дата заказа', 'sorting' => true),
			array('name' => 'sum', 'width' => '10%', 'class' => 'header_table_left', 'title' => 'Сумма', 'html' => 'Сумма', 'sorting' => true),
			array('name' => 'complect', 'width' => '27%', 'class' => 'header_table_left', 'title' => 'Состав', 'html' => 'Состав')
		),
		"row_color" => 'color',
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "ID"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["id"];'
				)
			),
			// Функция поля "Номер"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["number"];'
				)
			),
			// Функция поля "ФИО клиента"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["fio"];'
				)
			),
			// Функция поля "Город"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["delivery_address"];'
				)
			),
			// Функция поля "Состояние"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["state_order"];'
				)
			),
			// Функция поля "Дата заказа"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["date_order"];'
				)
			),
	
			// Функция поля "Сумма"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["price"];'
				)
			),
			// Функция поля "Состав"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'		global $db;
							$products = $db->get_all("
								SELECT mop.*, mp.name, mp.article
									FROM " . CFG_DBTBL_MOD_ORDER_PRODUCT . " AS mop
									   , " . CFG_DBTBL_MOD_PRODUCT . " AS mp
									WHERE mop.id_order = ?
									  AND mop.id_product = mp.id
							", $node["id"]);

							$sum_order = 0;
							$count = 0;
							if (is_array($products)) {
								foreach($products as $product) {
									$count++;
									$product_complect .= $product["name"] . " (" . $product["article"] . ") - " . $product["count"] . " шт. - " . $product["price"] . " руб.<br/>";
								}
							}

						return $product_complect;'
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
				"confirm"	=> 'Вы уверены, что хотите удалить данный заказ?'
			)
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['form'], 1, 0),
				"html"		=> 'Создать заказ<br />вручную'
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> 'Удалить выбранные<br>заказы',
				"confirm"	=> 'Вы уверены что хотите удалить выделенные заказы?'
			)
		)
	);

?><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="js/list.js"></script>

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
				'typeField'	=> TFTEXTFIELD,
				'name'		=> 'id',
				'label'		=> 'ID'
			),
			array(
				'typeField'	=> TFTEXTFIELD,
				'name'		=> 'id_client',
				'label'		=> 'ID клиента'
			),
			array(
				'typeField' => TFDATEPICKERRANGE,
				'label' => 'Дата заказа',
				'name' => 'date_order_range',
				'calendarsCount' => 2,
				'value' => ""
			),
			array(
				'typeField' => TFSELECTDATASET,
				'name' => 'state_order',
				'label' => 'Состояние заказа',
				'multiple' => false,
				'empty' => true,
				'dataSet' => $db->get_all("SELECT * FROM " . CFG_DBTBL_DICT_STATE_ORDER)
			),
			array(
				'typeField'	=> TFTEXTFIELD,
				'name'		=> 'order_number',
				'label'		=> 'Номер заказа'
			),
			array(
				'typeField' => TFDATEPICKERRANGE,
				'label' => 'Дата доставки',
				'name' => 'date_delivery_range',
				'calendarsCount' => 2,
				'value' => ""
			),
			array(
				'typeField' => TFSELECTDATASET,
				'name' => 'id_city',
				'label' => 'Регион (город)',
				'multiple' => false,
				'empty' => true,
				'dataSet' => $db->get_all("SELECT * FROM " . CFG_DBTBL_DICT_CITY)
			)
		)
	);

	$dsp_helper->writeTableFilter($filter_config, $attributes);
	$dsp_helper->writeTable($table_config);

	if (!$attributes['filter']) {
		// пишем в куки ID последних 10 заказов
		setcookie('lastsOrders', join(",", $lastsOrders));
	}
?>
<style type="text/css">
	.hasNewOrders {
		background-color: #DDD;
		border: 1px solid #CCCCCC;
		color: red;
		font-size: 20px;
		height: 70px;
		padding-top: 20px;
		position: absolute;
		text-align: center;
		vertical-align: middle;
		width: 400px;
		z-index: 10000;
		-moz-border-radius: 5px;
		-webkit-border-radius: 5px;
		-moz-box-shadow: 0 0 1em #CCCCCC;
		-webkit-box-shadow: 0 0 1em #CCCCCC;
		top: 200px;
		left: 230px;
		cursor: pointer;

		box-shadow: 2px 2px 5px #CCCCCC;
		text-shadow: 1px 1px #F1F1F1;
	}

	.opacityTable {
		opacity: 0.3;
		filter: alpha(opacity = 30);
	}
</style>
<script type="text/javascript">
	function checkNewOrders() {
		$.ajax({
			type: "POST",
			url: "<?=SITE_URL?>/library/libcruiser4/ajax/get_last_orders.php",
			success: function(msg) {
				var lo = Cruiser.getCookie('lastsOrders');

				if (lo !== trim(msg)) {
					if ($("body div.hasNewOrders").length === 0) {
						window.scrollTo(0,0);

						$('table.t_virab_tree').addClass('opacityTable');
						$('<div class="hasNewOrders">В заказах есть изменения!<br />Нажмите для обновления таблицы.</div>')
							.appendTo($("body"))
							.click(function() {
								$(this).remove();
								cleanFilter();
							});
					}
				}
			}
		});
	}
	setInterval(checkNewOrders, 20000);
</script>