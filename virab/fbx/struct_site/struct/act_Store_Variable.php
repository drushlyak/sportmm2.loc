<?php

$id = intval($attributes['id']);
$FORM_ERROR = "";
// Получаем результирующие массивы параметров переменных
$varSet = array();
$names = $attributes['attr_name'];
$types = $attributes['var_type'];
$methods = $attributes['var_method'];
$defaults = $attributes['default_value'];
$dels = $attributes['del_attr'];
$attr_id = $attributes['attr_id'];
// Перебираем массив
foreach($names as $i => $name){
	if((mb_strlen($name, "UTF-8") == 0) && !is_array($dels[$i])){
		$FORM_ERROR .= _("Необходимо указать название для всех переменных");
		continue;
	}
	/*if((mb_strlen($defaults[$i], "UTF-8") == 0) && (!is_array($dels[$i])) && ($methods[$i] != "FILE")){
		$FORM_ERROR .= _("Необходимо указать значение по умолчанию для поля<br/>");
		continue;
	}*/
	$temp = array();
	$temp['name'] = strip_tags(trim($name));
	$temp['method'] = strip_tags(trim($methods[$i]));
	if($temp['method'] == "FILE"){
			$temp['default_value'] = '';
			$temp['type'] = '';
	}else{
		$temp['default_value'] = strip_tags(trim($defaults[$i]));
		$temp['type'] = strip_tags(trim($types[$i]));
	}
	$temp['id'] = (intval($attr_id[$i]))?intval($attr_id[$i]):0;
	$temp['delete'] = (is_array($dels[$i]))?1:0;
	$varSet[] =   $temp;
}
if(!$FORM_ERROR){
	foreach($varSet as $var){
		if($var['delete']){
			if($var['id']){
				$sql = "
					DELETE
					FROM ".CFG_DBTBL_ATTR."
					WHERE id = ?
				";
				$db->query($sql, $var['id']);
			}
			continue;
		}
		if($var['id']){
			$sql = sql_placeholder("
				UPDATE ".CFG_DBTBL_ATTR."
				SET
					name = ?,
					default_value = ?,
					method = ?,
					var_type = ?
				WHERE id = ?",
				$var['name'],
				$var['default_value'],
				$var['method'],
				$var['type'],
				$var['id']
			);
		}else{
			$sql = sql_placeholder("
				INSERT INTO ".CFG_DBTBL_ATTR."
				SET
					name = ?,
					default_value = ?,
					method = ?,
					var_type = ?,
					page_id = ?",
				$var['name'],
				$var['default_value'],
				$var['method'],
				$var['type'],
				$id
			);
		}
		$db->query($sql);
	}
	Location($_XFA['main'], 0);
}else{
	include("qry_Variable.php");
	include("dsp_Variable.php");
}

?>