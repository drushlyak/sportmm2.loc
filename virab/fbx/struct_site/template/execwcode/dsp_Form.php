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
<form name="editform" method="post" action="<?=$_XFA['storewcode']?>">
<center><br><b><?=_("Перевод:")?>&nbsp;&nbsp;<span id="Translater"></span></b><br><br></center>
<script language="javascript">
<!--
<?php
  if ($node['name'])
      echo "setGradusnik('&nbsp;\"".$lng->Gettextlng($node['name'])."\" "._("для")." \"".$lng->Gettextlng($node['name'])."\"');";
?>
  chngLng(lng_now);
//-->
</script>
<input type="hidden" name="id1" value="<?=$id1?>">
<input type="hidden" name="id" value="<?=$id1?>">
<input type="hidden" name="typ" value="<?=$typ?>">
<input type="hidden" name='type_executor' value="<?=$type_executor?>">
<input type='hidden' name='acl' value='1'>
  <table width="100%"  border="0" cellpadding="0" cellspacing="4">
    <tr>
      <td><?=_("Содержимое");?>:</td>
      <td>
<?php
$executor_text = "";
$page_count = count($pageSet);
$i=0;
if($pageSet){
	foreach($pageSet as $node){
		$i++;
		if($node['text']){
			$text = $lng->getTextlngall($node['text']);
			foreach($text as $num => $tmpText){
				if ($num == 'msgid') {
					continue;
				}
				if (strlen($tmpText)) {
					$executor['text'][$num] .= $tmpText;
					if ($i < $page_count) {
						$executor['text'][$num] .= "{pagebreak}";
					}
				}
			}
		}
		$executor['text']['msgid'] = -1;
	}
}
if($type_executor == TE_EXECUTOR_WYSIWYG){
	$lng->richEdit(1, 'text', $executor['text']);
}elseif($type_executor == TE_EXECUTOR_SIMPLE){

	$lng->textArea(1, 'text', $executor['text'], array('cols' => "100%", 'rows' => 8));
}
?>
      </td>
    </tr>
    <tr>
		<td valign="top" width='20%'><?=_("Страница");?></td>

		<td>
<?php
	if($typ == 1){
		$resArray = array();
		$selected = array();
		if(is_array($nsSet)){
			foreach($nsSet as $ns){
// Если нет имени узла
				$name = $lng->Gettextlng($ns['name']);
        		$resArray[$ns['id']] = $name;
        		if($page == $ns['id']){
        			$selected[] = $ns['id'];
        		}
			}
		}
		html_select($resArray, $selected, 'id_map');
	}else{
		if(is_array($nsSet)){
			foreach($nsSet as $ns){
				if($ns['id'] == $page){
					$name = $lng->Gettextlng($ns['name']);
					break;
				}
			}
print <<<EOF
	{$name}
	<input type="hidden" name="id_map" value="{$page}">
EOF;

		}
	}
?>
		</td>
	</tr>
    <tr>
      <td><input name="btnS" type="submit" id="btnS" value="<?=_("Сохранить");?>"></td>
    </tr>
  </table>
</form>