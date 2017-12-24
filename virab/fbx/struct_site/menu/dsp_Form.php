<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" language="JavaScript">
  var elem_array = Array();
  elem_array[1] = 'd1';
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
	echo "setGradusnik('&nbsp;\"".htmlspecialchars($lng->Gettextlng($name),ENT_QUOTES)."\"');";
}else{
	echo "setGradusnik('&nbsp;" . _("дочернего&nbsp;к") . "&nbsp;\"".htmlspecialchars($lng->Gettextlng($parent[0]['name']),ENT_QUOTES)."\"', '"._("Добавление раздела меню")."');";
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
		<td width="20%"><?=_("Переменная");?></td>
<?php

if($category['id_te_value']){
	echo "<td><b>".htmlspecialchars(getTeValueName($category['id_te_value']),ENT_QUOTES)."</b><input name=\"value\" id=\"value\" type=\"hidden\" value=\"".htmlspecialchars(getTeValueName($category['id_te_value']),ENT_QUOTES)."\"></td>";
}else{
	echo "<td><input name=\"value\" id=\"value\" value=\"menu".substr($lng->NewId(), 0, 5)."\"></td>";
}

?>
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
		<td width="20%"><?=_("Узел");?></td>
		<td>
<?php

$resArray = array();
$selected = array();
foreach($nodeSet as $node){
	if($node['left'] == 1){
		continue;
	}
// Если нет имени узла
	if(!$lng->Gettextlng($node['name'])){
		$name = _("Безымянный");
	}else{
		$name = $lng->Gettextlng($node['name']);
	}
	$resArray[$node['id']] = $name;
	if($category['id_node'] == $node['id']){
		$selected[] = $node['id'];
	}
}
html_select($resArray, $selected, 'id_node');

?>
		</td>
	</tr>
	<tr height=30>
		<td valign="middle"><?=_("Доступные языки");?></td>
		<td>
			<span style="color: #000000;">
<?php

$i = 0;
foreach($lng->lng_array as $dlng){
	echo $dlng['ind_name']."&nbsp;&nbsp;<input name=\"lng".$dlng['id']."\" type=\"checkbox\" id=\"lng".$dlng['id']."\"".((is_array($lngSet) && in_array($dlng['id'], $lngSet)) ? " checked" : "")."><br>";
	$i++;
}

?>
			</span>
		</td>
	</tr>
	<tr>
		<td valign="top"><?=_("Шаблон");?></td>
		<td>
<?php

$resArray = array();
$selected = array();
if(is_array($conteinerSet)){
	foreach($conteinerSet as $conteiner){
		if($conteiner['type_template'] != 7){
			continue;
		}
// Если нет имени узла
		if(!$lng->Gettextlng($conteiner['name'])){
			$name = _("Безымянный");
		}else{
			$name = $lng->Gettextlng($conteiner['name']);
		}
        $resArray[$conteiner['id']] = $name;
        if($category['template_id'] == $conteiner['id']){
        	$selected[] = $conteiner['id'];
        }
	}
}
html_select($resArray, $selected, 'template_id');

?>
		</td>
	</tr>
	<tr>
		<td valign="top"><?=_("Url адрес");?></td>
		<td>
			<input name="url" size="50" type="text" id="url" value="<?=htmlspecialchars($category['url'],ENT_QUOTES)?>">
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