<?php

$id = intval($attributes['id']);
$did = $attributes['did'];
$FORM_ERROR = "";
$resTree = $auth_in->store->getResourceTree();
//=====================================================
function delElement($id)
{
	global $nsTree, $db, $lng, $FORM_ERROR, $resTree, $auth_in, $ACL_ERROR;
	if ($id) {
		if ($info = $nsTree->getNodeInfo($id)) {
			$category = $nsTree->getNode($info['id'], array('name', 'title', 'description', 'keywords', 'id_contaner', 'res_id'));
// Проверка доступа
			if(!$auth_in->aclCheck($category['res_id'], DELETE)){
				$ACL_ERROR .= _(" У вас нет прав на удаление шаблона с id=") . $id;
				return false;
			}
// Удалим все языковые записи
			$lng->Deltext($category['name']);
			$lng->Deltext($category['title']);
			$lng->Deltext($category['description']);
			$lng->Deltext($category['keywords']);
// Удалим все записи для WYSIWYG полей которые имеются
			$rsNodes = $db->query(sql_placeholder("SELECT text FROM ".CFG_DBTBL_TE_EXECWCODE." WHERE id_map=?", $category['id']));
			while ($node = $rsNodes->fetch_assoc()) {
				$lng->Deltext($node['text']);
			}
			$sql = sql_placeholder("
				DELETE
				FROM ".CFG_DBTBL_TE_EXECWCODE."
				WHERE id_map=?",
				$category['id']
			);
			$db->query($sql);
// Удалим все записи для комбинированных шаблонов
			$sql = sql_placeholder("
				DELETE
				FROM ".CFG_DBTBL_TE_SELECTIVE_TMPL."
				WHERE id_map=?",
				$category['id']
			);
			$db->query($sql);
			$node = $nsTree->getNode($id, array('res_id'));
// Удалим узлы
//			$auth_in->acl->remove($node['res_id'], false);
			$nsTree->removeNodes($id, false);
		} else {
			$FORM_ERROR .= _("Отсутствует запись для id=") . $id;
		}
	} else {
		$FORM_ERROR .= _("Не определен id записи");
	}
	return true;
}
//============================================================
if (is_array($did)) {
	foreach ($did as $delel) {
		delElement($delel);
	}
} elseif ($id) {
	delElement($id);
}
Location(sprintf($_XFA['mainf'], ((strlen(trim($FORM_ERROR." ".$ACL_ERROR)) > 350) ? substr($FORM_ERROR." ".$ACL_ERROR, 0, 350)."..." : trim($FORM_ERROR." ".$ACL_ERROR))), 0);

?>