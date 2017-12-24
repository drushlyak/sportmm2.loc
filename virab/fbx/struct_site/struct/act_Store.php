<?php

$id       = intval($attributes['id']);
$parentId = intval($attributes['parent_id']);
eval("\$defl_name = trim(\$attributes['name'][".$lng->deflt_lng."]);");
$enable    = ($attributes['enable'] == 'on')    ? 1 : 0;
$printable = intval($attributes['printable']);
$id_contaner_old = (intval($attributes['id_contaner_old']))?intval($attributes['id_contaner_old']):0;
$wile      = intval($attributes['wile']);
$chpu      = ($attributes['domen_priz']) ? $attributes['adrs'] : $attributes['chpu'];
$FORM_ERROR = "";

if(!$defl_name){
	$FORM_ERROR .= _("Необходимо указать название страницы для языка по-умолчанию");
}

$temp_chpu = strtok($chpu, " ");
while($temp_chpu){
	if($attributes['domen_priz'] && ! preg_match("/^([0-9a-z._-]+)$/s", $temp_chpu)){
		$FORM_ERROR .= _("Некорректные адреса сайтов. Адрес должен иметь формат [xxx.][xxx.][...]xxxxxx.xxx. Между собой адреса должны разделяться пробелами.");
	}
	$temp_chpu = strtok(" ");
}

if(!$FORM_ERROR){
	$name = $lng->SetTextlng($attributes['name']);
	$title = $lng->SetTextlng($attributes['title']);
	$description = $lng->SetTextlng($attributes['description']);
	$keywords = $lng->SetTextlng($attributes['keywords']);
	if($id){
// Если выбран другой шаблон - то сначала удалим все записи для WYSIWYG полей которые имеются
		if($attributes['id_contaner'] <> $attributes['id_contaner_old']){
			$rsNodes = $db->query("SELECT text FROM ".CFG_DBTBL_TE_EXECWCODE." WHERE id_map=".$id);
			while($node = $rsNodes->fetch_assoc()){
				$lng->Deltext($node['text']);
			}
			$rsNodes = $db->query("DELETE FROM ".CFG_DBTBL_TE_EXECWCODE." WHERE id_map = $id");
		}
		$nsTree->updateNode($id, array(
			'name'        => $name,
			'id_contaner' => $attributes['id_contaner'],
			'title'       => $title,
			'description' => $description,
    		'keywords'    => $keywords,
			'encoding'    => $attributes['encoding'],
			'cache'       => $attributes['cache'],
			'robots'      => $attributes['robots'],
			'target'      => $attributes['target'],
			'chpu'        => $chpu,
			'enable'      => $enable,
			'printable'   => $printable,
			'wile'        => $wile
		));
	}elseif($parentId){
		$node = $nsTree->getNode($parentId, array('res_id'));
		$configTable = $auth_in->store->getConfig();
		$res_id = $auth_in->store->newResourceId();
		$id = $nsTree->appendChild($parentId, array(
			'name'        => $name,
			'id_contaner' => $attributes['id_contaner'],
			'title'       => $title,
			'description' => $description,
    		'keywords'    => $keywords,
			'encoding'    => $attributes['encoding'],
			'cache'       => $attributes['cache'],
			'robots'      => $attributes['robots'],
			'target'      => $attributes['target'],
			'chpu'        => $chpu,
			'enable'      => $enable,
			'printable'   => $printable,
			'wile'        => $wile,
			'res_id'	  => $res_id
		), 0);
		if($node['res_id']){
			$sql = sql_placeholder("SELECT id FROM ".$resTree->structTable." where data_id=?", $node['res_id']);
			$parent_id = $db->get_one($sql);
			$new_id = $resTree->appendChild($parent_id, array(), $res_id);
		} else {
			$sql = sql_placeholder("SELECT top_id FROM ".CFG_DBTBL_MODULE." where var='site_struct'");
			$top_id = $db->get_one($sql);
			$new_id = $resTree->appendChild($top_id, array(), $res_id);
		}
	}
// Зафиксируем признак активности для конкретного языка
	foreach($lng->lng_array as $dlng){
		eval("\$tt = (\$lng".$dlng['id']." == 'on') ? 1 : 0;");
		$upLngPage = $db->query(sql_placeholder("SELECT id FROM ".CFG_DBTBL_PAGELNG." WHERE page_id=? AND language_id=? AND page_menu=?", $id, $dlng['id'], ACC_LNG_PAGE));
		if(!$upLngPage->num_rows && $tt){
			$upLngPage = $db->query(sql_placeholder("INSERT INTO ".CFG_DBTBL_PAGELNG." SET page_id=?, language_id=?, page_menu=?", $id, $dlng['id'], ACC_LNG_PAGE));
		}
		if($upLngPage->num_rows && !$tt){
			$upLngPage = $db->query(sql_placeholder("DELETE FROM ".CFG_DBTBL_PAGELNG." WHERE page_id=? AND language_id=? AND page_menu=?", $id, $dlng['id'], ACC_LNG_PAGE));
		}
	}
	// проверим старый и новый контейнеры. Если они не совпадают.. то очищаем все wysiwyg-и и простые тексты для изменяемой страницы
	if($id_contaner_old != $attributes['id_contaner'] && $id){
	   // Очищаем все wysiwyg-и и простые тексты
	   $tbls = array(CFG_DBTBL_TE_EXECWCODE, CFG_DBTBL_TE_EXECSCODE);
	   foreach($tbls as $tbl){
      	   $sql = sql_placeholder("
      	   	SELECT text
      	   	FROM ".$tbl."
      	   	WHERE id_map = ?", $id
      	   );
      	   $texts = $db->get_all($sql);
      	   if(is_array($texts)){
      	      foreach($texts as $txt){
      	         $lng->Deltext($txt['text']);
      	      }
      	   }
      	   $sql = sql_placeholder("
      	   	DELETE FROM ".$tbl."
      	   	WHERE id_map = ?",
      	      $id
      	   );
      	   $db->query($sql);
	   }
	}
	Location($_XFA['main'], 0);
}else{
	include ("qry_Form.php");
	include ("dsp_Form.php");
}

?>
