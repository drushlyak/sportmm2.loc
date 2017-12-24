<?php

	$table_config = array(
		"id"				=> $tableID,
		"type"				=> 'list',
		"pager"				=> $datapager,
		"hide_pager"		=> false,
		"url"				=> sprintf($_XFA['bonus'], $id_client),
		"nodeSet"			=> $dataSet,
		"resID"				=> $resourceId,
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> true,
		"action_nodes"		=> true,
		"sorting"			=> true,
		"form_action"		=> sprintf($_XFA['bonus_delete'], 0, $id_client),
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('name' => 'date', 'width' => '20%', 'title' => 'Дата', 'html' => 'Дата', 'sorting' => true),
			array('name' => 'bonus', 'width' => '30%', 'title' => 'Количество бонус в рублях', 'html' => 'Бонус, руб.', 'sorting' => true),
			array('name' => 'type_bonus_action', 'width' => '30%', 'title' => 'Тип', 'html' => 'Тип', 'sorting' => true)
		),
		// Фунции логики вывода данных
		"data_nodes_cfg"	=> array(
			// Функция поля "Дата"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["idate"];'
				)
			),
			// Функция поля "Бонус"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["bonus"];'
				)
			),

			// Функция поля "Тип"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	global $__TYPE_BONUS_ACTION;
						foreach ($__TYPE_BONUS_ACTION as $bc_elem) {
							if ($bc_elem["id"] == $node["type_bonus_action"]) {
								return $bc_elem["name"];
								}
						}
					return false;'
				)
			)
		),
		// Настройка элементов действий над нодами (обязательны при action_nodes = true)
		"action_nodes_cgf" 	=> array(
			// DELETE
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['bonus_delete'], '!node_id!', $id_client),
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
				"href"		=> sprintf($_XFA['bonus_form'], 1, 0, $id_client),
				"html"		=> 'Добавить <br />запись'
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> 'Удалить выбранные<br />записи',
				"confirm"	=> 'Вы уверены что хотите удалить выделенные записи?'
			)
		)
	);
	//				'	return (($node["is_moblie"] ? "<img src=\"images/but/yes.gif\" border=0 alt=\"Мобильный номер\" title=\"Мобильный номер\">" : "<img src=\"images/but/not.gif\" border=0 alt=\"Обычный номер\" title=\"Обычный номер\">");'

?><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script type="text/javascript" src="js/list.js"></script>

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
					'typeField'	=> TFTEXTFIELD,
					'name'		=> 'number',
					'label'		=> _('Номер телефона')
				)
			)
		);

		$dsp_helper->writeTableFilter($filter_config, $attributes);*/
		$dsp_helper->writeTable($table_config);
	?>