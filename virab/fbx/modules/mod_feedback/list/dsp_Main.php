<?php
	$table_config = array(
		"id"				=> 't_mod_feedback_list_grid',
		"type"				=> 'list',
		"url"				=> sprintf($_XFA['main'], $id_feedback),
		"nodeSet"			=> $dataSet,
		"resID"				=> $resourceId,
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> true,
		"action_nodes"		=> true,
		"form_action"		=> sprintf($_XFA['delete'], $id_feedback, 0),
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('width' => '40%', 'title' => 'Отзыв', 'html' => 'Отзыв'),
			array('width' => '10%', 'title' => 'Автор', 'html' => 'Автор'),
			array('width' => '10%', 'title' => 'Дата', 'html' => 'Дата'),
			array('width' => '25%', 'title' => 'Продукт', 'html' => 'Продукт'),
			array('width' => '5%', 'title' => 'Отображение на сайте', 'html' => 'Отобр.')
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "Отзыв"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	global $lng;
						return $lng->Gettextlng($node["text"]);'
				)
			),
			// Функция поля "Автор"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	global $lng;
						return $lng->Gettextlng($node["author_name"])." <small>(".$node["author_mail"].")</small>";'
				)
			),
			// Функция поля "Дата"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return convertDateToView($node["idate"]);'
				)
			),
			// Функция поля "Продукт"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return ((strlen($node["product_name"])) ? "<a href=\"http://obradoval.ru/virab/index.php?fuseaction=mod_product.form&type=2&id=" . $node["id_product"] . "\" style=\"text-decoration:underline;\">" . $node["product_name"] . " (" . $node["article"] . ")</a>" : "&nbsp;");'
				)
			),
			// Функция поля "Отображение"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return "<img src=\"images/but/" . ($node["priz_active"] ? "yes" : "not") . ".gif\" border=0>";'
				)
			)
		),
		// Настройка элементов действий над нодами (обязательны при action_nodes = true)
		"action_nodes_cgf" 	=> array(
			// EDIT
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['form'], 2, $id_feedback, '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/ed.gif',
				"title"		=> 'Редактировать отзыв'
			),
			// DELETE
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['delete'], $id_feedback, '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/del.gif',
				"title"		=> 'Удалить отзыв',
				"confirm"	=> 'Вы уверены, что хотите удалить данный отзыв?'
			)
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['form'], 1, $id_feedback, 0),
				"html"		=> 'Создать новый<br>отзыв'
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> 'Удалить выбранные<br>отзывы',
				"confirm"	=> 'Вы уверены что хотите удалить выделенные отзывы?'
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

	// Таблица
	$dsp_helper->write_table($table_config);
?>