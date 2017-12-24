<?php

$id = intval($attributes['idp']);
$id1 = intval($attributes['id1']);
$id2 = intval($attributes['id2']);
$typ = intval($attributes['typ']);
eval("\$defl_text = trim(\$attributes['text'][".$lng->deflt_lng."]);");
if(!$defl_text){
	//$FORM_ERROR = _("Введите текст страницы для языка по умолчанию");
}
if(!$FORM_ERROR){
	$text = $lng->SetTextlng($attributes['text']);
	if($typ == 2){
		$rsNodes = $db->query("UPDATE ".CFG_DBTBL_TE_EXECWCODE." SET text='$text' WHERE id=".$id);
	}else{
		$rsPages = $db->query("SELECT max(page) as pg FROM ".CFG_DBTBL_TE_EXECWCODE." WHERE id_executor=$id2 AND id_map=$id1");
      	$page = $rsPages->fetch_assoc();
      	$pg = $page['pg']+1;
      	$rsPages = $db->query("INSERT INTO ".CFG_DBTBL_TE_EXECWCODE." SET text='$text', id_executor=$id2, id_map=$id1, page=$pg");
	}
	Location(sprintf($_XFA['mainbig'], $id1, $id2), 0);
}else{
	include ("edit_content/bigpage/qry_Form.php");
    include ("edit_content/bigpage/dsp_Form.php");
}

?>