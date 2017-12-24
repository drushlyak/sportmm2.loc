<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" language="JavaScript">
//--------------------------------------------------------------------
function addVarField()
{
	var count = parseInt($("#count_var").val(), 10);
	count++;
	var clon = $("#new_var").clone().get(0);
	var new_id = 'RN_' + Math.random(1)*100000;
	clon.id = new_id.substr(0, 7);
	$(clon).appendTo('#vars');
	$("#"+clon.id+" .var_name").attr('name', 'attr_name[' + count + ']');
	$("#"+clon.id+" .default_value").attr('name', 'default_value[' + count + ']');
	$("#"+clon.id+" .del_attr").attr('name', 'del_attr[' + count + '][]');
	$("#"+clon.id+" .var_method").attr('name', 'var_method[' + count + ']');
	$("#"+clon.id+" .var_type").attr('name', 'var_type[' + count + ']');
	$("#count_var").val(count);
}
$(function()
	{
		$("select").change(function()
			{
				var el = $(this).parents("tbody").get(0);
				if(this.options[this.selectedIndex].value == "FILE"){
					$($("tr", el).get(2)).hide();
					$($("tr", el).get(0)).hide();
				}else{
					$($("tr", el).get(2)).show();
					$($("tr", el).get(0)).show();
				}
			}
		);
	}
);
//----------------------------------------------------------------------
</script>
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
<form name="form1" method="post" action="<?=$_XFA['variable_store']?>">
<center><br><b><?=_("Перевод:")?>&nbsp;&nbsp;<span id="Translater"></span></b><br><br></center>
<script language="javascript">
<!--
<?php


if($category['name']){
	echo "setGradusnik('&nbsp;\"".$lng->getTextlng($name)."\"');";
}else{
	echo "setGradusnik('', '" . _("Редактирование переменных узла") . "');";
}

?>
chngLng(lng_now);
//-->
</script>
<input type='hidden' name='id' value="<?=$id?>">
<input type='hidden' name='acl' value='1'>
<?php
$count_var = (is_array($attrSet))?count($attrSet):0;
?>
<input type='hidden' name='count_var' id='count_var' value='<?=intval($count_var)?>'>
<div id='vars'>
<?php
if(is_array($attrSet)){
	$i = 1;
	foreach($attrSet as $attr){
		if($attr['method'] == 'FILE'){
			$display = 'none';
		}else{
			$display = '';
		}
		$name = htmlspecialchars($attr['name'],ENT_QUOTES);
		$type_var = _("Тип переменной");
			$text =<<<EOF
<fieldset border='1' style='border:1px solid red;'>
<legend>Переменная*:<input type='text' value="{$name}" name='attr_name[{$i}]'></legend>
<table border=0 cellpadding=0 cellspacing=0 width='100%'>
	<tr style='display:{$display}'>
		<td width="30%">{$type_var}</td>
		<td>
		<input type='hidden' name='attr_id[{$i}]' value='{$attr['id']}'>
EOF;
		print $text;
		$resArray = array();
		$selected = array();
		$typeArray = array(
			'int' => _("Целое"),
			'string' => _("Строка"),
			'float' => _("Дробное")
		);
		foreach($typeArray as $t_id => $type){
			$resArray[$t_id] = $type;
			if($attr['var_type'] == $t_id){
				$selected[] = $t_id;
			}
		}
		$dom_name = 'var_type['.$i.']';
		html_select($resArray, $selected, $dom_name);
?>
		</td>
	</tr>
	<tr>
		<td width="30%"><?=_("Метод");?></td>
		<td>
<?php
		$resArray = array();
		$selected = array();
		$typeArray = array(
			'GET' => "GET",
			'POST' => "POST",
			'COOKIE' => "COOKIE",
			'FILE' => "FILE"
		);
		foreach($typeArray as $t_id => $type){
			$resArray[$t_id] = $type;
			if($attr['method'] == $t_id){
				$selected[] = $t_id;
			}
		}
		$dom_name = "var_method[".$i."]";
		html_select($resArray, $selected, $dom_name);
?>
<script type='text/javascript'>
$("#<?=$dom_name?>").addClass("var_method");
</script>
		</td>
	</tr>
	<tr style='display:<?=$display?>'>
		<td width="30%"><?=_("Значение по-умолчанию");?></td>
		<td>
			<input type='text' class='default_value' value="<?=htmlspecialchars($attr['default_value'],ENT_QUOTES);?>" name='default_value[<?=$i?>]' size='80'>
		</td>
	</tr>
	<tr>
		<td width="30%">&nbsp;</td>
		<td>
			<input type='checkbox' value="1" class='del_attr' name='del_attr[<?=$i?>][]'><?=_("Удалить переменную")?>
		</td>
	</tr>
</table>
</fieldset>
<?php
		$i++;
	}
}
?>
</div>
<table>
	<tr>
		<td>
			<input name="btn" onClick='addVarField(); return false;' type="button" id="btnS" value="<?=_("Добавить переменную");?>">
		</td>
		<td>
			<input name="btnS" type="submit" id="btnS" value="<?=_("Сохранить");?>">
		</td>
	</tr>
</table>
</form>
<div id='attr_template' style="display:none;">
<fieldset border='1' style='border:1px solid red;margin-top:10px;' id='new_var'>
<legend>Переменная:<input type='text' value="" class='var_name' name='attr_name[]'></legend>
<table  border=0 cellpadding=0 cellspacing=4 width='100%'>
	<tr>
		<td width="30%"><?=_("Тип переменной");?></td>
		<td>
<?php
		$resArray = array();
		$selected = array();
		$typeArray = array(
			'int' => _("Целое"),
			'string' => _("Строка"),
			'float' => _("Дробное")
		);
		foreach($typeArray as $t_id => $type){
			$resArray[$t_id] = $type;
		}
		$dom_name = "var_type";
		html_select($resArray, $selected, $dom_name);
?>
<script type='text/javascript'>
$("#<?=$dom_name?>").addClass("var_type");
</script>
		</td>
	</tr>
	<tr>
		<td width="30%"><?=_("Метод");?></td>
		<td>
<?php
		$resArray = array();
		$selected = array();
		$typeArray = array(
			'GET' => "GET",
			'POST' => "POST",
			'COOKIE' => "COOKIE",
			'FILE' => "FILE"
		);
		foreach($typeArray as $t_id => $type){
			$resArray[$t_id] = $type;
		}
		$dom_name = "var_method";
		html_select($resArray, $selected, $dom_name);
?>
<script type='text/javascript'>
$("#<?=$dom_name?>").addClass("var_method");
</script>
		</td>
	</tr>
	<tr>
		<td width="30%"><?=_("Значение по-умолчанию");?></td>
		<td>
			<input type='text' value="" class='default_value' name='default_value[]' size='80'>
		</td>
	</tr>
	<tr>
		<td width="30%">&nbsp;</td>
		<td>
			<input type='checkbox' value="1" class='del_attr' name='del_attr[]'><?=_("Удалить переменную")?>
		</td>
	</tr>
</table>
</fieldset>
</div>