<?php
$id = intval($attributes['id']);
// Проверка доступа
if($id){
	$node = $nsTree->getNode($id, array('res_id'));
	if(!is_array($node)){
		Location($_XFA['main'], 0);
		return;
	}
	if(!$auth_in->aclCheck($node['res_id'], EDIT)){
		$ACL_ERROR = _("У вас нет прав на редактирование");
		return;
	}
	// Выбираем все переменные для данной странцы
	$sql = "
		SELECT *
		FROM ".CFG_DBTBL_ATTR."
		WHERE page_id = ?
	";
	$attrSet = $db->get_all($sql, $id);
}

?>