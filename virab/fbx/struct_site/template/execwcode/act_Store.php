<?php

$id = intval($attributes['id']);
$id1 = intval($attributes['id1']);
$type_executor = intval($attributes['type_executor']);
$id_map = intval($attributes['id_map']);
eval("\$defl_text = trim(\$attributes['text'][".$lng->deflt_lng."]);");
$tbl = ($type_executor == TE_EXECUTOR_SCREEN_WYSIWYG) ? CFG_DBTBL_TE_EXECWCODE : CFG_DBTBL_TE_EXECSCODE;
if(!$defl_text){
	$FORM_ERROR = _("Необходимо указать содержимое страницы для языка по-умолчанию");
}
if(!$id_map){
	$FORM_ERROR = _("Необходимо указать страницу");
}
if(!$FORM_ERROR){
	$max_page_num = 0;
    foreach ($lng->lng_array as $dlng) {
    	eval("\$text = trim(\$attributes['text'][".$dlng['id']."]);");
// Переберем в тексте все {pagebreak} - разделив их по странично
		$fragment = explode("{pagebreak}", $text);
		$page_num = 0;
		foreach($fragment as $page){
			$page_num++;
        	$sql = sql_placeholder("
        		SELECT text
        		FROM ".$tbl."
        		WHERE id_executor=?
        			AND id_map=?
        			AND page=?",
        		$id, $id_map, $page_num
        	);
			$node = $db->get_row($sql);
        	if(is_array($node)){
            	if(!$node['text']){
            		$node['text'] = $lng->NewId();
            	}
            	$lng->Settext($node['text'], $page, $dlng['id']);
            	$sql = "
            		UPDATE ".$tbl."
            		SET
            			text=?
            		WHERE
            			id_executor=?
            			AND id_map=?
            			AND page=?
            	";
            	$db->query($sql, $node['text'], $id, $id_map, $page_num);
         	}else{
         		$page_id = $lng->NewId();
         		$lng->Settext($page_id, $page, $dlng['id']);
            	$sql = "
            		INSERT
            		INTO ".$tbl."
            		SET
            			text=?,
            			id_executor=?,
            			id_map=?,
            			page=?
            	";
         		$db->query($sql, $page_id, $id, $id_map, $page_num);
         	}
		}
// Если в тексте для этого языка окончилось содержимое то очищаем все остальное
		if($page_num > $max_page_num){
			$max_page_num = $page_num;
// Удалим все страницы которых в сохраняемом тексте нет ({pagebrek} - все перебрали а в базе от старой
// версии страницы еще остались при это в других языках до того кол-ва страниц не до шли
			$sql = "
				DELETE
				FROM ".$tbl."
				WHERE id_executor=?
					AND id_map=?
					AND page > ?
			";
			$db->query($sql, $id, $id_map, $page_num);
		}else{
// Очистим сожержимое языковых переменных для всех страниц которые в данном языке не определенны, но для
// предыдущих языков уже занесены
			while($page_num < $max_page_num){
				$sql = "
					SELECT *
					FROM ".$tbl."
					WHERE
						id_executor=?
						AND id_map=?
						AND page=?
				";
				$node = $db->get_row($sql, $id, $id_map, $page_num);
				if(is_array($node)){
					$lng->Settext($node['text'], "", $dlng['id']);
				}
                $page_num++;
			}
		}
     }
     Location(sprintf($_XFA['mainwcode'], $id1), 0);
}else{
	include ("execwcode/qry_Form.php");
	include ("execwcode/dsp_Form.php");
}
?>