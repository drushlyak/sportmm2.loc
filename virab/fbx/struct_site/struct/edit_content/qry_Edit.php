<?php
$id = ($attributes['id']) ? intval($attributes['id']) : $id;
$id1 = ($attributes['id1']) ? intval($attributes['id1']) : $id1;
$type_executor = (intval($attributes['typ']))?intval($attributes['typ']):TE_EXECUTOR_SIMPLE;
// Проверка доступа
$node = $nsTree->getNode($id1, array('res_id'));
if(!$auth_in->aclCheck($node['res_id'], EDIT)){
	$ACL_ERROR = _("У вас нет прав на редактирование");
	return;
}

$parent = &$nsTree->select($id1, array('name'), NSTREE_AXIS_SELF);
$exect = &$cntTree->select($id, array('name'), NSTREE_AXIS_SELF);
$tbl = ($type_executor == TE_EXECUTOR_SCREEN_WYSIWYG)?CFG_DBTBL_TE_EXECWCODE:CFG_DBTBL_TE_EXECSCODE;
$sql = sql_placeholder("
	SELECT text
	FROM ".$tbl."
	WHERE id_executor=?
		AND id_map=?
	ORDER BY page",
	$id, $id1
);
$nodeSet = $db->get_all($sql);
$count = $db->query_total($sql);
if(!$count){
	$text_id = $lng->NewId();
	$sql = "
		INSERT
		INTO ".$tbl."
		SET
			id_executor=?,
			id_map=?,
			page=1,
			text=?
	";
	$db->query($sql, $id, $id1, $text_id);
}

?>