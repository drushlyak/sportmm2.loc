<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" language="JavaScript">
  var elem_array = Array();
  elem_array[1] = 'd1';
  elem_array[2] = 'd2';
  elem_array[3] = 'd3';
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
// Если уровень выше первого значит узел, а не домен
$domen_priz = ($info['level'] > 1 || $parent_info['level'] > 0) ? 0 : 1;

?>
<form name="form1" method="post" action="<?=$_XFA['store']?>">
<center><br><b><?=_("Перевод:")?>&nbsp;&nbsp;<span id="Translater"></span></b><br><br></center>
<script language="javascript">
<!--
<?php

if($typ == 2){
	echo "setGradusnik('&nbsp;\"".htmlspecialchars($lng->Gettextlng($name),ENT_QUOTES)."\"');";
}else{
	if ($parent[0]['name']){
		echo "setGradusnik('&nbsp;"._("дочернего к ")."&nbsp;\"".$lng->Gettextlng($parent[0]['name'])."\"', '"._("Добавление раздела,")."');";
	}else{
		echo "setGradusnik('', '"._("Добавление нового корневого узла")."');";
	}
}

?>
chngLng(lng_now);
//-->
</script>
<?=(($category['id']) ? "<input type=\"hidden\" name=\"id\" value=\"".$category['id']."\">" : "")?>
<?=(($parent_id) ? "<input type=\"hidden\" name=\"parent_id\" value=\"".$parent_id."\">" : "")?>
<input type="hidden" name="id_contaner_old" value="<?=$category['id_contaner']?>">
<input type="hidden" name="domen_priz" value="<?=$domen_priz?>">
<input type="hidden" name="typ" value="<?=$typ?>">
<input type='hidden' name='acl' value='1'>
<table width="100%"  border="0" cellpadding="0" cellspacing="4">
	<tr>
		<td width="20%"><?=_("Название");?></td>
		<td>
<?php
			$lng->textField(1, 'name', $category['name'], array('size' => 50));
?>
		</td>
	</tr>
	<tr>
		<td><?=_("Заголовок");?></td>
		<td>
<?php
			$lng->textField(2, 'title', $category['title'], array('size' => 50));
?>
		</td>
	</tr>
    <tr>
    	<td valign="top"><?=_("Описание");?></td>
    	<td>
<?php
			$lng->textArea(3, 'description', $category['description'], array('cols' => "100%", 'rows' => 8));
?>
		</td>
	</tr>
	<tr>
		<td valign="top"><?=_("Ключевые слова");?></td>
		<td>
<?php
			$lng->textArea(4, 'keywords', $category['keywords'], array('cols' => "100%", 'rows' => 8));
?>
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
// Если нет имени узла
		if(!$lng->Gettextlng($conteiner['name'])){
			$name = _("Безымянный");
		}else{
			$name = $lng->Gettextlng($conteiner['name']);
		}
        $resArray[$conteiner['id']] = $name;
        if($category['id_contaner'] == $conteiner['id']){
        	$selected[] = $conteiner['id'];
        }
	}
}
html_select($resArray, $selected, 'id_contaner');

?>
		</td>
	</tr>
	<tr height=30>
		<td valign="middle"><?=_("Параметры страницы");?></td>
		<td>
			<span style="color: #000000;">
       		<?=_("Активна:");?>&nbsp;&nbsp;<input name="enable" type="checkbox" id="enable"<?=($category['enable']) ? " checked" : ""?>>
      		</span>
      	</td>
     </tr>
     <tr height=30>
     	<td valign="middle"><?=_("Доступные языки");?></td>
     	<td>
     		<span style="color: #000000;">
<?php
  $i = 0;
  foreach ($lng->lng_array as $dlng) {
      echo $dlng['ind_name']."&nbsp;&nbsp;<input name=\"lng".$dlng['id']."\" type=\"checkbox\" id=\"lng".$dlng['id']."\"".((is_array($lngSet) && in_array($dlng['id'], $lngSet)) ? " checked" : "")."><br>";
      $i++;
  }
?>
      		</span>
      	</td>
     </tr>
     <tr>
     	<td width="20%"><?=_("Отображение печать/экран");?></td>
     	<td>
<?php

$resArray = array();
$selected = array();
$i = 0;
foreach($__TYPE_PRINT as $print_type){
	$i++;
	$resArray[$i] = $print_type;
    if($category['printable'] == $i){
    	$selected[] = $i;
    }
}
html_select($resArray, $selected, 'printable', false)

?>
		</td>
	</tr>
	<tr>
		<td width="20%"><?=_("Поведение при отсутствии контента");?></td>
		<td>
<?php

$resArray = array();
$selected = array();
$i = 0;
foreach($__TYPE_WILE as $wile_type){
	$i++;
	$resArray[$i] = $wile_type;
    if($category['wile'] == $i){
    	$selected[] = $i;
    }
}
html_select($resArray, $selected, 'wile', false)

?>
		</td>
	</tr>
	<tr>
		<td valign="top"><?=_("Кодировка");?></td>
		<td>
<?php

$resArray = array();
$selected = array();

$resArray['0'] = _("Без явного указания");
$resArray['1'] = _("Западноевропейская");
$resArray['2'] = _("Кирилица")."(DOS)";
$resArray['3'] = _("Кирилица")."(ISO)";
$resArray['4'] = _("Кирилица")."(KOI8-U)";
$resArray['5'] = _("Кирилица")."(KOI8-R)";
$resArray['6'] = _("Кирилица")."(WINDOWS)";
$resArray['7'] = _("Центральноевропейская")."(DOS)";
$resArray['8'] =_("Центральноевропейская")."(ISO)";
$resArray['9'] =_("Центральноевропейская")."(WINDOWS)";
$resArray['10'] = _("Юникод")."(windows-1251)";

for($i=0 ; $i<=10; $i++){
	if($category['encoding'] == $i){
		$selected[] = $i;
	}
}
html_select($resArray, $selected, 'encoding', false)

?>
		</td>
	</tr>
	<tr>
		<td valign="top"><?=_("Обработка поисковым роботом");?></td>
		<td>
			<input name="robots" type="text" id="robots" value="<?=htmlspecialchars($category['robots'],ENT_QUOTES)?>">
		</td>
	</tr>
	<tr>
		<td valign="top"><?=_("target");?></td>
		<td>
<?php

$resArray = array();
$selected = array();

$resArray['0'] = "";
$resArray['1'] = "_blank";
$resArray['2'] = "_parent";
$resArray['3'] = "_self";
$resArray['4'] = "_top";

for($i=0 ; $i<5; $i++){
	if($category['target'] == $i){
		$selected[] = $i;
	}
}
html_select($resArray, $selected, 'target', false)

?>
		</td>
	</tr>
	<tr>
		<td valign="top"><?=_("Кэширование");?></td>
		<td>
			<input name="cache" type="text" id="cache" value="<?=htmlspecialchars($category['cache'],ENT_QUOTES)?>">
		</td>
	</tr>
	<tr>
<?php

if($domen_priz){
	echo "<td valign=\"top\">"._("Домены")."</td>";
	echo "<td>http://&nbsp;<input name=\"adrs\" type=\"text\" id=\"adrs\" size=20 value=\"".htmlspecialchars($category['chpu'],ENT_QUOTES)."\">&nbsp;/&nbsp;</td>";
}else{
	echo "<td valign=\"top\">"._("ЧПУ")."</td><td>http://";
	foreach($chpuPath as $chpuP){
		if(($chpuP['level'] == 1) && ($chpuP['chpu'])){
			$temp = strtok($chpuP['chpu'], " ");
			echo $temp."/";
		}elseif($chpuP['chpu']){
			echo $chpuP['chpu']."/";
		}
	}
	echo "&nbsp;<input name=\"chpu\" type=\"text\" id=\"chpu\" size=20 value=\"".htmlspecialchars($category['chpu'],ENT_QUOTES)."\">&nbsp;/</td>";
}

?>
	</tr>
	<tr>
		<td valign="top">&nbsp;</td>
		<td>
			<input name="btnS" type="submit" id="btnS" value="<?=_("Сохранить");?>">
		</td>
	</tr>
</table>

</form>