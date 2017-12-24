<?php

	$table_config = array(
		"id"				=> 't_site_node_executors_grid',
		"type"				=> 'list',
		"url"				=> $_XFA['mainexec'],
		"nodeSet"			=> $Executor,
		"resID"				=> $parent['res_id'],
		"acl_rule_view"		=> VIEW,
		"select_nodes"		=> false,
		"action_nodes"		=> true,
		"form_action"		=> "",
		// Массив настроек заголовка таблицы
		"table_header"		=> array(
			array('width' => '3%', 'title' => '', 'html' => ''),
			array('width' => '50%', 'title' => _('Исполнитель'), 'html' => ('Исполнитель')),
			array('width' => '25%', 'title' => _('Шаблонная переменная'), 'html' => _('Шаблонная переменная')),
			array('width' => '5%', 'title' => _('Статус'), 'html' => _('Статус'))
		),
		// Фунции логики вывода данных (внимательно к экранированию!)
		"data_nodes_cfg"	=> array(
			// Функция поля ""
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	global $lng;
						return "<img src=\"images/page_executor.gif\">";
					'
				)
			),
			// Функция поля "Исполнитель"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	global $lng;
						return "<span class=\"bld\" title=\"" . $node["path"] . "\">" . $lng->Gettextlng($node["name"]) . "</span><br/>&nbsp;&nbsp;<span>" . $node["path"] . "</span>";
					'
				)
			),
			// Функция поля "Шаблонная переменная"
			array(
				"align"	=> 'center',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	global $lng;
						return "<span title=\"" . $node["path"] . "\">" . $node["te_var"] . "</span>";
					'
				)
			),
			// Функция поля "Статус"
			array(
				"align"	=> 'left',
				"args"	=> '$node',
				"func"	=> create_function(
					'$node',
					'	$res_str = "";

						if ($node["printable"]) {
							$res_str .= "<img src=\"images/but/printable_yes.gif\" border=0 alt=\"' . _("Страница будет иметь пункт для вывода ее на печать") . '\" title=\"' . _("Страница будет иметь пункт для вывода ее на печать") . '\">";
						} else {
							$res_str .= "<img src=\"images/but/printable_not.gif\" border=0 alt=\"' . _("Страница не доступна для печати") . '\" title=\"' . _("Страница не доступна для печати") . '\">";
						}

						if ($node["bigpage"]) {
							$res_str .= "<img src=\"images/but/big_yes.gif\" border=0 alt=\"' . _("Многостраничная страница. Для улучшения читабельности производится разбивка на страницы") . '\" title=\"' . _("Многостраничная страница. Для улучшения читабельности производится разбивка на страницы") . '\">";
						} else {
							$res_str .= "<img src=\"images/but/big_not.gif\" border=0 alt=\"' . _("Обыкновенная одностраничная страница") . '\" title=\"' . _("Обыкновенная одностраничная страница") . '\">";
						}

						return $res_str;
					'
				)
			)
		),
		// Настройка элементов действий над нодами (обязательны при action_nodes = true)
		"action_nodes_cgf" 	=> array(
			// Редактировать содержимое исполнителя в одностраничном режиме
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> false,
				"make_href"	=> create_function(
						'$node',
						'	global $_XFA, $id1;
		 					return sprintf($_XFA["editexeccont"], $id1, $node["id"], $node["type_executor"]);'
				),
				"img_src"	=> 'images/but/edusr.gif',
				"title"		=> _('Редактировать содержимое исполнителя в одностраничном режиме')
			),
			// Редактировать содержимое исполнителя в многостраничном режиме
			array(
				"acl_rule"	=> EDIT,
				"res_innod"	=> false,
				"make_href"	=> create_function(
						'$node',
						'	global $_XFA, $id1;
		 					return sprintf($_XFA["mainbig"], $id1, $node["id"]);'
				),
				"img_src"	=> 'images/but/edbigpage.gif',
				"title"		=> _('Редактировать содержимое исполнителя в многостраничном режиме')
			)
		),
		// настройки глобальных действий
		"main_action_block" => array(

		)
	);

?><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


<script type="text/javascript">
<?php
  if ($category['name'])
      echo "setPagerTitle('&nbsp;" . _("для узла") . "&nbsp;\"" . $lng->Gettextlng($category['name']) . "\"');";
?>
</script>

<?php if($attributes['str_error']): ?>
	<p class="cerr"><?=$attributes['str_error']?></p>
<?php endif;?>

<?php if (!$auth_in->isAllowed()): ?>
	<p class="cerr"><?=$ACL_ERROR?></p>
<?php return; endif; ?>

<?php
	$dsp_helper->writeTable($table_config);
?>


















<?php /*
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" src="js/list.js"></script>
<?
if($attributes['str_error']){
	echo "<p class=\"cerr\">".$attributes['str_error']."</p>";
}
// Проверка доступа
if(!$auth_in->isAllowed()){
	echo "<p class=\"cerr\">".$ACL_ERROR."</p>";
	return;
}
?>
<form name="form1" method="post" action="">
<script language="JavaScript">
<!--
<?
  if ($category['name'])
      echo "setGradusnik('&nbsp;"._("для")."&nbsp;\"".$lng->Gettextlng($category['name'])."\"');";
?>
//-->
</script>
<input type="hidden" name=id1 value="<?=$attributes['id1']?>">
<table width=100% border=0 cellpadding=0 cellspacing=0>
  <tr>
    <td colspan=5 width=100% style="padding: 7px;">
      <table width=100% border=0 cellpadding=0 cellspacing=0>
        <tr>
          <td style="text-align: left;"><?=leftPageStr()?></td>
          <td style="text-align: center;"><?=centerPageStr($_XFA['mainexec'])?></td>
          <td style="text-align: right;"><?=rightPageStr($_XFA['mainexec'])?></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan=5 bgcolor=#E6E6E6 width=100%><img src="images/spacer.gif" width=1 height=2 border=0></td>
  </tr>
  <tr>
    <td width=0% valign=middle bgcolor="#F9F9F9" style="text-align: center;" title="<?=_("Отметить / снять отметку со всех элементов");?>"><input type="checkbox" name="allbox" value="1" onclick="CheckAll('form1');" /></td>
    <td width=50% height="19" bgcolor="#F9F9F9" style="text-align: center;" valign=middle class="headTable"><?=_("Исполнитель");?></td>
    <td width=15% height="19" bgcolor="#F9F9F9" style="text-align: center;" valign=middle class="headTable"><?=_("Шаблонная переменная");?></td>
    <td width=10% height=19 bgcolor=#F9F9F9 style="text-align: center;" valign=middle class="headTable"><?=_("Статус");?></td>
    <td width=20% height=19 bgcolor=#F9F9F9 style="text-align: center;" valign=middle class="headTable"><?=_("Действия");?></td>
  </tr>
  <tr>
    <td colspan=5 bgcolor=#FF0000 width=100%><img src="images/spacer.gif" width=1 height=2 border=0></td>
  </tr>
  <?
    if (is_array($Executor)) {
        $currentLevel = 1;
        $lvl[1][0] = 0;
        $lvl[1][1] = 1;

        foreach ($Executor as $node) {
  ?>
  <tr id="d<?=$lvl[$node['level']][0]?>_<?=$lvl[$node['level']][1]?>" onmouseout=delite(this); onmouseover=hilite(this); rw="<?=$lvl[$node['level']][0]?>_<?=$lvl[$node['level']][1]?>"<? if ($lvl[$node['level']][0]) { echo " style=\"display: None;\"";} ?>>
    <td valign="middle" width="20"><input type="checkbox" name="did[]" value="<?=$node['id']?>"></td>
    <td height=19 style="text-align: left; padding-left: 20px;" valign=middle title="<?=$node['path']?>"><?=$lng->Gettextlng($node['name'])?></td>
    <td height=19 style="text-align: left; padding-left: 20px;" valign=middle title="<?=$node['path']?>"><?=$node['te_var']?></td>
    <td height=19 style="text-align: center;" valign=middle><?
      if ($node['printable'])
          echo "<img src=\"images/but/printable_yes.gif\" width=17 height=16 border=0 alt=\""._("Страница будет иметь пункт для вывода ее на печать")."\">";
      else
          echo "<img src=\"images/but/printable_not.gif\" width=17 height=16 border=0 alt=\""._("Страница для печати не доступна")."\">";

      if ($node['bigpage'])
          echo "<img src=\"images/but/big_yes.gif\" width=17 height=16 border=0 alt=\""._("Многостраничная страница. Для улучшения читабельности производиться разбивка на страницы")."\">";
      else
          echo "<img src=\"images/but/big_not.gif\" width=17 height=16 border=0 alt=\""._("Обыкновенная одностраничная страница")."\">";
    ?></td>
    <td style="text-align: center;">
      <? if($auth_in->aclCheck($parent['res_id'], EDIT)): ?>
      	<a href="<?=sprintf($_XFA['editexeccont'], $id1, $node['id'], $node['type_executor'])?>"><img src="images/but/ed.gif" width=17 height=16 border=0 alt="<?=_("Редактировать содержимое исполнителя в одностраничном режиме")?>"></a>&nbsp;&nbsp;
      <? endif ?>
      <? if($auth_in->aclCheck($parent['res_id'], VIEW)): ?>
      	<a href="<?=sprintf($_XFA['mainbig'], $id1, $node['id'])?>"><img src="images/but/fld1.gif" width=13 height=10 border=0 alt="<?=_("Редактировать содержимое исполнителя в многостраничном режиме")?>"></a>
      <? endif ?>
    </td>
  </tr>
  <tr id="f<?=$lvl[$node['level']][0]?>_<?=$lvl[$node['level']][1]?>"<? if ($lvl[$node['level']][0]) { echo " style=\"display: None;\"";} ?>>
    <td colspan=5 bgcolor=#E6E6E6 width=100%><img src="images/spacer.gif" width=1 height=2 border=0></td>
  </tr>
<?
            $lvl[$node['level']][1]++;
        }
    }
?>
  <tr>
    <td colspan=5 width=100% style="padding: 7px;">
      <table width=100% border=0 cellpadding=0 cellspacing=0>
        <tr>
          <td style="text-align: left;"><?=leftPageStr()?></td>
          <td style="text-align: center;"><?=centerPageStr($_XFA['mainexec'])?></td>
          <td style="text-align: right;"><?=rightPageStr($_XFA['mainexec'])?></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan=5 bgcolor=#FF0000 width=100%><img src="images/spacer.gif" width=1 height=2 border=0></td>
  </tr>
</table>
</form>
*/ ?>