<?
// Проверка доступа
if(!$auth_in->aclCheck($resourceId, DELETE)){
	$ACL_ERROR .= _(" У вас нет прав на удаление");
	Location(sprintf($_XFA['main'], $pg, $count_pg, ((strlen(trim($ACL_ERROR)) > 350) ? substr($ACL_ERROR, 0, 350)."..." : trim($ACL_ERROR))), 0);
	die;
}
$id = intval($attributes['id']);
$did = $attributes['did'];
$FORM_ERROR = "";
//-------------------------------------------------------
function delElement($id)
{
	global $db, $lng, $FORM_ERROR;
	if($id){
		$sql = sql_placeholder("
			SELECT *
			FROM ".CFG_DBTBL_TE_VALUE."
			WHERE id=?",
			$id
		);
		$node = $db->get_row($sql);
		if($node){
			$lng->Deltext($node['description']);
			$sql = sql_placeholder("
				DELETE
				FROM ".CFG_DBTBL_TE_VALUE."
				WHERE id=?", $id
			);
			$db->query($sql);
		}else{
			$FORM_ERROR .= _("Отсутствует запись шаблонной переменной для id=") .$id;
		}
	}else{
		$FORM_ERROR .= _("Не определен id записи");
	}
	return true;
}
//---------------------------------------------------
if(is_array($did)){
	foreach($did as $delel){
		delElement($delel);
	}
}elseif($id){
	delElement($id);
}
Location(sprintf($_XFA['main'], $pg, $count_pg, ((strlen(trim($FORM_ERROR)) > 350) ? substr($FORM_ERROR, 0, 350)."..." : trim($FORM_ERROR))), 0);

?>