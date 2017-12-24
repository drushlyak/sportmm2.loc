<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" language="JavaScript">
  var elem_array = Array();
  elem_array[1] = 'd1';
<?php include "../../../js/language.js.php"; ?>
</script>
<?php
if($FORM_ERROR){
	?><p class="cerr"><?=$FORM_ERROR?></p><?php
}
// Проверка доступа
if(!$auth_in->isAllowed()){
	echo "<p class=\"cerr\">".$ACL_ERROR."</p>";
	return;
}
?>
<form name="editform" method="post" action="<?=$_XFA['storebigpage']?>">
<center><br><b><?=_("Перевод:")?>&nbsp;&nbsp;<span id="Translater"></span></b><br><br></center>
<script language="javascript">
<!--
<?php
  if ($exect['name'] || $parent[0]['name'])
      //echo "setGradusnik('&nbsp;\"".$lng->Gettextlng($exect['name'])."\" "._("для")." \"".$lng->Gettextlng($parent[0]['name'])."\"');";
?>
  chngLng(lng_now);
//-->
</script>
<input type="hidden" name=id1 value="<?=$attributes['id1']?>">
<input type="hidden" name=id2 value="<?=$attributes['id2']?>">
<input type="hidden" name='typ' value="<?=$typ?>">
<input type="hidden" name='idp' value="<?=$id?>">
<input type='hidden' name='acl' value='1'>
  <table width="100%"  border="0" cellpadding="0" cellspacing="4">
    <tr>
      <td><?=_("Содержимое");?>:</td>
    </tr>
    <tr>
      <td>
<?php
	$lng->richEdit(1, 'text', $page['text']);
?>
      </td>

    </tr>
    <tr>
      <td><input name="btnS" type="submit" id="btnS" value="<?=_("Сохранить");?>"></td>
    </tr>
  </table>
</form>