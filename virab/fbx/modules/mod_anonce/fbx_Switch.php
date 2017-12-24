<?php
	$module_var = "mod_anonce";

	$_XFA['main']	= FuseUrl($module_var . '.' . 'main');
	$_XFA['mainf']	= FuseUrl($module_var . '.' . 'main', array('str_error' => '%s'));
	$_XFA['form']	= FuseUrl($module_var . '.' . 'form', array('type' => '%d', 'id' => '%d'));
	$_XFA['formf']	= FuseUrl($module_var . '.' . 'form', array('error' => '%d', 'type' => '%d', 'id' => '%d', 'params' => '%s'));
	$_XFA['delete']	= FuseUrl($module_var . '.' . 'delete', array('id' => '%d'));
	$_XFA['store']	= FuseUrl($module_var . '.' . 'store');
	
	$_XFA['record_active']	= FuseUrl($module_var . '.' . 'record_active', array('id' => '%d', 'operation' => '%d'));
	

	// Информация о модуле
	$resTree = $auth_in->store->getResourceTree();
	$resourceId = $auth_in->getModuleResource($module_var);
	$ACL_ERROR = "";
	switch($Fusebox["fuseaction"]){
		case "main":
		case "mainf":
		case "Fusebox.defaultFuseaction":
			include("qry_Main.php");
			include("dsp_Main.php");
			break;
		case "form":
		case "formf":
			include("qry_Form.php");
			include("dsp_Form.php");
			break;
		case "store":
			include ("act_Store.php");
			break;
		case "delete":
			include("act_Delete.php");
			break;
			
		case "record_active":
			include("act_ActiveRecord.php");
			break;
			
		default:
			print "I received a fuseaction called <b>'" . $Fusebox["fuseaction"] . "'</b> that circuit <b>'" . $Fusebox["circuit"] . "'</b> does not have a handler for.";
			break;
	}
?>