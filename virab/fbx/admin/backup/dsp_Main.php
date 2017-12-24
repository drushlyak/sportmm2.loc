<?php

	$table_config = array(
		"id"				=> 't_backup_tree_grid',
		"type"				=> 'list',
		"hide_pager"		=> true,
		"url"				=> $_XFA['main'],
		"nodeSet"			=> $nodeSet,
		"resID"				=> $resourceId,
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> true,
		"action_nodes"		=> true,
		"form_action"		=> sprintf($_XFA['delete'], 0),
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('width' => '20%', 'title' => _('Пользователь'), 'html' => _('Пользователь')),
			array('width' => '15%', 'title' => _('Дата копии'), 'html' => _('Дата копии')),
			array('width' => '30%', 'title' => _('Файл копии'), 'html' => _('Файл копии')),
			array('width' => '10%', 'title' => _('Метка о загрузке файла дампа'), 'html' => _('Загруженный?')),
			array('width' => '10%', 'title' => _('Размер файла'), 'html' => _('Размер')),
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "Пользователь"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["login"];'
				)
			),
			// Функция поля "Дата"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["idate"];'
				)
			),
			// Функция поля "Файл"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["file"];'
				)
			),
			// Функция поля "Загруженный?"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["is_loaded"] ? "<img src=\"images/but/yes.gif\">" : "<img src=\"images/but/nor.gif\">";'
				)
			),
			// Функция поля "Размер"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return byteConvert(filesize(BACKUP_PATH . "/" . $node["file"]));'
				)
			)
		),
		// Настройка элементов действий над нодами (обязательны при action_nodes = true)
		"action_nodes_cgf" 	=> array(
			// Скачать дамп
			array(
				"acl_rule"	=> LOAD,
				"res_innod"	=> false,
				"make_href" => create_function(
		 			'$node',
		 			'	return str_replace(BASE_PATH, "", BACKUP_PATH) . "/" . $node["file"];'
		 		),
				"img_src"	=> 'images/but/database_download.gif',
				"title"		=> _('Скачать копию БД')
			),
			// Восстановить из копии
			array(
				"acl_rule"	=> LOAD,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['restore'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/database_load.gif',
				"title"		=> _('Восстановить из копии')
			),
			// Удалить дамп
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['delete'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/del.gif',
				"confirm"	=> _('Вы уверены, что хотите удалить данную копию БД?'),
				"title"		=> _('Удалить копию БД')
			)
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['store']),
				"html"		=> _('Создать резервную<br />копию БД')
			),
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['loadform']),
				"html"		=> _('Загрузить сохраненную<br />копию БД')
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> _('Удалить выбранные<br />копии БД'),
				"confirm"	=> _('Вы уверены что хотите удалить выбранные копии?')
			)
		)
	);

?><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


<?php if($attributes['str_error']): ?>
	<p class="cerr"><?=$attributes['str_error']?></p>
<?php endif;?>

<?php if($attributes['str_message']): ?>
	<p class="cmessage"><?=$attributes['str_message']?></p>
<?php endif;?>

<?php if (!$auth_in->isAllowed()): ?>
	<p class="cerr"><?=$ACL_ERROR?></p>
<?php return; endif; ?>

<?php
	$dsp_helper->writeTable($table_config);
?>
