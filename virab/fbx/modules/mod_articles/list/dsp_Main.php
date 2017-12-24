<?php

	$table_config = array(
		"id"				=> $tableID,
		"type"				=> 'list',
		"pager"				=> $datapager,
		"hide_pager"		=> false,
		"url"				=> $_XFA['articles'],
		"nodeSet"			=> $dataSet,
		"resID"				=> $resourceId,
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> true,
		"action_nodes"		=> true,
		"sorting"			=> true,
		"form_action"		=> sprintf($_XFA['articles_delete'], 0, $id_category),
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('name' => 'i_date', 'width' => '40%', 'class' => 'header_table_center', 'title' => 'Дата', 'html' => 'Дата', 'sorting' => true),
			array('name' => 'name', 'width' => '40%', 'class' => 'header_table_center', 'title' => 'Название статьи', 'html' => 'Название статьи', 'sorting' => true)
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "Дата"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["i_date"];'
				)
			),
			// Функция поля "Наименование"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["name"];'
				)
			)
		),
		// Настройка элементов действий над нодами (обязательны при action_nodes = true)
		"action_nodes_cgf" 	=> array(
			// EDIT
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['articles_form'], 2, $id_category, '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/ed.gif',
				"title"		=> 'Редактировать'
			),
			
			
			// DELETE
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['articles_delete'], '!node_id!', $id_category),
				"has_id"	=> true,
				"img_src"	=> 'images/but/del.gif',
				"title"		=> 'Удалить',
				"confirm"	=> 'Вы уверены, что хотите удалить данную статью?'
			)
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['articles_form'], 1, $id_category, 0),
				"html"		=> 'Создать новую<br>статью'
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> 'Удалить выбранные<br>статьи',
				"confirm"	=> 'Вы уверены что хотите удалить выделенные статьи?'
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
/*$filter_config = array(
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
*/
	//$dsp_helper->writeTableFilter($filter_config, $attributes);
	$dsp_helper->writeTable($table_config);
//	return $org_form . " " . $node["company_name"] . ((strlen($node["inn"]) + strlen($node["kpp"])) ? " (" . ((strlen($node["inn"])) ? "ИНН: " . $node["inn"] . ", " : "") . ((strlen($node["kpp"])) ? "КПП: " . $node["kpp"] : "") . ")" : "");

?>