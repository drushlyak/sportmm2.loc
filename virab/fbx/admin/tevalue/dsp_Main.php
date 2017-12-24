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
<form name="form1" method="post" action="<?=sprintf($_XFA['delete'], 0, $pg+1, $count_pg)?>">
<input type="hidden" name="pg" value="<?=($pg+1)?>">
<input type="hidden" name="count_pg" value="<?=$count_pg?>">
<table width=100% border=0 cellpadding=0 cellspacing=0>
  <tr>
    <td colspan=6 width=100% style="padding: 7px;">
      <table width=100% border=0 cellpadding=0 cellspacing=0>
        <tr>
          <td style="text-align: left;"><?=leftPageStr()?></td>
          <td style="text-align: center;"><?=centerPageStr(sprintf($_XFA['main'], $pg+1, $count_pg, ""))?></td>
          <td style="text-align: right;"><?=rightPageStr(sprintf($_XFA['main'], $pg+1, $count_pg, ""))?></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan=6 bgcolor=#E6E6E6 width=100%><img src="images/spacer.gif" width=1 height=2 border=0></td>
  </tr>
  <tr>
    <td width=0% valign=middle bgcolor="#F9F9F9" style="text-align: center;" title="<?=_("Отметить / снять отметку со всех элементов");?>"><input type="checkbox" name="allbox" value="1" onclick="CheckAll('form1');" /></td>
    <td width=35% height="19" bgcolor="#F9F9F9" style="text-align: center;" valign=middle class="headTable"><?=_("Переменная");?></td>
    <td width=25% height="19" bgcolor="#F9F9F9" style="text-align: center;" valign=middle class="headTable"><?=_("Тип");?></td>
    <td width=5% height="19" bgcolor="#F9F9F9" style="text-align: center;" valign=middle class="headTable"><?=_("Системная");?></td>
    <td width=15% height="19" bgcolor="#F9F9F9" style="text-align: center;" valign=middle class="headTable"><?=_("Файл");?></td>
    <td width=20% height=19 bgcolor=#F9F9F9 style="text-align: center;" valign=middle class="headTable"><?=_("Действия");?></td>
  </tr>
  <tr>
    <td colspan=6 bgcolor=#FF0000 width=100%><img src="images/spacer.gif" width=1 height=2 border=0></td>
  </tr>
  <?
    if (is_array($nodeSet)) {
        $currentLevel = 1;
        $lvl[1][0] = 0;
        $lvl[1][1] = 1;

        foreach ($nodeSet as $node) {
  ?>
  <tr id="d<?=$lvl[$node['level']][0]?>_<?=$lvl[$node['level']][1]?>" onmouseout=delite(this); onmouseover=hilite(this); rw="<?=$lvl[$node['level']][0]?>_<?=$lvl[$node['level']][1]?>"<? if ($lvl[$node['level']][0]) { echo " style=\"display: None;\"";} ?>>
    <td valign="middle" width="20"><? if (! $node['sys']) { ?><input type="checkbox" name="did[]" value="<?=$node['id']?>"><? } ?></td>
    <td height=19 style="text-align: left; padding-left: 20px;" valign=middle>{<?=$node['name']?>}</td>
    <td height=19 style="text-align: center;" valign=middle><?=$__TYPE_TE_VALUE[$node['typ']]['text']?></td>
    <td height=19 style="text-align: center;" valign=middle>
    <?
      if ($node['sys'])
          echo "<img src=\"images/but/yes.gif\" width=17 height=16 border=0 alt=\""._("Системная переменая. Создана одним из элементов системы и ее редактирование возможно только из модуля создавшего ее.")."\">";
      else
          echo "<img src=\"images/but/not.gif\" width=17 height=16 border=0 alt=\""._("Пользовательская переменная")."\">";
    ?></td>
    <td height=19 style="text-align: center;" valign=middle><?=$node['file']?></td>
    <td style="text-align: center;"><? if (! $node['sys']) { ?>
      <? if($auth_in->aclCheck($resourceId, EDIT)): ?>
      	<a href="<?=sprintf($_XFA['form'], 2, $node['id'], $pg+1, $count_pg)?>"><img src="images/but/ed.gif" width=17 height=16 border=0 alt="<?=_("Редактировать");?>"></a> &nbsp;
      <? endif ?>
      <? if($auth_in->aclCheck($resourceId, DELETE)): ?>
      <a href="<?=sprintf($_XFA['delete'], $node['id'], $pg+1, $count_pg)?>" onClick="return confirm('<?=_("Вы уверены, что хотите удалить?");?>')"><img src="images/but/del.gif" width=17 height=16 border=0 alt="<?=_("Удалить");?>"></a>
      <? endif ?>
      <? } ?>
    </td>
  </tr>
  <tr id="f<?=$lvl[$node['level']][0]?>_<?=$lvl[$node['level']][1]?>"<? if ($lvl[$node['level']][0]) { echo " style=\"display: None;\"";} ?>>
    <td colspan=6 bgcolor=#E6E6E6 width=100%><img src="images/spacer.gif" width=1 height=2 border=0></td>
  </tr>
<?
            $lvl[$node['level']][1]++;
        }
    }
?>
  <tr>
    <td colspan=6 width=100% style="padding: 7px;">
      <table width=100% border=0 cellpadding=0 cellspacing=0>
        <tr>
          <td style="text-align: left;"><?=leftPageStr()?></td>
          <td style="text-align: center;"><?=centerPageStr(sprintf($_XFA['main'], $pg+1, $count_pg, ""))?></td>
          <td style="text-align: right;"><?=rightPageStr(sprintf($_XFA['main'], $pg+1, $count_pg, ""))?></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan=6 width=100% height=23 valign=bottom style="padding-bottom: 2px; padding-left: 20px;"><span style="color: #FF0000; text-align: left;"><b><?=_("Действия");?></b></span></td>
  </tr>
  <tr>
    <td colspan=6 bgcolor=#FF0000 width=100%><img src="images/spacer.gif" width=1 height=2 border=0></td>
  </tr>
  <tr>
    <td colspan=6 width=100% height=20 valign=middle style="padding: 10px;">
     <table border=0 cellpadding=0 cellspacing=0 width=100%>
      <tr>
       <td width=50%>
        <? if($auth_in->aclCheck($resourceId, CREATE)): ?>
        <table border=0 cellpadding=0 cellspacing=0 width=100%>
         <tr>
          <td style="padding: 5px;"><img src="images/dwn_but.gif" border=0 alt=""></td>
          <td bgcolor=#737373><img src="images/spacer.gif" width=1 height=100% border=0 alt=""></td>
          	<td width=100% style="padding-left: 10px;"><a href="<?=sprintf($_XFA['form'], 0, 0, $pg+1, $count_pg)?>"><?=_("Добавить переменную");?></a></td>

         </tr>
        </table>
         <? endif ?>
       </td>
       <td width=50%>
        <? if($auth_in->aclCheck($resourceId, DELETE)): ?>
        <table border=0 cellpadding=0 cellspacing=0 width=100%>
         <tr>
          <td style="padding: 5px;"><img src="images/dwn_but.gif" border=0 alt=""></td>
          <td bgcolor=#737373><img src="images/spacer.gif" width=1 height=100% border=0 alt=""></td>
          	<td width=100% style="padding-left: 10px;"><a href="#" onClick="if (confirm('<?=_("Вы уверены что хотите удалить выделенные переменные?");?>')) {form1.submit();} else {return false;}"><?=_("Удалить выбранные переменные");?></a></td>

         </tr>
        </table>
          <? endif ?>
       </td>
      </tr>
     </table>
    </td>
  </tr>
</table>
</form>