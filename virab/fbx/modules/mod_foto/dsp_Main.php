<?php 

	$table_config = array(
		"id"				=> 't_mod_foto_grid',
		"type"				=> 'tree',
		"url"				=> $_XFA['cat_main'],
		"nodeSet"			=> $nodeSet,
		"resID"				=> $resourceId, 
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> false,
		"action_nodes"		=> true,
		"form_action"		=> sprintf($_XFA['cat_delete'], 0),
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('width' => '55%', 'title' => 'Раздел', 'html' => 'Раздел'),
			array('width' => '25%', 'title' => 'Переменная', 'html' => 'Переменная')
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "Раздел"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	global $lng;
						return $lng->Gettextlng($node["name"]);
					'
				)
			),
			// Функция поля "Переменная"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return getTeValueName($node["id_te_value"]);'
				)			
			)
		),
		// Настройка элементов действий над нодами (обязательны при action_nodes = true)
		"action_nodes_cgf" 	=> array(
			// Добавить дочерний раздел
			array(
				"acl_rule"	=> CREATE,
				"res_innod"	=> true,
				"href"		=> sprintf($_XFA['cat_form'], 1, '!node_id!'),
				"has_id"	=> true, 
				"img_src"	=> 'images/but/newusr.gif',
				"title"		=> 'Добавить дочерний раздел'
			),
			// Редактировать
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> true,
				"href"		=> sprintf($_XFA['cat_form'], 2, '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/ed.gif',
				"title"		=> 'Редактировать'
			),
			// Список фотографий раздела
			array(
				"acl_rule"	=> VIEW,
				"res_innod"	=> true,				
				"href"		=> sprintf($_XFA['main'], '!node_id!'),
				"url_id"	=> 'id_fotogr',
				"has_id"	=> true,
				"img_src"	=> 'images/but/edusr.gif',
				"title"		=> 'Список фотографий раздела'
			),
			// Поменять родителя
			array(
				"acl_rule"	=> CHANGE_PARENT,
				"res_innod"	=> true,
				"href"		=> sprintf($_XFA['cat_ch_parent'], '!node_id!'),
				"check_func"=> create_function(
					'$node',
					'return $node["can_move"];'
				),
				"has_id"	=> true,
				"img_src"	=> 'images/but/edgr.gif',
				"title"		=> 'Поменять родителя'
			),
			// Переместить узел выше
			array(
				"acl_rule"	=> CHANGE_POSITION,
				"res_innod"	=> true,				
				"href"		=> sprintf($_XFA['cat_ch_pos_top'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/top.gif',
				"title"		=> 'Переместить раздел выше'
			),
			// Переместить узел ниже
			array(
				"acl_rule"	=> CHANGE_POSITION,
				"res_innod"	=> true,				
				"href"		=> sprintf($_XFA['cat_ch_pos_bottom'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/bottom.gif',
				"title"		=> 'Переместить раздел ниже'
			),
			// Удалить
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> true,		
				"href"		=> sprintf($_XFA['cat_delete'], '!node_id!'),
				"check_func"=> create_function(
					'$node',
					'return $node["can_delete"];'
				),				
				"has_id"	=> true,
				"img_src"	=> 'images/but/del.gif',
				"title"		=> 'Удалить',
				"confirm"	=> 'Вы уверены что хотите удалить данный раздел?'
			)			
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['cat_form'], 1, 0),
				"html"		=> 'Добавить<br />раздел'
			)
		)
	);
	
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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