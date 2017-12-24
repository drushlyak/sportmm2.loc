<?php

$id = intval($attributes['id']);
$parentId = intval($attributes['parent_id']);
$FORM_ERROR = "";
eval("\$defl_title = trim(\$attributes['title'][".$lng->deflt_lng."]);");
if(!$defl_title){
	$FORM_ERROR = _("Необходимо указать название раздела меню для языка по-умолчанию");
}
if(!$FORM_ERROR){
	$title = $lng->Settextlng($attributes['title']);
	$quick_help = $lng->SetTextlng($attributes['quick_help']);
	if($id){
		$sTree->updateNode($id, array(
			'title'      => $title,
			'url'        => $attributes['url'],
			'quick_help' => $quick_help,
			'menu'       => $attributes['menu'],
			'edt'        => $attributes['edt'])
		);
	}else{
		$id = $sTree->appendChild($parentId, array(
			'title'      => $title,
			'url'        => $attributes['url'],
			'quick_help' => $quick_help,
			'menu'       => $attributes['menu'],
			'edt'        => $attributes['edt']), 0
		);
	}
	Location($_XFA['main'], 0);
}else{
	include ("qry_Form.php");
	include ("dsp_Form.php");
}

?>