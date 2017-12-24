<?php

	//категории для фильтрации
	$main_cat_arr = $db->get_all("SELECT id, name FROM " . CFG_DBTBL_DICT_MAIN_CATEGORY);
	if (is_array($main_cat_arr)) {
		foreach ($main_cat_arr as $mca) {
			$cat_array[(int) $mca['id'] + 1000] = $mca['name'];
			$category_array = $db->get_hashtable("SELECT id, name FROM " . CFG_DBTBL_DICT_CATEGORY . " WHERE id_main_category = ?", $mca['id']);
			if (is_array($category_array)) {
				foreach ($category_array as $key => $val) {
					$cat_array = $cat_array + array((int) $key => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $val);
				}
			}
		}
	}
	$category_array = $cat_array;

	$tab_conf = array(
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['form'], 1, 0),
				"html"		=> 'Создать новый<br>продукт'
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> 'Удалить выбранные<br>продукты',
				"confirm"	=> 'Вы уверены что хотите удалить выделенные продукты?'
			),
			array(
				"acl_rule"	=> EDIT,
				"href"		=> $_XFA['copy_city'],
				"html"		=> 'Скопировать продукты<br>из одного города в другой'
			)
		)
	);

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
			array('width' => '10%', 'title' => 'Изображение', 'html' => 'Изображение'),
			array('name' => 'article', 'width' => '20%', 'title' => 'Артикул', 'html' => 'Артикул', 'sorting' => true),
			array('name' => 'name', 'width' => '30%', 'title' => 'Название продукта', 'html' => 'Название продукта', 'sorting' => true),
			array('name' => 'cost_excess', 'width' => '10%', 'title' => 'Цена, руб./ед.', 'html' => 'Цена, руб./ед.', 'sorting' => true),
			array('width' => '5%',  'title' => 'На главной', 'html' => 'На главной'),
			array('width' => '5%',  'title' => 'Отображение', 'html' => 'Отображение')
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "Изображение"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return "<img src=\"" . $node["main_foto50"] . "\" border=\"0\" alt=\"\">";'
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
			// Функция поля "Название номенклатурной единицы"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["name"];'
				)
			),
			
			// Функция поля "Цена сверх, руб./ед."
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["cost_excess"];'
				)
			),
			// Функция поля "На главной"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return ($node["is_view_main"]) ? "Да" : "Нет";'
				)
			),
			// Функция поля "Отображение"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return ($node["is_active"]) ? "Да" : "Нет";'
				)
			)
		),
		// Настройка элементов действий над нодами (обязательны при action_nodes = true)
		"action_nodes_cgf" 	=> array(
			// активировать продукт
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
				"title"		=> _('Активировать или отключить продукт')
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
			/*// PHOTOS
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['photo'], '!node_id!'),
				"has_id"	=> true,
				"url_id"	=> 'id_product',
				"img_src"	=> 'images/but/photo.gif',
				"title"		=> 'Редактировать дополнительные фотографии товара'
			),*/
			// RECOMENDED
			/*array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['recommended'], '!node_id!'),
				"has_id"	=> true,
				"url_id"	=> 'id_product',
				"img_src"	=> 'images/but/points.gif',
				"title"		=> 'Редактировать рекомендуемые товары'
			),*/
			// DELETE
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['delete'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/del.gif',
				"title"		=> 'Удалить',
				"confirm"	=> 'Вы уверены, что хотите удалить данный продукт?'
			)
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['form'], 1, 0),
				"html"		=> 'Создать новый<br>продукт'
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> 'Удалить выбранные<br>продукты',
				"confirm"	=> 'Вы уверены что хотите удалить выделенные продукты?'
			),
			array(
				"acl_rule"	=> EDIT,
				"href"		=> $_XFA['copy_city'],
				"html"		=> 'Скопировать продукты<br>из одного города в другой'
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
			),
			array(
				'typeField'	=> TFSELECT,
				'name' => 'categories',
				'label' => _('Категории'),
				'without_output_modification' => true,
				'options' => $category_array
			),
			array(
				'typeField'	=> TFSELECTDATASET,
				'name' => 'id_producer',
				'label' => _('Производитель'),
				'without_output_modification' => true,
				'dataSet' => $db->get_all("SELECT id, name FROM " . CFG_DBTBL_MOD_BRANDS),
			),
			array(
				'typeField'	=> TFCHECKBOX,
				'name' => 'no_photo',
				'label' => _('Нет фотографий')
			),
			array(
				'typeField'	=> TFCHECKBOX,
				'name' => 'is_view_main',
				'label' => _('Отображение на главной')
			),
			array(
				'typeField'	=> TFCHECKBOX,
				'name' => 'is_active',
				'label' => _('Отображение на сайте')
			)
		)
	);
	//$dsp_helper->write_tab($tab_conf);
	$dsp_helper->writeTableFilter($filter_config, $attributes);
	$dsp_helper->writeTable($table_config);
?>