<?php

	$module_var = "settings";

	$_XFA['main']			 = FuseUrl($module_var . '.' . 'main');
	$_XFA['mainf']			 = FuseUrl($module_var . '.' . 'main', array('str_error' => '%s'));
	$_XFA['form']			 = FuseUrl($module_var . '.' . 'form', array('type' => '%d', 'id' => '%d'));
	$_XFA['formf']			 = FuseUrl($module_var . '.' . 'form', array('error' => '%d', 'type' => '%d', 'id' => '%d', 'params' => '%s'));
	$_XFA['delete']			 = FuseUrl($module_var . '.' . 'delete', array('id' => '%d'));
	$_XFA['store']			 = FuseUrl($module_var . '.' . 'store');

	$resourceId = $auth_in->getModuleResource($module_var);
	$ACL_ERROR = "";

	switch($Fusebox["fuseaction"]){
		case "main":
		case "Fusebox.defaultFuseaction":
			include ("qry_Main.php");
			include ("dsp_Main.php");
			break;

		case "form":
			include ("qry_Form.php");
			include ("dsp_Form.php");
			break;

		case "store":
			include ("act_Store.php");
			break;

		case "delete":
			include ("act_Delete.php");
			break;

		default:
			print _("Для  fuseaction <b>'" . $Fusebox["fuseaction"] . "'</b> не зарегистрирован обработчик!");
			break;
	}

?>