 <?php 
	
	$table_config = array(
		"id"				=> 't_mod_feedback_tree_grid',
		"type"				=> 'tree',
		"url"				=> $_XFA['cat_main'],
		"nodeSet"			=> $dataSet,
		"resID"				=> $resourceId, 
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> true,
		"action_nodes"		=> true,
		"form_action"		=> sprintf($_XFA['cat_delete'], 0),
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('width' => '50%', 'title' => 'Наименование раздела', 'html' => 'Наименование раздела'),
			array('width' => '30%', 'title' => 'Шаблонная переменная', 'html' => 'Шаблонная переменная')
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "Наименование раздела"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	global $lng;
						return $lng->Gettextlng($node["name"]);'
				)
			),
			// Функция поля "Шаблонная переменная"
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
			// EDIT
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['cat_form'], 2, '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/ed.gif',
				"title"		=> 'Редактировать раздел'
			),
			// Фотографии модели
			array(
				"acl_rule"	=> VIEW,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['main'], '!node_id!'),
				"has_id"	=> true,
				"url_id"	=> 'id_feedback',
				"img_src"	=> 'images/but/edusr.gif',
				"title"		=> 'Редактировать отзывы в данном разделе'
			),
			// DELETE
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['cat_delete'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/del.gif',
				"title"		=> 'Удалить раздел',
				"confirm"	=> 'Вы уверены что хотите удалить данный раздел отзывов?'
			)			
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['cat_form'], 1, 0),
				"html"		=> 'Создать новый<br>раздел'			
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> 'Удалить выбранные<br>разделы',
				"confirm"	=> 'Вы уверены что хотите удалить выделенные разделы?'			
			)
		)
	);
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="js/list.js"></script>
<style type="text/css">
	.top_div {
		text-align: right;
		padding: 5px 10px 5px 0;
	}
</style>
	
	<?php if($attributes['str_error']): ?>
		<p class="cerr"><?=$attributes['str_error']?></p>
	<?php endif;?>
	
	<?php if (!$auth_in->isAllowed()): ?>
		<p class="cerr"><?=$ACL_ERROR?></p>
	<?php return; endif; ?>

<?php 
	$dsp_helper = new DspHelper();

	// Таблица
	$dsp_helper->write_table($table_config); 
?>