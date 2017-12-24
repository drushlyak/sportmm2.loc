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
<form name="editform" method="post" action="<?=$_XFA['storeexeccont']?>">
<center><br><b><?=_("Перевод:")?>&nbsp;&nbsp;<span id="Translater"></span></b><br><br></center>
<script language="javascript">
<!--
<?php
  if ($exect['name'] || $parent[0]['name'])
      echo "setGradusnik('&nbsp;\"".$lng->Gettextlng($exect[0]['name'])."\" "._("для")." \"".$lng->Gettextlng($parent[0]['name'])."\"');";
?>
  chngLng(lng_now);
//-->
</script>
<input type="hidden" name=id1 value="<?=$attributes['id1']?>">
<input type="hidden" name=typ value="<?=$typ?>">
<input type="hidden" name='type_executor' value="<?=$type_executor?>">
<input type="hidden" name=id value="<?=(isset($id)) ? $id : $attributes['id']?>">
<input type="hidden" name=speed_page value="<?=(isset($spp)) ? $spp : 0?>">
<input type='hidden' name='acl' value='1'>
  <table width="100%"  border="0" cellpadding="0" cellspacing="4">
    <tr>
      <td><?=_("Содержимое")?>:</td>
    </tr>
    <tr>
      <td>
<?php
$executor_text = "";
$page_count = count($nodeSet);
$i=0;
if($nodeSet){
	foreach($nodeSet as $node){
		$i++;
		if($node['text']){
			$text = $lng->getTextlngall($node['text']);
			if ($text) {
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
		}
		$executor['text']['msgid'] = -1;
	}
	if($type_executor == TE_EXECUTOR_WYSIWYG){
		$lng->richEdit(1, 'text', $executor['text']);
	}elseif($type_executor == TE_EXECUTOR_SIMPLE){

		$lng->textArea(1, 'text', $executor['text'], array('cols' => "100%", 'rows' => 8));
	}
}
?>
      </td>
    </tr>
    <tr>
      <td><input name="btnS" type="submit" id="btnS" value="<?=_("Сохранить")?>"></td>
    </tr>
  </table>
</form>