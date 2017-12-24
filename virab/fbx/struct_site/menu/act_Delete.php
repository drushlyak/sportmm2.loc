<?php
// Проверка доступа
if(!$auth_in->aclCheck($resourceId, DELETE)){
	$ACL_ERROR .= _(" У вас нет прав на удаление");
	Location(sprintf($_XFA['mainf'], ((strlen(trim($ACL_ERROR)) > 350) ? substr($ACL_ERROR, 0, 350)."..." : trim($ACL_ERROR))), 0);
	die;
}
$id  = intval($attributes['id']);
$did = $attributes['did'];
$FORM_ERROR = "";
$resTree = $auth_in->store->getResourceTree();
//-------------------------------------------
function delElement($id)
{

global $mnTree, $db, $lng, $resTree, $FORM_ERROR, $auth_in;
if($id){
	if($info = $mnTree->getNodeInfo($id)){
		$category = $mnTree->getNode($info['id'], array('name','id_te_value'));
		$rsNodes = $db->query(sql_placeholder("SELECT id FROM ".CFG_DBTBL_MENUTREE." WHERE data_id=?", $category['data_id']));
		if($rsNodes->num_rows <= 1){
			$lng->Deltext($category['name']);
		}
		$rsDelValue = $db->query(sql_placeholder("DELETE FROM ".CFG_DBTBL_TE_VALUE." WHERE id=?", $category['id_te_value']));
		$node = $mnTree->getNode($id, array('res_id'));
		$sql = sql_placeholder("
			SELECT id FROM $resTree->structTable WHERE data_id=?", $node['res_id']
		);
		$res_id = $db->get_one($sql);
// Удалим узлы
//		$auth_in->acl->remove($node['res_id']);
		$resTree->removeNodes($res_id, true);
		$mnTree->removeNodes($id, true);
	}
}else{
	$FORM_ERROR .= _("Не определен id записи");
}
return true;

}
//----------------------------------------------------
if(is_array($did)){
	foreach($did as $delel){
		delElement($delel);
	}
}elseif($id){
	delElement($id);
}
Location(sprintf($_XFA['mainf'], ((strlen(trim($FORM_ERROR)) > 350) ? substr($FORM_ERROR, 0, 350)."..." : trim($FORM_ERROR))), 0);

?>