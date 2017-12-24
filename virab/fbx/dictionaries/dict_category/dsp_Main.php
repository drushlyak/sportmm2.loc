<?php 
	
	$table_config = array(
		"id"				=> 't_dict_category_grid',
		"type"				=> 'list',
		"pager"				=> $datapager,
		"hide_pager"		=> false,
		"url"				=> $_XFA['main'],
		"nodeSet"			=> $dataSet,
		"resID"				=> $resourceId, 
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> true,
		"action_nodes"		=> true,
		"form_action"		=> sprintf($_XFA['delete'], 0),
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('width' => '20%', 'title' => 'Основная категория', 'html' => 'Основная категория'),
			array('width' => '40%', 'title' => 'Название категории', 'html' => 'Название категории'),
			array('width' => '10%', 'title' => 'Кол-во товаров', 'html' => 'Кол-во товаров'),
			array('width' => '5%', 'title' => 'Отображать в главном меню', 'html' => 'Отображать'),
            array('width' => '5%', 'title' => 'Отображать главную категорию в главном меню', 'html' => 'Отображать ГК'),
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "Основная категория"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["main_category"];'
				)
			),
			// Функция поля "Название категории"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["name"];'
				)
			),
			// Функция поля "Кол-во товаров"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					' global $db;
					$count = $db->get_one("SELECT COUNT(*) FROM " . CFG_DBTBL_MOD_CATEGORY_PRODUCT . " WHERE id_category = ?",$node["id"]);	
					return $count;'
				)
			),
			// Функция поля "Отображать"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["is_menu"] ? "+" : "-";'
				)
            ),
            // Функция поля "Отображать ГК"
            array(
                "align" => 'center',
                "args"  => '$node',
                "func"  => create_function(
                    '$node',
                    '   return $node["is_menu_mc"] ? "+" : "-";'
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

			// Переместить выше
			array(
				"acl_rule"	=> CHANGE_POSITION,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['moveup'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/top.gif',
				"title"		=> _('Переместить выше')
			),
			// Переместить ниже
			array(
				"acl_rule"	=> CHANGE_POSITION,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['movedown'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/bottom.gif',
				"title"		=> _('Переместить ниже')
			),

			// DELETE
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['delete'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/del.gif',
				"title"		=> 'Удалить',
				"confirm"	=> 'Вы уверены, что хотите удалить данную категорию?'
			)			
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['form'], 1, 0),
				"html"		=> 'Создать новую<br>категорию'			
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> 'Удалить выбранные<br>категории',
				"confirm"	=> 'Вы уверены что хотите удалить выделенные категории?'			
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
