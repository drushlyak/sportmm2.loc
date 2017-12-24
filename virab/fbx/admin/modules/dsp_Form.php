<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" language="JavaScript">
  var elem_array = Array();
  elem_array[1] = 'd1';
  elem_array[2] = 'd2';
<?php include "../../../js/language.js.php"; ?>
</script>
<?php
if($FORM_ERROR){
	echo "<p class=\"cerr\">".$FORM_ERROR."</p>";
}
// Проверка доступа
if(!$auth_in->isAllowed()){
	echo "<p class=\"cerr\">".$ACL_ERROR."</p>";
	return;
}
?>
<form name="form1" method="post" action="<?=$_XFA['store']?>" enctype="multipart/form-data">
<center><br><b><?=_("Перевод:")?>&nbsp;&nbsp;<span id="Translater"></span></b><br><br></center>
<input type='hidden' name='typ' id='typ' value=<?=$typ?>>
<input type='hidden' name='id' id='id' value=<?=$id?>>
<input type='hidden' name='acl' value='1'>
<script language="javascript">
<!--
<?php
  if ($category['name']) {
     // echo "setGradusnik('&nbsp;\"".$lng->Gettextlng($name)."\"');";
  } else {
      //echo "setGradusnik('', '"._("Добавление модуля")."');";
  }
?>
  chngLng(lng_now);
//-->
</script>
<?=(($category['id']) ? "<input type=\"hidden\" name=\"id\" value=\"".$category['id']."\">" : "")?>
  <table width="100%"  border="0" cellpadding="0" cellspacing="4">
    <tr>
      <td width="20%"><?=_("Название:");?></td>
      <td><?php $lng->textField(1, 'name', $category['name'], array('size' => 50)) ?></td>
    </tr>
<?php
if ($typ == 2):
?>
    <tr>
      <td width="20%"><?=_("Описание:");?></td>
      <td><?php $lng->textArea(2, 'description', $category['description'], array('cols' => "80%", 'rows' => 8)) ?></td>
    </tr>
<?php
endif;
?>
    <tr>
    	<td valign="top"><?=_("Видимость:");?></td>
    <td>
<?php
			$selected = array();
			$resArray['0'] =  _("Нет");
			$resArray['1'] = _("Да");
			$selected = ($category['enabled'] == '0') ? '0' : '1';
			html_select($resArray, $selected, 'enabled', false);
?>
		</td>
	</tr>
<?php
if ($typ == 1):
?>
    <tr>
      <td width="20%"><?=_("Загрузить");?></td>
      <td><input type="file" name="zipfile" size="30" value=""></td>
    </tr>
<?php
	endif;
?>
    <tr>
      <td valign="top">&nbsp;</td>
      <td><input name="btnS" type="submit" id="btnS" value="<?=_("Сохранить");?>"></td>
    </tr>
  </table>
</form>