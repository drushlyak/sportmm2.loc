<?php
	$_XFA['main']            = "index.php?fuseaction=tevalue.main&pg=%d&count_pg=%d&str_error=%s";
	$_XFA['mainf']           = "index.php?fuseaction=tevalue.main&str_error=%s";
	$_XFA['form']            = "index.php?fuseaction=tevalue.form&typ=%d&id=%d&pg=%d&count_pg=%d";
	$_XFA['delete']          = "index.php?fuseaction=tevalue.delete&id=%d&pg=%d&count_pg=%d";
	$_XFA['store']           = "index.php?fuseaction=tevalue.store";
	
	$resourceId = $auth_in->getModuleResource('template_var');
	$ACL_ERROR = "";
	switch($Fusebox["fuseaction"]) {
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