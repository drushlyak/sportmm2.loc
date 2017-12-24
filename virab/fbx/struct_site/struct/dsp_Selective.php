<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" language="JavaScript">
  var elem_array = Array();
  elem_array[1] = 'd1';
<?php include "../../../js/language.js.php"; ?>
</script>
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
<form name="editform" method="post" action="<?=$_XFA['selective_store']?>">
<center><br><b><?=_("Перевод:")?>&nbsp;&nbsp;<span id="Translater"></span></b><br><br></center>
<script language="javascript">
<!--
<?php
  if($parent[0]['name']){
  	echo "setGradusnik('&nbsp;');";
  }
?>
  chngLng(lng_now);
//-->
</script>
<input type="hidden" name=id value="<?=$id?>">
<input type='hidden' name='acl' value='1'>
<table width="100%"  border="0" cellpadding="0" cellspacing="4">
<?php
	if(is_array($nodeSet)){
		foreach($nodeSet as $id => $node){
$text =<<<EOF
	<tr>
		<td width='20%'>{$lng->Gettextlng($node['name'])}</td>
		<td>
EOF;
print $text;
?>
   	<td>
<?php

			$resArray = array();
			$selected = array();
			foreach($contSet as $cnt){
			// Если нет имени шаблона
				if(!$lng->Gettextlng($cnt['name'])){
					$name = _("Безымянный");
				}else{
					$name = $lng->Gettextlng($cnt['name']);
				}
				$resArray[$cnt['id']] = $name;
				if($node['contaner_id'] == $cnt['id']){
					$selected[] = $cnt['id'];
				}
			}
			$dom_name = 'contaner_id['.$id.']';
			html_select($resArray, $selected, $dom_name);
$text =<<<EOF
		</td>
	</tr>
EOF;
print $text;
		}
	}
?>
	<tr>
      <td><input name="btnS" type="submit" id="btnS" value="<?=_("Сохранить");?>"></td>
    </tr>
  </table>
</form>