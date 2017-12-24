<?php
// Проверка доступа
if(!$attributes['acl']){
	print "Ты как сюда попал?";
	return;
}
$id       = intval($attributes['id']);
$parentId = intval($attributes['parent_id']);
$FORM_ERROR = "";
eval("\$defl_name = trim(\$attributes['name'][".$lng->deflt_lng."]);");
if(!$defl_name){
	$FORM_ERROR .= _("Необходимо указать название раздела меню для языка по-умолчанию");
}
if(!$FORM_ERROR){
	$name = $lng->SetTextlng($attributes['name']);
	if($id){
		$mnTree->updateNode($id, array(
			'name'        => $name,
			'id_te_value' => getTeValueId($attributes['value']),
			'id_node'     => $attributes['id_node'],
			'url'         => $attributes['url'],
			'template_id' => $attributes['template_id']
		), 0);
	}elseif($parentId){
		$node = $mnTree->getNode($parentId, array('res_id'));
		$configTable = $auth_in->store->getConfig();
		$res_id = $auth_in->store->newResourceId();
		$rsInsValue = $db->query(sql_placeholder("INSERT INTO ".CFG_DBTBL_TE_VALUE." SET name=?, typ=7, sys=1", $attributes['value']));
		$id = $mnTree->appendChild($parentId, array(
			'name'        => $name,
			'id_te_value' => getTeValueId($attributes['value']),
			'id_node'     => $attributes['id_node'],
			'url'         => $attributes['url'],
			'template_id' => $attributes['template_id'],
			'res_id'	  => $res_id
		), 0);
		if($node['res_id']){
			$sql = sql_placeholder("
				SELECT id
				FROM ".$resTree->structTable."
				WHERE data_id=?",
				$node['res_id']
			);
			$parent_id = $db->get_one($sql);
			$new_id = $resTree->appendChild($parent_id, array(), $res_id);
		} else {
			$sql = sql_placeholder("
				SELECT top_id
				FROM ".CFG_DBTBL_MODULE."
				WHERE var='menu'"
			);
			$top_id = $db->get_one($sql);
			$new_id = $resTree->appendChild($top_id, array(), $res_id);
		}
	}
// Зафиксируем признак активности для конкретного языка
	foreach($lng->lng_array as $dlng){
		eval("\$tt = (\$lng".$dlng['id']." == 'on') ? 1 : 0;");
		$upLngPage = $db->query(sql_placeholder("SELECT id FROM ".CFG_DBTBL_PAGELNG." WHERE page_id=? AND language_id=? AND page_menu=?", $id, $dlng['id'], ACC_LNG_MENU));
		if(!$upLngPage->num_rows && $tt){
			$upLngPage = $db->query(sql_placeholder("INSERT INTO ".CFG_DBTBL_PAGELNG." SET page_id=?, language_id=?, page_menu=?", $id, $dlng['id'], ACC_LNG_MENU));
		}
		if($upLngPage->num_rows && !$tt){
			$upLngPage = $db->query(sql_placeholder("DELETE FROM ".CFG_DBTBL_PAGELNG." WHERE page_id=? AND language_id=? AND page_menu=?", $id, $dlng['id'], ACC_LNG_MENU));
		}
	}
	Location($_XFA['main'], 0);
}else{
	include ("qry_Form.php");
	include ("dsp_Form.php");
}
?>