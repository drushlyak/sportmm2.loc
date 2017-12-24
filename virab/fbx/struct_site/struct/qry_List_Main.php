<?php
// Проверка доступа
if(!$auth_in->aclCheck($resourceId, VIEW)){
	$ACL_ERROR = _("У вас нет прав на просмотр");
	return;
}
$nodeSet_temp = &$nsTree->selectNodes(0, 0, array('name', 'chpu', 'id_contaner', 'enable', 'printable', 'wile'));
$parent_id = $nodeSet_temp[0]['id'];
$count_records = count($nodeSet_temp);
$count_pg = ($attributes['count_pg']) ? $attributes['count_pg'] : 20;
$pg = ($attributes['pg']) ? $attributes['pg']-1 : 0;
$record_now = $pg * $count_pg;
$nodeSet = array();
for($i = $record_now+1; $i <= $record_now+$count_pg+1 && $i < $count_records; $i++){
	$nodeSet[] = $nodeSet_temp[$i];
}
?>