<?php
$id1 = intval($attributes['id1']);
// Проверка доступа
$parent = $cntTree->getNode($id1, array('name','res_id', 'type_executor'));
if(!$auth_in->aclCheck($parent['res_id'], VIEW)){
	$ACL_ERROR = _("У вас нет прав на просмотр");
	return;
}
// WYSIWYG коды для данного исполнителя
if($parent['type_executor'] == TE_EXECUTOR_SCREEN_WYSIWYG){
	$tbl = CFG_DBTBL_TE_EXECWCODE;
}else{
	$tbl = CFG_DBTBL_TE_EXECSCODE;
}
$count_pg = ($attributes['count_pg']) ? $attributes['count_pg'] : 20;
$pg = ($attributes['pg']) ? $attributes['pg']-1 : 0;
$record_now = $pg * $count_pg;
$sql = "
	SELECT *
	FROM ".$tbl."
	WHERE id_executor = ?
	ORDER BY id_map, page
";
$execSet = $db->get_all($sql, $id1);
$nodeSet = array();
if(is_array($execSet)){
	$current_map = 0;
	$count_page = 0;
	foreach($execSet as $exec){
		if($current_map != $exec['id_map']){
			$page = $nsTree->getNode($exec['id_map'], array('name'));
			$exec['page'] = $page['name'];
			$nodeSet[] = $exec;
			$current_map = $exec['id_map'];
		}
	}
}
if(is_array($nodeSet)){
	$count_records = count($nodeSet);
}else{
	$count_records = 0;
}
$pageSet = array();
if(is_array($nodeSet)){
	for($i = $record_now; $i <= $record_now + $count_pg; $i++){
		if(!is_array($nodeSet[$i])){
			break;
		}
		$pageSet[] = $nodeSet[$i];
	}
}
?>
