<?php
	$table_config = array(
		"id"				=> 't_admodules_priv_grid',
		"type"				=> 'list',
		"hide_pager"		=> true,
		"url"				=> $_XFA['main'],
		"nodeSet"			=> $nodeSet,
		"resID"				=> $resourceId,
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> false,
		"action_nodes"		=> true,
		"form_action"		=> "",
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('width' => '70%', 'title' => _('Привилегия доступа'), 'html' => _('Привилегия')),
			array('width' => '10%', 'title' => _('Константа класса'), 'html' => _('Константа'))
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "Привилегия"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	global $lng;
						return "&nbsp;" . $lng->Gettextlng($node["name"]);'
				)
			),
			// Функция поля "Константа"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return strtoupper($node["var"]);'
				)
			)
		),
		// Настройка элементов действий над нодами (обязательны при action_nodes = true)
		"action_nodes_cgf" 	=> array(
			// Удалить правило доступа
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> false,
				"make_href" => create_function(
					'$node',
					'	global $_XFA, $id_mod;
					return sprintf($_XFA["delete_priv"], $id_mod, $node["id"]);'
				),
				"img_src"	=> 'images/but/del.gif',
				"confirm"	=> _('Вы уверены что хотите удалить?'),
				"title"		=> _('Удалить правило доступа')
			)
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> EDIT,
				"href"		=> sprintf($_XFA['form_priv'], $id_mod),
				"html"		=> _('Добавить правило<br />доступа')
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
