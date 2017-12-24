<?php

$double_id = 0;
$id        = intval($attributes['id']);
$typ 	   = intval($attributes['typ']);
$type_template = intval($attributes['type_template']);
$type_executor = ($type_template == TE_VALUE_EXECUTOR)?intval($attributes['type_executor']):0;
$printable = intval($attributes['printable']);
$content   = ($attributes['content'] == 'on')   ? 1 : 0;
$value     = $attributes['value'];
$is_selective = (intval($attributes['is_selective']))?"1":"0";
if($type_template != TE_VALUE_EXECUTOR){
	$type_executor = 0;
}
$parentId = 0;
if(($typ == 1) && ($id > 1)){
	$parentId = $id;
}
eval("\$defl_name = trim(\$attributes['name'][".$lng->deflt_lng."]);");
$code = ($attributes['type_template'] != TE_VALUE_EXECUTOR) ?
	trim($attributes['acode']) : (($attributes['type_executor'] == TE_EXECUTOR_CODE) ?
		trim($attributes['code']['msgid']) : "")
	;
if($attributes['type_template'] <> TE_EXECUTOR_FILE){
	if(!$defl_name){
		$FORM_ERROR = _("Необходимо указать название контейнера для языка по-умолчанию");
	}
}
//-----------------------------------------------------------------------
function deleteUnUsedCode($id, $code)
{
	global $cntTree, $db, $nodeAll, $resTree, $auth_in, $__TYPE_EXECUTOR, $lng;
// Получаем список всех дочерних элементов для текущего контейнера
	$nodeSet = $cntTree->select($id,
		array(
			'code',
			'res_id',
			'id_te_value',
			'type_template',
			'type_executor',
			'double_id',
			'name'),
		NSTREE_AXIS_CHILD
	);
	preg_match_all("/(?<={)([a-zA-Z0-9_-]+)(?=})/i", $code, $matches);
	if(is_array($nodeSet)){
		foreach($nodeSet as $node){
// Проверяем наличие всех переменных в коде
			$exist = false;
			if(is_array($matches)){
				foreach($matches[0] as $match){
					if(getTeValueName($node['id_te_value']) == $match){
						$exist = true;
						break;
// Если переменная существует
					}
				}
			}
			if($exist){
				continue;
			}
			$category = $cntTree->getNode($node['id'],
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
//							$auth_in->acl->remove($del['res_id'], false);
							$sql = "
								DELETE
								FROM ".CFG_DBTBL_TE_CONTDATA."
								WHERE data_id = ?
							";
							$db->query($sql, $del['data_id']);
						}
					}
				}
				$cntTree->removeNodes($category['id'], true, false);
			}
		}
	}
}
//-----------------------------------------------------------------------
function ParseCode($id, $code, $used = array())
{
	global $cntTree, $db, $nodeAll, $resTree, $auth_in;
	$used[] = $id;
	preg_match_all("/(?<={)([a-zA-Z0-9_-]+)(?=})/i", $code, $matches);
	$nodeSel = $cntTree->select($id, array('id_te_value'), NSTREE_AXIS_CHILD);
// Добавление контейнеров в список
	foreach($matches[0] as $match){
// Поищем эту переменную в дочерних элементах
		foreach($nodeSel as $key_nod => $nods){
			if(getTeValueName($nods['id_te_value']) == $match){
				break;
			}
		}
// Если совпадение найдено, то в списке дочерних занулим этот элемент и перебираем дальше
    	if(getTeValueName($nods['id_te_value']) == $match){
    		$nodeSel[$key_nod]['id_te_value'] = 0;
        	continue;
    	}
// Поищем эту переменную среди элементов всего дерева
 		$double = array();
 		$doubles = array();
    	foreach($nodeAll as $nodsAll){
 			if(getTeValueName($nodsAll['id_te_value']) == $match){
 				$doubles[] = $nodsAll;
 			}
 		}
 		if(is_array($doubles)){
 			if(count($doubles) == 1){
 				$double = $doubles[0];
 				$exist = false;
 			}else{
 				foreach($doubles as $dbl){
 					if($dbl['double_id'] != 0){
 						$double = $dbl;
 					}
 					$used[] = $dbl['id'];
 					$exist = true;
 				}
 			}
 		}
// Если есть, то сохраняем только элемент дерева
    	if(getTeValueName($double['id_te_value']) == $match){
    		$used[] = $double['id'];
// Проверим, а не находится ли шаблонная переменная в родительской ветке
			$parentSet = $cntTree->select($id, array('id_te_value'), NSTREE_AXIS_ANCESTOR_OR_SELF);
			if(is_array($parentSet)){
				foreach($parentSet as $parent){
					if($parent['id'] == $double['id']){
						return ;
					}
				}
			}
			if(!$exist){
				$cntTree->updateNode($double['id'], array(
					'double_id' => $double['id']
				));
			}
    		$mainNode = $cntTree->getNodeInfo($id);
			$sql = sql_placeholder("
    			SELECT id
    			FROM ".CFG_DBTBL_TE_CONTTREE."
    			WHERE data_id = ?
    				AND id NOT IN (?@)",
				$mainNode['data_id'], $used
			);
			$nodes = $db->get_all($sql);
			$newid = $cntTree->appendChild($id, array(), $double['data_id']);
			if(is_array($nodes)){
				foreach($nodes as $nd){
					$cntTree->appendChild($nd['id'], array(), $double['data_id']);
				}
			}
        	$subnode = $cntTree->getNode($newid, array('type_template', 'code'));
// Если этот шаблон содержит код, то разберем и его
        	if(($subnode['type_template'] != TE_VALUE_EXECUTOR)
        		&& ($subnode['type_template'] != TE_VALUE_SELECT)
        		&& ($subnode['type_template'] != TE_VALUE_FOLDER)
        	){
        		ParseCode($newid, $subnode['code'], $used);
        	}
// Иначе добавляем данные в дерево и проверяем необходимость добавления данных в список шаблонных переменных
  		}else{
// Проверим наличие такой переменной в списке шаблонных и если нет то добавим и ее и сам узел шаблона
// иначе ошибка.
			$sql = "
				SELECT *
				FROM ".CFG_DBTBL_TE_VALUE."
				WHERE name=?
			";
  			$rsValue = $db->query($sql, $match);
        	if(!$rsValue->num_rows){
        		$sql = "
        			INSERT
        			INTO ".CFG_DBTBL_TE_VALUE."
        			SET
        				name=?,
        				typ=?,
        				sys=1
        		";
        		$db->query($sql, $match, TE_VALUE_CONTANER);
				$res_id = $auth_in->store->newResourceId();
        		$nodeInfo = $cntTree->getNodeInfo($id);
        		$sql = "SELECT id
        			FROM ".CFG_DBTBL_TE_CONTTREE."
        			WHERE data_id = ?
        				AND id NOT IN (?@)
        		";
        		$insert_ids = $db->get_all($sql, $nodeInfo['data_id'], $used);
				$newid = $cntTree->appendChild($id,
        			array(
        				'id_te_value' => getTeValueId($match),
        				'res_id' => $res_id
        			), 0
        		);
        		$newInfo = $cntTree->getNodeInfo($newid);
        		if(is_array($insert_ids)){
        			$cntTree->updateNode($newid,
        				array(
        					'double_id' => $newid
        				)
        			);
        			foreach($insert_ids as $insert_id){
        				$cntTree->appendChild($insert_id['id'], array(), $newInfo['data_id']);
        			}
        		}
        		$parent = $cntTree->getNode($id, array('res_id'));
        		$sql = sql_placeholder("
        			SELECT id
        			FROM ".$resTree->structTable."
        			WHERE data_id=?", $parent['res_id']
        		);
        		$parent_id = $db->get_one($sql);
        		$new_id = $resTree->appendChild($parent_id, array(), $res_id);
        	}
  		}
	}
	return true;
}
//--------------------------------------------------------------------
if(!$FORM_ERROR){
	$name = $lng->SetTextlng($attributes['name']);
	if($type_executor == TE_EXECUTOR_CODE){
		$ccode = $attributes['ccode'];
		$ccode['msgid'] = $lng->NewId();
		$code = $lng->SetTextlng($ccode);
	}
	if($type_executor == TE_EXECUTOR_FILE){
		$code = $attributes['code'];
	}
	if(($typ == 2) && $id){
		$sql = sql_placeholder("
			UPDATE ".CFG_DBTBL_TE_VALUE."
			SET typ=?
			WHERE name=?",
			$attributes['type_template'],
			$attributes['value']
		);
		$db->query($sql);
		$cntTree->updateNode($id, array(
   			'id_te_value'   => getTeValueId($attributes['value']),
       		'name'          => $name,
       		'type_template' => $attributes['type_template'],
       		'type_executor' => $attributes['type_executor'],
       		'printable'     => $printable,
       		'content'       => $content,
       		'code'          => $code,
			'is_selective'  => $is_selective
     	));
	}else{
		$configTable = $auth_in->store->getConfig();
		$res_id = $auth_in->store->newResourceId();
		$sql = sql_placeholder("
			INSERT INTO ".CFG_DBTBL_TE_VALUE."
			SET
				name=?,
				typ=?,
				sys=1",
			$value,
			$attributes['type_template']
		);
		$db->query($sql);
		$id = $cntTree->appendChild($parentId, array(
			'id_te_value'   => getTeValueId($attributes['value']),
          	'name'          => $name,
          	'type_template' => $attributes['type_template'],
          	'type_executor' => $attributes['type_executor'],
          	'printable'     => $printable,
          	'content'       => $content,
          	'code'          => $code,
			'is_selective'  => $is_selective,
			'res_id'		=> $res_id
      	), 0);
		$sql = "
			SELECT
				top_id
			FROM ".CFG_DBTBL_MODULE."
			WHERE var='template'
		";
		if(($typ == 1) && ($parentId > 1)){
// В случае, если добавляется новый элемент не к корню, а вложеный(вложенные шаблоны "папки")
			$top_node = $cntTree->getNode($parentId, array('res_id'));
// Выбираем id для добавления в дерево ресурсов
			$sql = sql_placeholder("
				SELECT id
				FROM ".$resTree->structTable."
				WHERE data_id = ?",
				$top_node['res_id']
			);
		}
		$top_id = $db->get_one($sql);
		$new_id = $resTree->appendChild($top_id, array(), $res_id);
	}
// Если контейнер то обработаем переменные в коде
	if($attributes['type_template'] != TE_VALUE_EXECUTOR && $attributes['type_template'] != TE_VALUE_FOLDER){
		$nodeAll = $cntTree->selectNodes(0, 0, array('id_te_value', 'double_id'));
// и создадим необходимые дочерние контейнеры
		ParseCode($id, $code);
// или удалим ненужные
		deleteUnUsedCode($id, $code);
	}
	Location($_XFA['main'], 0);
}else{
	include ("qry_Form.php");
	include ("dsp_Form.php");
}

?>