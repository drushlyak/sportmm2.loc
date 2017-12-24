<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
if($FORM_ERROR){
	?><p class="cerr"><?=$FORM_ERROR?></p><?
}
// Проверка доступа
if(!$auth_in->isAllowed()){
	echo "<p class=\"cerr\">".$ACL_ERROR."</p>";
	return;
}
?>
<form method="post" action="<?=$_XFA['ch_parent_store']?>">
<script language="javascript">
<!--  
<?php
  if ($category[0]['title'])
      echo "setGradusnik('&nbsp;"._("для")."&nbsp;\"".$lng->Gettextlng($category[0]['title'])."\"');";
?>
//-->
</script>
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="acl" value="1">
  <table width="100%"  border="0" cellpadding="0" cellspacing="4"> 
    <tr> 
      <td width="20%"><?=_("Новый родитель");?></td> 
      <td>
        <select name="parent_id">
<?php
  $ident = 0;
?>
          <option value="0">root</option>
<?php
  $currentLevel = 1;
  $currLevel = 1;
  foreach ($nodeSet as $node) {
    $found = 0;
    foreach ($exclude_nodeSet as $exclude_node)
      if ($node['id'] == $exclude_node['id']) {
        $found = 1;
        break;
      }
      
    if ($found != 0) continue;
    if (! $lng->Gettextlng($node['title']))
      $name = _("Безымянный");
    else
      $name = $lng->Gettextlng($node['title']);
      
    if ($node['id'] == $parentNode['id']) {
      $nid = $node['id'];
      print "<option selected value=\"$nid\">";
    } else {
      $nid = $node['id'];
      print "<option value=\"$nid\">";
    }
    
    for ($i=0; $i<$node['level']; $i++) print "&nbsp;&nbsp;&nbsp;";
    echo $name;
?>
          </option>
<?php  }?>
        </select>
      </td> 
    </tr> 
    <tr> 
      <td valign="top">&nbsp;</td> 
      <td><input name="btnS" type="submit" id="btnS" value="<?=_("Сохранить");?>"></td> 
    </tr> 
  </table> 
</form>