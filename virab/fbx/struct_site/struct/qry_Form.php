<?php
$typ = intval($attributes['typ']);
$id  = intval($attributes['id']);
if($id){
	if($typ == 2){
		if($info = $nsTree->getNodeInfo($id)){
			$category = $nsTree->getNode($info['id'], array(
				'name', 'title', 'description', 'encoding', 'cache', 'robots', 'target', 'chpu',
				'id_contaner', 'enable', 'printable', 'wile', 'keywords', 'res_id'
			));
		$name = $category['name'];
		$category['name'] = $lng->getTextlngall($category['name']);
		$category['title'] = $lng->getTextlngall($category['title']);
		$category['description'] = $lng->getTextlngall($category['description']);
		$category['keywords'] = $lng->getTextlngall($category['keywords']);
		}else{
			Location($_XFA['main'], 0);
		}
// Список доступных языков
		$rsLang = $db->query(sql_placeholder("SELECT language_id FROM ".CFG_DBTBL_PAGELNG." WHERE page_id=? AND page_menu=?", $id, ACC_LNG_PAGE));
		if($rsLang->num_rows){
			while($lang = $rsLang->fetch_assoc()){
				$lngSet[] = $lang['language_id'];
			}
		}
	}else{
		$parent_id = $id;
		$parent_info = $nsTree->getNodeInfo($parent_id);
		$parent = &$nsTree->select($parent_id, array('name', 'res_id'), NSTREE_AXIS_SELF);
	}
}
// Прочитаем список для построения ЧПУ
if($typ == 2){
	$chpuPath = $nsTree->select($id, array('chpu', 'name'), NSTREE_AXIS_ANCESTOR);
}else{
	$chpuPath = $nsTree->select($id, array('chpu', 'name'), NSTREE_AXIS_ANCESTOR_OR_SELF);
}
// Список шаблонов
$tmpConteinerSet = $cntTree->select(0, array('name', 'type_template'), NSTREE_AXIS_DESCENDANT);
$conteinerSet = array();
foreach($tmpConteinerSet as $tmpCont){
	if($tmpCont['type_template'] == TE_VALUE_PAGE){
		$conteinerSet[] = $tmpCont;
	}
}
// Проверка доступа
if(($typ == 2) && ($id)){
	if(!$auth_in->aclCheck($category['res_id'], EDIT)){
		$ACL_ERROR = _("У вас нет прав на редактирование");
		return;
	}
}elseif(($id == 1) && (!$auth_in->aclCheck($resourceId, CREATE))){
	$ACL_ERROR = _("У вас нет прав на создание");
	return;
}elseif(($id>1) && (!$auth_in->aclCheck($parent['res_id'], CREATE))){
	$ACL_ERROR = _("У вас нет прав на создание");
	return;
}
?>