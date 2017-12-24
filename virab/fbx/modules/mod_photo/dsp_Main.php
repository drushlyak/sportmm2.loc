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
            array('name' => 'name', 'width' => '55%', 'title' => 'Раздел', 'html' => 'Раздел', 'sorting' => true),
            array('width' => '25%', 'title' => 'Переменная', 'html' => 'Переменная')
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "Название"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'return $node["name"];'
				)
            ),
            // Функция поля "Переменная"
            array(
                "align" => 'center',
                "args"  => '$node',
                "func"  => create_function(
                    '$node',
                    '   return getTeValueName($node["id_te_value"]);'
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
				"confirm"	=> 'Вы уверены, что хотите удалить данную запись?'
			)
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['form'], 1, 0),
				"html"		=> 'Создать новую<br>запись'
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> 'Удалить выбранные<br>записи',
				"confirm"	=> 'Вы уверены что хотите удалить выделенные записи?'
			),
			/*array(
				"acl_rule"	=> EDIT,
				"href"		=> $_XFA['copy_city'],
				"html"		=> 'Скопировать продукты<br>из одного города в другой'
			)*/
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
			)
		)
	);
	//$dsp_helper->write_tab($tab_conf);
	$dsp_helper->writeTableFilter($filter_config, $attributes);
	$dsp_helper->writeTable($table_config);
?>