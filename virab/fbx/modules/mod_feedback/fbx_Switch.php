<?php

	$module_var = "mod_feedback";
	
	// Группы вопросов
	$_XFA['cat_main']	= FuseUrl($module_var . '.' . 'cat_main');
	$_XFA['cat_mainf']	= FuseUrl($module_var . '.' . 'cat_main', array('str_error' => '%s'));
	$_XFA['cat_form']	= FuseUrl($module_var . '.' . 'cat_form', array('type' => '%d', 'id' => '%d'));
	$_XFA['cat_formf']	= FuseUrl($module_var . '.' . 'cat_form', array('error' => '%d', 'type' => '%d', 'id' => '%d', 'params' => '%s'));
	$_XFA['cat_delete']	= FuseUrl($module_var . '.' . 'cat_delete', array('id' => '%d'));
	$_XFA['cat_store']	= FuseUrl($module_var . '.' . 'cat_store');
	
	// Вопросы
	$_XFA['main']		= FuseUrl($module_var . '.' . 'main', array('id_feedback' => '%d'));
	$_XFA['mainf']		= FuseUrl($module_var . '.' . 'main', array('id_feedback' => '%d', 'error' => '%d'));
	$_XFA['form']		= FuseUrl($module_var . '.' . 'form', array('type' => '%d', 'id_feedback' => '%d', 'id' => '%d'));
	$_XFA['formf']		= FuseUrl($module_var . '.' . 'form', array('error' => '%d', 'id_feedback' => '%d', 'type' => '%d', 'id' => '%d', 'params' => '%s'));
	$_XFA['delete']		= FuseUrl($module_var . '.' . 'delete', array('id_feedback' => '%d', 'id' => '%d'));
	$_XFA['store']		= FuseUrl($module_var . '.' . 'store');
	
	// Информация о модуле
	$resTree = $auth_in->store->getResourceTree();
	$resourceId = $auth_in->getModuleResource('mod_feedback');
	$ACL_ERROR = "";
	$FORM_ERROR = "";
	switch($Fusebox["fuseaction"]){
		// Группы вопросов
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
			
		// Отзывы
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
			include ("list/act_Delete.php");
			break;
		default:
			print "I received a fuseaction called <b>'" . $Fusebox["fuseaction"] . "'</b> that circuit <b>'" . $Fusebox["circuit"] . "'</b> does not have a handler for.";
			break;
	}

?>