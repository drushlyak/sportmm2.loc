<?php
	$table_config = array(
		"id"				=> 't_site_map_list_grid',
		"type"				=> 'list',
		"url"				=> $_XFA['main'],
		"nodeSet"			=> $nodeSet,
		"resID"				=> $resourceId,
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> true,
		"action_nodes"		=> true,
		"form_action"		=> sprintf($_XFA['delete'], 0),
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('width' => '50%', 'title' => _('Страницы'), 'html' => _('Страницы')),
			array('width' => '20%', 'title' => _('ЧПУ'), 'html' => _('ЧПУ')),
			array('width' => '5%', 'title' => _('Статус'), 'html' => _('Статус'))
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля "Страницы"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	global $lng;
						return (($node["level"] <= 1) ? $node["chpu"]." - " : "") . $lng->Gettextlng($node["name"]);'
				)
			),
			// Функция поля "ЧПУ"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	return $node["chpu"];'
				)
			),
			// Функция поля "Статус"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	$res_str = "";
						if ($node["enable"])
							$res_str .= "<img src=\"images/but/active_yes.gif\" width=17 height=16 border=0 alt=\"' . _("Страница активна и участвует в отображении") . '\">";
						else
							$res_str .= "<img src=\"images/but/active_not.gif\" width=17 height=16 border=0 alt=\"' . _("Страница неактивна и отображение ее невозможно") . '\">";

						if ($node["printable"])
							$res_str .= "<img src=\"images/but/printable_yes.gif\" width=17 height=16 border=0 alt=\"' . _("Страница будет иметь пункт для вывода ее на печать") . '\">";
						else
							$res_str .= "<img src=\"images/but/printable_not.gif\" width=17 height=16 border=0 alt=\"' . _("Страница для печати недоступна") . '\">";

						return $res_str;
					'
				)
			)
		),
		// Настройка элементов действий над нодами (обязательны при action_nodes = true)
		"action_nodes_cgf" 	=> array(
			// Новая страница
			array(
				"acl_rule"	=> CREATE,
				"res_innod"	=> true,
				"href"		=> sprintf($_XFA['form'], 1, '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/newusr.gif',
				"title"		=> _('Новая страница')
			),
			// Редактировать
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> true,
				"href"		=> sprintf($_XFA['form'], 2, '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/ed.gif',
				"title"		=> _('Редактировать')
			),
			// Редактировать содержимое
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> true,
				"href"		=> sprintf($_XFA['mainexecp'], '!node_id!'),
				"check_func"=> create_function(
					'$node',
					'return $node["id_contaner"] != 0;'
				),
				"has_id"	=> true,
				"url_id"	=> 'id1',
				"img_src"	=> 'images/but/edusr.gif',
				"title"		=> _('Редактировать содержимое')
			),
			// Редактировать комбинированые шаблоны
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> true,
				"href"		=> sprintf($_XFA['selective'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/edcombtmpl.gif',
				"title"		=> _('Редактировать комбинированые шаблоны')
			),
			// Редактировать переменные
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> true,
				"href"		=> sprintf($_XFA['variable'], '!node_id!'),
				"check_func"=> create_function(
					'$node',
					'return $node["id"];'
				),
				"has_id"	=> true,
				"img_src"	=> 'images/but/edvars.gif',
				"title"		=> _('Редактировать переменные')
			),
			// Поменять родителя
			array(
				"acl_rule"	=> CHANGE_PARENT,
				"res_innod"	=> true,
				"href"		=> sprintf($_XFA['ch_parent'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/edgr.gif',
				"title"		=> _('Поменять родителя')
			),
			// Переместить узел выше
			array(
				"acl_rule"	=> CHANGE_POSITION,
				"res_innod"	=> true,
				"href"		=> sprintf($_XFA['ch_pos_top'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/top.gif',
				"title"		=> _('Переместить узел выше')
			),
			// Переместить узел ниже
			array(
				"acl_rule"	=> CHANGE_POSITION,
				"res_innod"	=> true,
				"href"		=> sprintf($_XFA['ch_pos_bottom'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/bottom.gif',
				"title"		=> _('Переместить узел ниже')
			),
			// Удалить
			array(
				"acl_rule"	=> DELETE,
				"res_innod"	=> true,
				"href"		=> sprintf($_XFA['delete'], '!node_id!'),
				"has_id"	=> true,
				"img_src"	=> 'images/but/del.gif',
				"title"		=> _('Удалить'),
				"confirm"	=> _('Вы уверены что хотите удалить данный раздел?')
			)
		),
		// настройки глобальных действий
		"main_action_block" => array(
			array(
				"acl_rule"	=> CREATE,
				"href"		=> sprintf($_XFA['form'], 1, $parent_id),
				"html"		=> _('Создать узел<br />верхнего уровня')
			),
			array(
				"acl_rule"	=> DELETE,
				"href"		=> '#',
				"html"		=> _('Удалить выбранные<br />узлы'),
				"confirm"	=> _('Вы уверены что хотите удалить выделенные узлы?')
			)
		)
	);

?><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<style type="text/css">
	.top_div {
		text-align: right;
		padding: 5px 10px 0 0;
	}
</style>


<?php if($attributes['str_error']): ?>
	<p class="cerr"><?=$attributes['str_error']?></p>
<?php endif;?>

<?php if (!$auth_in->isAllowed()): ?>
	<p class="cerr"><?=$ACL_ERROR?></p>
<?php return; endif; ?>

<div class="top_div">
	<a href="<?=$_XFA['ch_type_visual']?>"><b> &darr; <?=_("Отобразить в виде дерева")?></b></a>
</div>

<?php
	$dsp_helper->writeTable($table_config);
?>