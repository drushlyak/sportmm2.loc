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
<form name="editform" method="post" action="<?=$_XFA['store']?>">
<center><br><b><?=_("Перевод:")?>&nbsp;&nbsp;<span id="Translater"></span></b><br><br></center>
<script language="javascript">
<!--
<?php

if($typ == 2){
	echo "setGradusnik('&nbsp;\"".htmlspecialchars($lng->Gettextlng($title),ENT_QUOTES)."\"');";
}else{
	if($parent[0]['title']){
		echo "setGradusnik('&nbsp;"._("дочернего к ")."&nbsp;\"".$lng->Gettextlng($parent[0]['title'])."\"', '"._("Добавление раздела меню")."');";
	}else{
		echo "setGradusnik('', '"._("Добавление нового раздела меню")."');";
	}
}

?>
chngLng(lng_now);
//-->
</script>
<?=(($category['id']) ? "<input type=\"hidden\" name=\"id\" value=\"".$category['id']."\">" : "")?>
<?=(($parent_id) ? "<input type=\"hidden\" name=\"parent_id\" value=\"".$parent_id."\">" : "")?>
<input type='hidden' name='acl' value='1'>
<table width="100%"  border="0" cellpadding="0" cellspacing="4">
	<tr>
		<td width="20%"><?=_("Название");?></td>
      	<td>
       		<?php $lng->textField(1, 'title', $category['title'], array('size' => 50)); ?>
      	</td>
    </tr>
    <tr>
    	<td width="20%"><?=_("URL");?></td>
    	<td>
    		<input name="url" type="text" id="name" size=50 value="<?=$category['url']?>">
    	</td>
    </tr>
    <tr>
    	<td width="20%"><?=_("Раздел меню");?></td>
    	<td>
<?php
			$selected = array();
			$resArray['0'] =  _("Нет");
			$resArray['1'] = _("Да");
			$selected = ($category['menu'] == '0') ? '0' : '1';
			html_select($resArray, $selected, 'menu', false);
?>
		</td>
	</tr>
	<tr>
		<td width="20%"><?=_("Редактор записи");?></td>
		<td>
<?php
			$selected = array();
			$resArray['0'] =  _("Нет");
			$resArray['1'] = _("Да");
			$selected = ($category['edt'] == '0') ? '0' : '1';
			html_select($resArray, $selected, 'edt', false);
?>
		</td>
	</tr>
	<tr>
		<td valign="top"><?=_("Описание");?></td>
		<td>
<?php
			$lng->textArea(2, 'quick_help', $category['quick_help'], array('cols' => "100%", 'rows' => 8)); ?>
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