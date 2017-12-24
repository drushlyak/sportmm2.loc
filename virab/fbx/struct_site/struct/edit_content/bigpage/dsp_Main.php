<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" src="js/list.js"></script>
<?php
if($attributes['str_error']){
	echo "<p class=\"cerr\">".$attributes['str_error']."</p>";
}
// Проверка доступа
if(!$auth_in->isAllowed()){
	echo "<p class=\"cerr\">".$ACL_ERROR."</p>";
	return;
}
?>
<form name="form1" method="post" action="<?=sprintf($_XFA['deletebigpage'], $id1, $id2, 0)?>">
<script language="JavaScript">
<!--
<?php
  if ($exect['name'] || $parent[0]['name'])
      echo "setGradusnik('&nbsp;\"".$lng->Gettextlng($parent[0]['name'])."\" "._("для")." \"".$lng->Gettextlng($exect['name'])."\"');";
?>
//-->
</script>
<input type="hidden" name=id1 value="<?=$attributes['id1']?>">
<input type="hidden" name=id2 value="<?=$attributes['id2']?>">
<table width=100% border=0 cellpadding=0 cellspacing=0> 
  <tr>
    <td colspan=4 width=100% style="padding: 7px;">
      <table width=100% border=0 cellpadding=0 cellspacing=0>
        <tr>
          <td style="text-align: left;"><?=leftPageStr()?></td>
          <td style="text-align: center;"><?=centerPageStr(sprintf($_XFA['mainbig'], $id1, $id2))?></td>
          <td style="text-align: right;"><?=rightPageStr(sprintf($_XFA['mainbig'], $id1, $id2))?></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr> 
    <td colspan=4 bgcolor=#E6E6E6 width=100%><img src="images/spacer.gif" width=1 height=2 border=0></td> 
  </tr> 
  <tr>
    <td width=0% valign=middle bgcolor="#F9F9F9" style="text-align: center;" title="<?=_("Отметить / снять отметку со всех элементов");?>"><input type="checkbox" name="allbox" value="1" onclick="CheckAll('form1');" /></td> 
    <td width=30% height="19" bgcolor="#F9F9F9" style="text-align: center;" valign=middle class="headTable"><?=_("Страница");?></td> 
    <td width=50% height="19" bgcolor="#F9F9F9" style="text-align: center;" valign=middle class="headTable"><?=_("Содержимое");?></td> 
    <td width=20% height=19 bgcolor=#F9F9F9 style="text-align: center;" valign=middle class="headTable"><?=_("Действия");?></td> 
  </tr> 
  <tr> 
    <td colspan=4 bgcolor=#FF0000 width=100%><img src="images/spacer.gif" width=1 height=2 border=0></td> 
  </tr>
  <?php
    if (is_array($pageSet)) {
        $currentLevel = 1;
        $lvl[1][0] = 0;
        $lvl[1][1] = 1;
      
        foreach ($pageSet as $node) {
  ?>
  <tr id="d<?=$lvl[$node['level']][0]?>_<?=$lvl[$node['level']][1]?>" onmouseout=delite(this); onmouseover=hilite(this); rw="<?=$lvl[$node['level']][0]?>_<?=$lvl[$node['level']][1]?>"<? if ($lvl[$node['level']][0]) { echo " style=\"display: None;\"";} ?>>
    <td valign="middle" width="20"><input type="checkbox" name="did[]" value="<?=$node['id']?>"></td> 
    <td height=19 style="text-align: left; padding-left: 20px;" valign=middle><?=_("Страница ").$node['page']?></td> 
    <td height=19 style="text-align: left; padding-left: 20px;" valign=middle><?=substr(strip_tags($lng->Gettextlng($node['text'])), 0, 50)?>...</td> 
    <td style="text-align: center;">
      <?php if($auth_in->aclCheck($parent_res['res_id'], EDIT)): ?>
      	<a href="<?=sprintf($_XFA['formbigpage'], 2, $id1, $id2, $node['id'])?>"><img src="images/but/ed.gif" width=17 height=16 border=0 alt="<?=_("Редактировать страницу");?>"></a> &nbsp; 
      <?php endif ?>
      <?php if($auth_in->aclCheck($parent_res['res_id'], DELETE)): ?>
      	<a href="<?=sprintf($_XFA['deletebigpage'], $id1, $id2, $node['id'])?>" onClick="return confirm('<?=_("Вы уверены, что хотите удалить данную страницу?");?>')"><img src="images/but/del.gif" width=17 height=16 border=0 alt="<?=_("Удалить страницу");?>"></a> &nbsp; 
      <?php endif ?>
      <?php if($auth_in->aclCheck($parent_res['res_id'], CHANGE_POSITION)): ?>
      	<a href="<?=sprintf($_XFA['ch_pos_topbig'], $id1, $id2, $node['id'])?>"><img src="images/but/top.gif" width=9 height=16 border=0 alt="<?=_("Переместить страницу выше");?>"></a> &nbsp; 
      	<a href="<?=sprintf($_XFA['ch_pos_bottombig'], $id1, $id2, $node['id'])?>"><img src="images/but/bottom.gif" width=9 height=16 border=0 alt="<?=_("Переместить страницу ниже");?>"></a>
      <?php endif ?>
    </td> 
  </tr> 
  <tr id="f<?=$lvl[$node['level']][0]?>_<?=$lvl[$node['level']][1]?>"<? if ($lvl[$node['level']][0]) { echo " style=\"display: None;\"";} ?>>
    <td colspan=4 bgcolor=#E6E6E6 width=100%><img src="images/spacer.gif" width=1 height=2 border=0></td> 
  </tr> 
<?php
            $lvl[$node['level']][1]++;
        }
    }
?>  
  <tr>
    <td colspan=4 width=100% style="padding: 7px;">
      <table width=100% border=0 cellpadding=0 cellspacing=0>
        <tr>
          <td style="text-align: left;"><?=leftPageStr()?></td>
          <td style="text-align: center;"><?=centerPageStr(sprintf($_XFA['mainbig'], $id1, $id2))?></td>
          <td style="text-align: right;"><?=rightPageStr(sprintf($_XFA['mainbig'], $id1, $id2))?></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr> 
    <td colspan=4 width=100% height=23 valign=bottom style="padding-bottom: 2px; padding-left: 20px;"><span style="color: #FF0000; text-align: left;"><b><?=_("Действия");?></b></span></td>
  </tr>
  <tr> 
    <td colspan=4 bgcolor=#FF0000 width=100%><img src="images/spacer.gif" width=1 height=2 border=0></td> 
  </tr>
  <tr> 
    <td colspan=4 width=100% height=20 valign=middle style="padding: 10px;">
     <table border=0 cellpadding=0 cellspacing=0 width=100%>
      <tr>
       <td width=50%>
         <?php if($auth_in->aclCheck($parent_res['res_id'], CREATE)): ?>
        <table border=0 cellpadding=0 cellspacing=0 width=100%>
         <tr>
          <td style="padding: 5px;"><img src="images/dwn_but.gif" border=0 alt=""></td>
          <td bgcolor=#737373><img src="images/spacer.gif" width=1 height=100% border=0 alt=""></td>
          	<td width=100% style="padding-left: 10px;"><a href="<?=sprintf($_XFA['formbigpage'], 1, $id1, $id2, 0)?>"><?=_("Добавить страницу");?></a></td>
         </tr>
        </table>
         <?php endif ?>
       </td>
       <td width=50%>
        <?php if($auth_in->aclCheck($parent_res['res_id'], DELETE)): ?>
        <table border=0 cellpadding=0 cellspacing=0 width=100%>
         <tr>
          <td style="padding: 5px;"><img src="images/dwn_but.gif" border=0 alt=""></td>
          <td bgcolor=#737373><img src="images/spacer.gif" width=1 height=100% border=0 alt=""></td>
          	<td width=100% style="padding-left: 10px;"><a href="#" onClick="if (confirm('<?=_("Вы уверены что хотите удалить выделенные страницы?");?>')) {form1.submit();} else {return false;}"><?=_("Удалить выбранные страницы");?></a></td>
         </tr>
        </table>
       <?php endif ?>
       </td>
      </tr>
     </table>
    </td>
  </tr>
</table>
</form>