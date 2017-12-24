<?php
$id = intval($attributes['id']);
$did = $attributes['did'];
$FORM_ERROR = "";
//----------------------------------------------------------------
function delElement($id, $used = array())
{
	global $cntTree, $db, $lng, $resTree, $FORM_ERROR, $ACL_ERROR, $auth_in;
	if($id){
		$category = $cntTree->getNode($id,
			array(
				'name',
				'type_template',
				'type_executor',
				'code',
				'id_te_value',
				'res_id',
				'double_id'
			)
		);
		if(is_array($category)){
// Проверка доступа
			if(!$auth_in->aclCheck($category['res_id'], DELETE)){
				$ACL_ERROR .= _("У вас нет прав на удаление шаблона с id=") . $id;
				return false;
			}
// Выбираем все дочерние шаблоны для удаляемого
			$delSet = $cntTree->select($category['id'],
				array(
						'name',
						'type_template',
						'type_executor',
						'code',
						'id_te_value',
						'res_id',
						'double_id'
					),
				NSTREE_AXIS_DESCENDANT_OR_SELF
			);
			if(is_array($delSet)){
				$current_block = 0;
				foreach($delSet as $del){
					if($del['level'] > $current_block && $current_block != 0){
						continue;
					}
					$delete = true;
					if($del['double_id'] != 0){
						$current_block = $del['level'];
// Находим элемент в дереве с таким же data_id
						$sql = sql_placeholder("
							SELECT *
							FROM ".CFG_DBTBL_TE_CONTTREE."
							WHERE data_id = ?
								AND id <> ?
							ORDER BY id",
							$del['data_id'], $del['id']
						);
						$new_main = $db->get_row($sql);
						if(is_array($new_main)){
							$parent_new_main = $cntTree->getParentNode($new_main['id'], array('res_id'));
							$cntTree->updateNode($new_main['id'], array('double_id' => $new_main['id']));
// Изменяем все double_id на новые значения
							$newNodes = $cntTree->select($del['id'], array('double_id'), NSTREE_AXIS_DESCENDANT);
							if(is_array($newNodes)){
								foreach($newNodes as $new_nd){
									$sql = sql_placeholder("
										SELECT *
										FROM ".CFG_DBTBL_TE_CONTTREE."
										WHERE data_id = ?
											AND id <> ?
										ORDER BY id",
										$new_nd['data_id'], $new_nd['id']
									);
									$new_main = $db->get_row($sql);
									if(is_array($new_main)){
										$cntTree->updateNode($new_main['id'], array('double_id' => $new_main['id']));
									}
								}
							}
// Выбираем id в дереве ресурсов для перестройки дерева
							$sql = "
								SELECT id
								FROM ".$resTree->structTable."
								WHERE data_id = ?
							";
							$id_res = $db->get_one($sql, $del['res_id']);
							$id_new_res = $db->get_one($sql, $parent_new_main['res_id']);
							$resTree->replaceNode($id_res, $id_new_res);
							$delete = false;
						}
					}
// Удаляем ненужные wysiwyg и простой текст
					if($del['type_template'] == TE_VALUE_EXECUTOR){
						$drop_codes = false;
						if($del['type_executor'] == TE_EXECUTOR_SCREEN_WYSIWYG){
							$tbl = CFG_DBTBL_TE_EXECWCODE;
							$drop_codes = true;
						}elseif($del['type_executor'] == TE_EXECUTOR_SIMPLE){
							$tbl = CFG_DBTBL_TE_EXECSCODE;
							$drop_codes = true;
						}
						if($drop_codes){
							$sql = "
								SELECT *
								FROM ".$tbl."
								WHERE id_executor = ?
							";
							$dels = array();
							$execSet = $db->get_all($sql, $del['id']);
							if(is_array($execSet)){
								foreach($execSet as $exec){
									$lng->Deltext($exec['text']);
									$dels[] = $exec['id'];
								}
								$sql = sql_placeholder("
									DELETE
									FROM ".$tbl."
									WHERE id IN (?@)",
									$dels
								);
								$db->query($sql);
							}
						}
					}
// В случае, если шаблон больше нигде не используется удаляем его из дерева ресурсов!
					if($delete){
//						$auth_in->acl->remove($del['res_id'], false);
						$sql = "
							DELETE
							FROM ".CFG_DBTBL_TE_CONTDATA."
							WHERE data_id = ?
						";
						$db->query($sql, $del['data_id']);
					}
				}
			}
			$remove_child = ($category['type_template'] == TE_VALUE_FOLDER)?false:true;
			$cntTree->removeNodes($category['id'], $remove_child, false);
		}else{
			$FORM_ERROR .= _("Отсутствует запись для id=") . $id;
		}
	}else{
		$FORM_ERROR .= _("Не определен id записи");
	}
	return true;
}
//----------------------------------------------------------------------
$used = array();
if(is_array($did)){
	foreach ($did as $delel){
		delElement($delel);
	}
}elseif($id){
	delElement($id);
}
Location(sprintf($_XFA['mainf'], ((strlen(trim($FORM_ERROR." ".$ACL_ERROR)) > 350) ? substr($FORM_ERROR." ".$ACL_ERROR, 0, 350)."..." : trim($FORM_ERROR." ".$ACL_ERROR))), 0);

?>