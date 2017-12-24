<?php

$id1 = intval($attributes['id1']);
$id2 = intval($attributes['id2']);
// Проверка доступа
$parent_res = $nsTree->getNode($id1, array('res_id'));
if(!$auth_in->aclCheck($parent_res['res_id'], VIEW)){
	$ACL_ERROR = _("У вас нет прав на просмотр");
	return;
}
$parent = &$nsTree->select($id1, array('name'), NSTREE_AXIS_SELF);
$rsExect = &$cntTree->select($id2, array('name'), NSTREE_AXIS_SELF);
$exect = $rsExect[0];

$count_pg = ($attributes['count_pg']) ? $attributes['count_pg'] : 20;
$pg = ($attributes['pg']) ? $attributes['pg']-1 : 0;
$record_now = $pg * $count_pg;

$sql = "
	SELECT id, page, text
	FROM ".CFG_DBTBL_TE_EXECWCODE."
	WHERE id_executor=$id2 and id_map=$id1
	ORDER BY page LIMIT $record_now, $count_pg
";
$count_records = $db->query_total($sql);
$pageSet = $db->get_all($sql);

?>