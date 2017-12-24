<?php

	$table_config = array(
		"id"				=> 't_admodules_grid',
		"type"				=> 'list',
		"url"				=> $_XFA['main'],
		"nodeSet"			=> $nodeSet,
		"resID"				=> $resourceId,
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> false,
		"action_nodes"		=> true,
		"form_action"		=> "",
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('width' => '30%', 'title' => _('Название модуля'), 'html' => _('Название модуля')),
			array('width' => '20%', 'title' => _('Переменная'), 'html' => _('Переменная')),
			array('width' => '20%', 'title' => _('Тип модуля'), 'html' => _('Тип')),
			array('width' => '10%', 'title' => _('Видимость'), 'html' => _('Видимость')),
			array('width' => '10%', 'title' => _('Системный'), 'html' => _('Системный')),
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "Название модуля"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	global $lng;
						return "&nbsp;" . $lng->Gettextlng($node["name"]);'
				)
			),
			// Функция поля "Переменная"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["var"];'
				)
			),
			// Функция поля "Тип"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return ($node["mod_type"] == 0) ? "' . _("Модуль") . '" : "' . _("Справочник") . '";'
				)
			),
			// Функция поля "Видимость"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["enabled"] ? "<img src=\"images/but/yes.gif\">" : "<img src=\"images/but/nor.gif\">";'
				)
			),
			// Функция поля "Системный"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["sys"] ? "<img src=\"images/but/yes.gif\">" : "<img src=\"images/but/nor.gif\">";'
				)
			)
		),
		// Настройка элементов действий над нодами (обязательны при action_nodes = true)
		"action_nodes_cgf" 	=> array(
			// Редактировать модуль
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['form'], 2, '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/ed.gif',
				"title"		=> _('Редактировать модуль')
			),
			// Привилегии доступа в модуле
			array(
				"acl_rule"	=> VIEW,
				"res_innod"	=> false,
				"href"		=> sprintf($_XFA['main_priv'], '!node_id!'),
				"has_id"	=> true,
				"url_id"	=> 'id_mod',
				"img_src"	=> 'images/but/access.gif',
				"title"		=> _('Привилегии доступа в модуле')
			)
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['form'], 1, 0),
				"html"		=> _('Загрузить<br />модуль')
			),
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['manualform'], 1),
				"html"		=> _('Ручная установка<br />модуля')
			),
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['manualform'], 2),
				"html"		=> _('Ручная установка<br />словаря')
			)
		)
	);

?><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


<?php if ($attributes['str_error']): ?>
	<p class="cerr"><?=$attributes['str_error']?></p>
<?php endif;?>

<?php if (!$auth_in->isAllowed()): ?>
	<p class="cerr"><?=$ACL_ERROR?></p>
<?php return; endif; ?>

<?php
	$dsp_helper->writeTable($table_config);
?>
