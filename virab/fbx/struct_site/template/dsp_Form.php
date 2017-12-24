<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" language="JavaScript">
  var elem_array = Array();
  elem_array[1] = 'd1';
  elem_array[2] = 'd2';
<?php include "../../../js/language.js.php"; ?>
function Select_executor(objForm)
{
	targetElement1 = document.getElementById("code_raw1");
    targetElement2 = document.getElementById("code_raw2");
    targetElement1.style.display = (objForm.type_template.value != <?=TE_VALUE_EXECUTOR?> || objForm.type_executor.value == <?=TE_EXECUTOR_CODE?> || objForm.type_executor.value == <?=TE_EXECUTOR_WYSIWYG?> || objForm.type_executor.value == <?=TE_EXECUTOR_SIMPLE?>) ? "none" : "";
    targetElement2.style.display = (objForm.type_template.value != <?=TE_VALUE_EXECUTOR?> || objForm.type_executor.value != <?=TE_EXECUTOR_CODE?>) ? "none" : "";
    <?=(($category['type_executor'] == TE_EXECUTOR_WYSIWYG) ? "document.getElementById('warntext').innerHTML = (objForm.type_executor.value == ".TE_EXECUTOR_WYSIWYG." || objForm.type_executor.value == ".TE_EXECUTOR_SIMPLE.") ? \""._("Смена типа на любой внешний формат хранения данных приведет к удалению всех WYSIWYG элементов этого исполнителя в базе данных !!!")."\" : \"<font color=#FF0000>"._("Смена типа на любой внешний формат хранения данных приведет к удалению всех WYSIWYG элементов этого исполнителя в базе данных !!!")."</font>\";" : "")?>
    switch(objForm.type_executor.value){
<?php
		$i = 0;
		foreach($__TYPE_EXECUTOR as $executor_type){
			$i++;
			echo "case '".$i."':";
			echo "document.getElementById('code_name1').innerHTML = \"".$executor_type['text']."\"; document.getElementById('code_name2').innerHTML = \"".$executor_type['text']."\"; break;";
		}
?>
	}
}

function Select_template()
{
	var objForm = document.getElementById("form1");
	targetElement1 = document.getElementById("contaner_code");
	targetElement2 = document.getElementById("typ_executor");
	targetElement3 = document.getElementById("param_executor");
	targetElement4 = document.getElementById("selective");
	targetElement5 = document.getElementById("printable");
	targetElement6 = document.getElementById("code_raw2");
// Исполнитель
	if(objForm.type_template.value == <?=TE_VALUE_EXECUTOR?>){
		targetElement1.style.display = "none";
		targetElement2.style.display = "";
		targetElement3.style.display = "";
		if(targetElement4 != null){
			targetElement4.style.display = "";
		}
		targetElement5.style.display = "";
		Select_executor(objForm);
	}
// Страница
	if(objForm.type_template.value == <?=TE_VALUE_PAGE?>){
		targetElement1.style.display = "";
		targetElement2.style.display = "none";
		targetElement3.style.display = "none";
		if(targetElement4 != null){
			targetElement4.style.display = "";
		}
		targetElement5.style.display = "";
		targetElement6.style.display = "none";
	}
// Папка
	if(objForm.type_template.value == <?=TE_VALUE_FOLDER?>){
		targetElement1.style.display = "none";
		targetElement2.style.display = "none";
		targetElement3.style.display = "none";
		if(targetElement4 != null){
			targetElement4.style.display = "none";
		}
		targetElement5.style.display = "none";
		targetElement6.style.display = "none";
	}
// Контейнер
	if(objForm.type_template.value == <?=TE_VALUE_CONTANER?>){
		targetElement1.style.display = "";
		targetElement2.style.display = "none";
		targetElement3.style.display = "none";
		if(targetElement4 != null){
			targetElement4.style.display = "";
		}
		targetElement5.style.display = "";
		targetElement6.style.display = "none";
	}
// Пользовательский
	if(objForm.type_template.value == <?=TE_VALUE_USER?>){
		targetElement1.style.display = "none";
		targetElement2.style.display = "none";
		targetElement3.style.display = "none";
		if(targetElement4 != null){
			targetElement4.style.display = "none";
		}
		targetElement5.style.display = "none";
		targetElement6.style.display = "none";
	}
// Комбинированный
	if(objForm.type_template.value == <?=TE_VALUE_SELECT?>){
		targetElement1.style.display = "none";
		targetElement2.style.display = "none";
		targetElement3.style.display = "";
		if(targetElement4 != null){
			targetElement4.style.display = "";
		}
		targetElement5.style.display = "";
		targetElement6.style.display = "none";
	}

}
</script>
<?php
if($FORM_ERROR){
?>
	<p class="cerr"><?=$FORM_ERROR?></p>
<?php
}
// Проверка доступа
if(!$auth_in->isAllowed()){
	echo "<p class=\"cerr\">".$ACL_ERROR."</p>";
	return;
}
if(!$category['type_template']){
	$category['type_template'] = TE_VALUE_CONTANER;
}
?>
<form name="form1" method="post" action="<?=$_XFA['store']?>" id='form1'>
<center><br><b><?=_("Перевод:")?>&nbsp;&nbsp;<span id="Translater"></span></b><br><br></center>
<script language="javascript">
<!--
<?php
  	if($category['name']){
    	echo "setGradusnik('&nbsp;\"".$lng->Gettextlng($name)."\"');";
  	}else{
  		echo "setGradusnik('', '"._("Добавление шаблона")."');";
  	}
?>
  chngLng(lng_now);
//-->
</script>
<input type="hidden" name="id" value="<?=$attributes['id']?>">
<input type="hidden" name="typ" value="<?=$attributes['typ']?>">
<input type="hidden" name="parent_id" value="<?=$parent_id?>">
<input type='hidden' name='res_id' value="<?=$category['res_id']?>">
<input type='hidden' name='acl' value='1'>
<table width="100%"  border="0" cellpadding="0" cellspacing="4">
<tr>
	<td width="20%"><?=_("Переменная");?></td>
	<td>
<?php
	if($category['id_te_value']){
		echo "<b>".((isset($category['id_te_value']) && $category['id_te_value']) ? htmlspecialchars(getTeValueName($category['id_te_value']),ENT_QUOTES) : '')."</b>";
		echo "<input name=\"value\" id=\"value\" type=\"hidden\" value=\"".htmlspecialchars(getTeValueName($category['id_te_value']),ENT_QUOTES)."\">";
	}else{
		echo "<input name=\"value\" id=\"value\" value=\"cont".substr($lng->NewId(), 0, 5)."\">";
	}
?>
	</td>
</tr>
<tr>
	<td width="20%"><?=_("Название");?></td>
	<td>
<?php
		$lng->textField(1, 'name', $category['name'], array('size' => 50));
?>
	</td>
</tr>
<tr>
	<td width="20%"><?=_("Тип шаблона");?></td>
	<td>
		<select name="type_template" id="type_template" onChange="Select_template();">
<?php
	foreach($__TYPE_TE_VALUE as $id => $template_type){
		echo "<option value='$id' ".(($category['type_template'] == $id) ? "selected" : "").">".$template_type['text']."</option>";
	}

?>
		</select>
	</td>
</tr>
<tr id="contaner_code"<?=(($category['type_template'] == TE_VALUE_EXECUTOR) ? " style=\"display: none;\"" : "")?>>
	<td valign="top"><?=_("Код");?></td>
	<td>
		<textarea name="acode" id="acode" cols="100%" rows="15"><?=($category['type_executor'] == 1 || $category['type_template'] == TE_VALUE_CONTANER)?htmlspecialchars($category['code'],ENT_QUOTES):""?></textarea>
	</td>
</tr>
<tr id="typ_executor"<?=(($category['type_template'] == TE_VALUE_EXECUTOR) ? "" : " style=\"display: none;\"")?>>
	<td width="20%"><?=_("Тип исполнителя");?></td>
	<td>
		<select name="type_executor" id="type_executor" onChange="Select_executor(this.form);">
<?php
			foreach($__TYPE_EXECUTOR as $id => $executor_type){
				echo "<option value='$id' ".(($category['type_executor'] == $id) ? "selected" : "").">".$executor_type['text']."</option>";
			}
?>
		</select>
		<span id="warntext">
			<?=(($category['type_executor'] == TE_EXECUTOR_WYSIWYG) ? _("Смена типа на любой внешний формат хранения данных приведет к удалению всех WYSIWYG элементов этого исполнителя в базе данных !!!") : "")?>
		</span>
	</td>
</tr>
<tr id="param_executor"<?=(($category['type_template'] == TE_VALUE_EXECUTOR) ? "" : " style=\"display: none;\"")?> height=30>
	<td valign="middle"><?=_("Параметры исполнителя");?></td>
	<td>
		<span style="color: #000000;">
			<?=_("Контент:");?>&nbsp;&nbsp;
			<input name="content" type="checkbox" id="content"<?=($category['content']) ? " checked" : ""?>>
		</span>
	</td>
</tr>

<tr id='printable'>
	<td width="20%"><?=_("Отображение печать/экран:");?></td>
	<td>
		<select name="printable" id="printable">
<?php
	foreach($__TYPE_PRINT as $id => $print_type){
		echo "<option value='$id' ".(($category['printable'] == $id) ? "selected" : "").">".$print_type."</option>";
	}
?>
		</select>
	</td>
</tr>
<tr id="code_raw1"<?=(($category['type_template'] <> TE_VALUE_EXECUTOR || $category['type_executor'] == TE_EXECUTOR_WYSIWYG || $category['type_executor'] == TE_EXECUTOR_SCREEN_CODE) ? " style=\"display: none;\"" : "")?>>
	<td id="code_name1" width="20%">
		<?=$__TYPE_EXECUTOR[$category['type_executor']]['text']?>
	</td>
	<td id="code_field1">
	<input name="code" type="text" id="code" size=50 value="<?=($category['type_executor'] == 2)?htmlspecialchars($category['code'],ENT_QUOTES):""?>">
	</td>
</tr>
<tr id="code_raw2"<?=(($category['type_template'] <> TE_VALUE_EXECUTOR || $category['type_executor'] <> TE_EXECUTOR_SCREEN_CODE) ? " style=\"display: none;\"" : "")?>>
	<td id="code_name2" width="20%">
		<?=$__TYPE_EXECUTOR[$category['type_executor']]['text']?>
	</td>
	<td id="code_field2">
<?php
		$lng->textArea(2, 'ccode', $category['code'], array('cols' => "100%", 'rows' => '15'));
?>
	</td>
</tr>
<?php
	if($can_be_selective){
?>
<tr>
	<td>&nbsp;</td>
	<td>
		<div id='selective'>
			<input type='checkbox' name='is_selective' <?=($category['is_selective'])?" checked ":" " ?> value='1'><?=_("Селективный шаблон");?>
		</div>
	</td>
</tr>
<?php
	}
?>
<tr>
	<td valign="top">&nbsp;</td>
	<td>
		<input name="btnS" type="submit" id="btnS" value="<?=_("Сохранить");?>">
	</td>
</tr>
</table>
</form>
<script type='text/javascript'>
$(function()
	{
		editAreaLoader.init({
			id : "code"		// textarea id
			,syntax: "html"			// syntax to be uses for highgliting
			,start_highlight: true		// to display with highlight mode on start-up
			,min_width: 800,
			min_height: 400,
			display: "later",
			language: "ru"
		});
		editAreaLoader.init({
			id : "acode"		// textarea id
			,syntax: "html"			// syntax to be uses for highgliting
			,start_highlight: true		// to display with highlight mode on start-up
			,min_width: 800,
			min_height: 400,
			display: "later",
			language: "ru"
		});
		Select_template();
	}
);
</script>