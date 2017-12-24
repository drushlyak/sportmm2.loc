<?php
if(!$attributes['acl']){
	print "Ты как сюда попал?";
	return;
}
$id = intval($attributes['id']);
$parent_id = intval($attributes['parent_id']);
// Изменяем родителя элемента
$node = $fotoTree->getNode($id, array('res_id'));
$sql = "SELECT id FROM ".$resTree->structTable." WHERE data_id=".$node['res_id'];
// Получаем id в дереве ресурсов для перемещаемого узла
$res_id = $db->get_one($sql);
if($parent_id){
	$parent_node = $fotoTree->getNode($parent_id, array('res_id'));
}
$fotoTree->replaceNode($id, $parent_id);
// Перестраиваем дерево ресурсов
if($parent_node){
	$sql = sql_placeholder(" 
		SELECT id 
		FROM ".$resTree->structTable." 
		WHERE data_id=?",
		$parent_node['res_id']
	);
}else{
	$sql = sql_placeholder(" 
		SELECT top_id 
		FROM ".CFG_DBTBL_MODULE." 
		WHERE var='mod_foto'"
	);
}
$res_parent_id = $db->get_one($sql);
$sql = sql_placeholder(" 
	SELECT id 
	FROM ".$resTree->structTable." 
	WHERE data_id=?",
	$node['res_id']
);
$res_id =  $db->get_one($sql);
$resTree->replaceNode($res_id, $res_parent_id);
Location($_XFA['cat_main'], 0);

?>