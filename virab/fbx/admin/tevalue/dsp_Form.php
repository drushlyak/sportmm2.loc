<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" language="JavaScript">
  var elem_array = Array();
  elem_array[1] = 'd1';
  elem_array[2] = 'd2';
<? include "../../../js/language.js.php"; ?>
</script>
<?

if($FORM_ERROR){
	echo "<p class=\"cerr\">".$FORM_ERROR."</p>";
}
// Проверка доступа
if(!$auth_in->isAllowed()){
	echo "<p class=\"cerr\">".$ACL_ERROR."</p>";
	return;
}
?>
<form name="editform" method="post" action="<?=$_XFA['store']?>">
<center><br><b><?=_("Перевод:")?>&nbsp;&nbsp;<span id="Translater"></span></b><br><br></center>
<script language="javascript">
<!--
<?

if($category['name']){
	echo "setGradusnik('&nbsp;\"{$category['name']}\"');";
}else{
	echo "setGradusnik('', '"._("Добавление шаблонной переменной")."');";
}

?>
chngLng(lng_now);
//-->
</script>
<?=(($category['id']) ? "<input type=\"hidden\" name=\"id\" value=\"".$category['id']."\">" : "")?>
<input type="hidden" name="pg" value="<?=intval($attributes['pg'])?>">
<input type="hidden" name="count_pg" value="<?=intval($attributes['count_pg'])?>">
<input type='hidden' name='acl' value='1'>
<table width="100%"  border="0" cellpadding="0" cellspacing="4">
	<tr>
		<td width="20%"><?=_("Переменная");?></td>
		<td>
			{&nbsp;<input name="name" type="text" id="name" size=50 value="<?=htmlspecialchars($category['name'],ENT_QUOTES)?>">&nbsp;}
		</td>
	</tr>
	<tr>
		<td valign="top"><?=_("Описание");?></td>
		<td>
<?
			$lng->textArea(1, 'description', $category['description'], array('cols' => "100%", 'rows' => "8"));
?>
		</td>
	</tr>
	<tr>
		<td width="20%"><?=_("Файл");?></td>
		<td>
			<input name="file" type="text" id="file" size=50 value="<?=htmlspecialchars($category['file'],ENT_QUOTES)?>">
		</td>
	</tr>
	<tr>
		<td valign="top">&nbsp;</td>
		<td>
			<input name="btnS" type="submit" id="btnS" value="<?=_("Сохранить");?>">
		</td>
	</tr>
</table>
</form>
