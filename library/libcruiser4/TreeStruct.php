<?php
// Структура дерева узлов
$nsTree = new NSTree(
	CFG_DBTBL_PAGETREE,
	CFG_DBTBL_PAGE, 
	array(
		'id'      => TREE_STRUCT_ID,
		'data_id' => TREE_STRUCT_DATA_ID,
		'left'    => TREE_STRUCT_LEFT,
		'right'   => TREE_STRUCT_RIGHT,
		'level'   => TREE_STRUCT_LEVEL
	)
);
// Структура дерева ресурсов прав доступу
$aclTree = new NSTree(
	CFG_DBTBL_ACL_RESOURCE,
	CFG_DBTBL_ACL_RESEQ, 
	array(
		'id'      		=> TREE_STRUCT_ID,
		'data_id' 	=> TREE_STRUCT_DATA_ID,
		'left'    		=> TREE_STRUCT_LEFT,
		'right'   		=> TREE_STRUCT_RIGHT,
		'level'   		=> TREE_STRUCT_LEVEL
	)
);
// Структура дерева контейнеры шаблонов
$cntTree = new NSTree(
	CFG_DBTBL_TE_CONTTREE,
	CFG_DBTBL_TE_CONTDATA, 
	array(
		'id'      => TREE_STRUCT_ID,
		'data_id' => TREE_STRUCT_DATA_ID,
		'left'    => TREE_STRUCT_LEFT,
		'right'   => TREE_STRUCT_RIGHT,
		'level'   => TREE_STRUCT_LEVEL
	)
);
// Структура дерева меню
$mnTree = new NSTree(
	CFG_DBTBL_MENUTREE,
	CFG_DBTBL_MENU, 
	array(
		'id'      => TREE_STRUCT_ID,
		'data_id' => TREE_STRUCT_DATA_ID,
		'left'    => TREE_STRUCT_LEFT,
		'right'   => TREE_STRUCT_RIGHT,
		'level'   => TREE_STRUCT_LEVEL
	)
);
// Структура дерева навигации. STree !
$sTree = new STree(
   CFG_DBTBL_NAVIGATION,
  array(
   'id'        => TREE_STRUCT_ID,
   'parent_id' => TREE_STRUCT_PARENT_ID,
   'ord'       => TREE_STRUCT_ORD,
   )
);
