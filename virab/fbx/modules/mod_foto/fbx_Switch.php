<?php
	$module_var = "mod_foto";
	
	// Группы фотографий
	$_XFA['cat_main']			 = FuseUrl($module_var . '.' . 'cat_main');
	$_XFA['cat_mainf']			 = FuseUrl($module_var . '.' . 'cat_main', array('str_error' => '%s'));
	$_XFA['cat_form']			 = FuseUrl($module_var . '.' . 'cat_form', array('type' => '%d', 'id' => '%d'));
	$_XFA['cat_formf']			 = FuseUrl($module_var . '.' . 'cat_form', array('error' => '%d', 'type' => '%d', 'id' => '%d', 'params' => '%s'));
	$_XFA['cat_delete']			 = FuseUrl($module_var . '.' . 'cat_delete', array('id' => '%d'));
	$_XFA['cat_store']			 = FuseUrl($module_var . '.' . 'cat_store');
	$_XFA['cat_ch_parent']		 = FuseUrl($module_var . '.' . 'cat_ch_parent', array('id' => '%d'));
	$_XFA['cat_ch_parent_store'] = FuseUrl($module_var . '.' . 'cat_ch_parent_store');
	$_XFA['cat_ch_pos_top']      = FuseUrl($module_var . '.' . 'cat_ch_pos_top', array('id' => '%d'));
	$_XFA['cat_ch_pos_bottom']   = FuseUrl($module_var . '.' . 'cat_ch_pos_bottom', array('id' => '%d'));

	// Список фотографий в групе
	$_XFA['main']		   = FuseUrl($module_var . '.' . 'main', array('id_fotogr' => '%d'));
	$_XFA['mainf']		   = FuseUrl($module_var . '.' . 'main', array('id_fotogr' => '%d', 'error' => '%d'));
	$_XFA['form']		   = FuseUrl($module_var . '.' . 'form', array('type' => '%d', 'id_fotogr' => '%d', 'id' => '%d'));
	$_XFA['formf']		   = FuseUrl($module_var . '.' . 'form', array('error' => '%d', 'id_fotogr' => '%d', 'type' => '%d', 'id' => '%d', 'params' => '%s'));
	$_XFA['delete']		   = FuseUrl($module_var . '.' . 'delete', array('id_fotogr' => '%d', 'id' => '%d'));
	$_XFA['store']		   = FuseUrl($module_var . '.' . 'store');
	$_XFA['ch_pos_top']	   = FuseUrl($module_var . '.' . 'ch_pos_top', array('id_fotogr' => '%d', 'id' => '%d'));
	$_XFA['ch_pos_bottom'] = FuseUrl($module_var . '.' . 'ch_pos_bottom', array('id_fotogr' => '%d', 'id' => '%d'));


	// Фотографии в группе
	$_XFA['photo']			= FuseUrl($module_var . '.' . 'photo', array('id_fotogr' => '%d'));
	$_XFA['photof']			= FuseUrl($module_var . '.' . 'photo', array('str_error' => '%d', 'id_fotogr' => '%d', 'params' => '%s'));
	$_XFA['photo_store']	= FuseUrl($module_var . '.' . 'photo_store');
	$_XFA['photo_delete']	= FuseUrl($module_var . '.' . 'photo_delete');
	$_XFA['photo_toprivate']= FuseUrl($module_var . '.' . 'photo_toprivate');
	$_XFA['photo_topublic']	= FuseUrl($module_var . '.' . 'photo_topublic');
	$_XFA['photo_reorder']	= FuseUrl($module_var . '.' . 'photo_reorder');
	
	
	$fotoTree = new NSTree(
		CFG_DBTBL_MOD_FOTO_GRTREE,
		CFG_DBTBL_MOD_FOTO_GRDATA, 
		array(
			'id'      => TREE_STRUCT_ID,
			'data_id' => TREE_STRUCT_DATA_ID,
			'left'    => TREE_STRUCT_LEFT,
			'right'   => TREE_STRUCT_RIGHT,
			'level'   => TREE_STRUCT_LEVEL
		)
	);
	
	// Информация о модуле
	$resTree = $auth_in->store->getResourceTree();
	$resourceId = $auth_in->getModuleResource($module_var);
	$ACL_ERROR = "";
	$FORM_ERROR = "";
	
	switch($Fusebox["fuseaction"]) {
		// Группы фотографий
		case "cat_main":
		case "cat_mainf":
		case "Fusebox.defaultFuseaction":
			include("qry_Main.php");
			include("dsp_Main.php");
			break;
		case "cat_form":
		case "cat_formf":
			include("qry_Form.php");
			include("dsp_Form.php");  
			break;
		case "cat_store":
			include ("act_Store.php");
			break;
		case "cat_delete":
			include("act_Delete.php");
			break;
		case "cat_ch_parent":  // Форма выбора нового родителя узлу
			include ("qry_ChParent.php");
			include ("dsp_ChParent.php");  
			break;
		case "cat_ch_parent_store":  // Смена родителя узла
			include ("act_ChParentStore.php");
			break;
		case "cat_ch_pos_top":  // Перемещение узла вверх по дереву
			include ("act_ChPosTop.php");
			break;
		case "cat_ch_pos_bottom":  // Перемещение узла вниз по дереву
			include ("act_ChPosBottom.php");
			break;
			
		// Фотографии в группе
		case "photo":
		case "photof":
			include("list/qry_Main.php");
			include("list/dsp_Main.php");
			break;
		case "photo_store":
			include("list/act_Store.php");
			break;
		case "photo_delete":
			include("list/act_Delete.php");
			break;
		case "photo_reorder":
			include("list/act_Reorder.php");
			break;
		
		
		// Список фотографий в группе
		case "main":
		case "mainf":
			include("list/qry_Main.php");
			include("list/dsp_Main.php");
			break;
		case "form":
		case "formf":
			include("list/qry_Form.php");
			include("list/dsp_Form.php");  
			break;
		case "store":
			include ("list/act_Store.php");
			break;
		case "delete":
			include("list/act_Delete.php");
			break;
		case "ch_pos_top":
			include ("list/act_ChPosTop.php");
			break;
		case "ch_pos_bottom":
			include ("list/act_ChPosBottom.php");
			break;
		default:
			print "I received a fuseaction called <b>'" . $Fusebox["fuseaction"] . "'</b> that circuit <b>'" . $Fusebox["circuit"] . "'</b> does not have a handler for.";
			break;
	}
?>