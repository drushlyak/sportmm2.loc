<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" language="JavaScript">
  var elem_array = Array();
  elem_array[1] = 'd1';
  elem_array[2] = 'd2';
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
<form name="form1" method="post" action="<?=$_XFA['store']?>">
<center><br><b><?=_("Перевод:")?>&nbsp;&nbsp;<span id="Translater"></span></b><br><br></center>
<style>
.allow {
	font-weight:bold;
	font-style:italic;
	color:green;
}
.deny {
	font-weight:bold;
	font-style:italic;
	color:red;
}
</style>

<script language="javascript">
<!--
<?php

if($category['config_name']){
	echo "setGradusnik('&nbsp;\"{$category['config_name']}\"');";
}else{
	echo "setGradusnik('', '"._("Редактирование прав")."');";
}

?>
  chngLng(lng_now);
//-->
</script>
<script type='text/javascript'>
function disableCheck(elCount)
{
	var el;
	for(var i = 0; el = document.getElementById('priv' + i); i++) {
		el.disabled = true;
	}
	for (var i = 0; i < elCount; i++) {
		var el = document.getElementById('privAll' + i);
		el.disabled = false;
	}
}

function disableCheckAll(elCount)
{
	var el;
	for(var i = 0; el = document.getElementById('priv' + i); i++) {
		el.disabled = false;
	}
	for (var i = 0; i < elCount; i++) {
		var el = document.getElementById('privAll' + i);
		el.disabled = true;
	}
}
</script>
<input type='hidden' name='role_id' value="<?=$role_id?>">
<input type='hidden' name='data_id' value="<?=$data_id?>">
<input type='hidden' name='module_id' value="<?=$module_id?>">
<input type='hidden' name='acl' value='1'>
<table border=0>
<tr>
<td><?=_("Роль:")?></td>
<td>

<?php
$resArray = array();
$selected = array();
if (is_array($roleSet)) {
	foreach($roleSet as $node){
// Если нет имени узла
			$name = $lng->Gettextlng($node['name']);
        	$resArray[$node['id']] = $name;
        	if($node['id'] == $role_id){
        		$selected = $node['id'];
        	}
	}
}
html_select($resArray, $selected, 'role_id');

?>
</td>
</tr>
<!--
<tr><td colspan=2>
			<fieldset>
			<legend>
				<input type='radio' id="fieldRadio" value=1 name="fieldRadio" onClick='disableCheck(3);'><?=_("Все привилегии")?>
			</legend>
				<table border=0>
				<tr><td><input type='radio' id="privAll1" value=1 name="privAll"><?=_("Разрешить")?></td>
				<td><input type='radio' id="privAll0" value=0 name="privAll" ><?=_("Запретить")?></td>
				<td><input type='radio' id="privAll2" value=2 name="privAll" ><?=_("Наследовать")?></td>
				</tr>
				</table>
			</fieldset>
</td></tr>
 -->
<tr><td colspan=2>
	<fieldset>
	<legend>
			<input type='radio' id="fieldRadio" value=2 name="fieldRadio" onClick='disableCheckAll(3);'><?=_("Привилегии")?>
	</legend>
	<table border=0>

<?php
		$i = 0;
		foreach ($privSet as $privilege):
	?>
				<tr>
				<td>
				<?=$lng->Gettextlng($privilege['name'])?>
				</td>
				<td><?=$auth_in->acl->isAllowed($role_id, $data_id, $privilege['id']) ? '<span class="allow">Разрешено</span>' : '<span class="deny">Запрещено</span>'; ?></td>
				<?php
				$allow_isset = $auth_in->acl->hasOwnRule(MilKit_Acl::TYPE_ALLOW, $data_id, $role_id, $privilege['id']);
				$deny_isset = $auth_in->acl->hasOwnRule(MilKit_Acl::TYPE_DENY, $data_id, $role_id, $privilege['id']);
				?>
				<td><input type='radio' <?= $allow_isset ? "checked" : "" ?> value=1 id="priv<?=$i++?>" name="priv[<?=$privilege['id']?>]"><?=_("Разрешить")?></td>
				<td><input type='radio' <?= $deny_isset ? "checked" : "" ?> value=0 id="priv<?=$i++?>" name="priv[<?=$privilege['id']?>]"><?=_("Запретить")?></td>
				<td><input type='radio' <?= !($allow_isset || $deny_isset) ? "checked" : "" ?> id="priv<?=$i++?>" value=2 name="priv[<?=$privilege['id'] ?>]"><?=_("Наследовать")?></td>
				</tr>
<?php
	endforeach;
?>

    </table>
    </fieldset>
</td></tr>
<tr><td>
     <input name="btnS" type="submit" id="btnS" value="<?=_("Сохранить");?>">
</td></tr>
</table>
</form>