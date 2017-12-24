<?php
// Проверка доступа
if(!$auth_in->aclCheck($resourceId, VIEW)){
	$ACL_ERROR = _("У вас нет прав на просмотр");
	return;
}
$nodeSet = $resTree->select(2, array('id'), NSTREE_AXIS_DESCENDANT);
$configTable = $auth_in->store->getConfig();

//-------------------------------------------------------
function selectChildren($id, $level)
{
	global $resNode, $db, $elvl, $elCount, $configTable;
	$sql = sql_placeholder("SELECT role_id FROM {$configTable['roleRefTable']} WHERE parent=?", $id);
	$nodeSet = $db->get_all($sql);
	if($nodeSet){
		foreach($nodeSet as $node){
			$sql = sql_placeholder("SELECT id, name FROM {$configTable['roleTable']} WHERE id=?", $node['role_id']);
			$nodeChild = $db->get_all($sql);
			foreach($nodeChild as $child){
				$elvl[$elCount++] = $level;
				$resNode[] = $child;
				selectChildren($child['id'], $level+1);
			}
		}
	}
}
//-----------------------------------------------------------
$sql = sql_placeholder("
	SELECT t1.id as id, t1.name as name, t2.parent as parent FROM {$configTable['roleTable']} AS t1
		LEFT JOIN {$configTable['roleRefTable']} AS t2
		ON (t1.id = t2.role_id) order by id
");
$roleSet = $db->get_all($sql);

$resNode = array();
$lvl = array();
$elCount = 1;
if($roleSet){
	foreach($roleSet as $node){
		$level = 1;
		if(!$node['parent']){
			$resNode[] = $node;
			$elvl[$elCount++] = $level;
			selectChildren($node['id'], $level+1);
		}
	}
}

$roleSet = array();
$elCount = 1;
foreach($resNode as $node){
	$tmpArray = array();
	$tmpArray['id'] = $node['id'];
	$tmpArray['name'] = $node['name'];
	$tmpArray['level'] = $elvl[$elCount++];
	$sql = sql_placeholder("SELECT id FROM {$configTable['roleRefTable']} WHERE parent=?", $node['id']);
	$tmpArray['has_children'] = ($db->get_one($sql))?"1":"0";
	$roleSet[] = $tmpArray;
}

foreach ($roleSet as $node) {
	$res = $db->query("SELECT id FROM {$configTable['roleRefTable']} WHERE role_id=?", $node['id']);
	$parCount[$node['id']] = $res->num_rows;
}
$tmpSet = $db->get_all("SELECT id, name, top_id, enabled FROM ".CFG_DBTBL_MODULE." WHERE enabled=1");

$moduleSet = array();
if($tmpSet){
	foreach ($tmpSet as $node) {
		 if ($node['enabled']) {
	 		$moduleSet[] = $node;
	 	}
	}
}
?>