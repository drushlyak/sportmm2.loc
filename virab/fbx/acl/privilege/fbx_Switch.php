<?php

	$_XFA['main']            = "index.php?fuseaction=privilege.main";
	$_XFA['mainf']           = "index.php?fuseaction=privilege.main&str_error=%s";
	$_XFA['form']            = "index.php?fuseaction=privilege.form&typ=%d&id=%d";
	$_XFA['formf']           = "index.php?fuseaction=privilege.form&typ=%d&id=%d&str_error=%s";
	$_XFA['delete']          = "index.php?fuseaction=privilege.delete&id=%d";
	$_XFA['store']           = "index.php?fuseaction=privilege.store";
	
	// ALC
	$resourceId = $auth_in->getModuleResource('privilege');
	$ACL_ERROR = "";
	switch ($Fusebox["fuseaction"]) {
		case "main":
		case "Fusebox.defaultFuseaction":
	  		include("qry_Main.php");
	  		include("dsp_Main.php");
	 		break;
		case "form":
			include("qry_Form.php");
			include("dsp_Form.php");  
	 		break;
	 	case "store":
	  		include("act_Store.php");
	 		break;
	 	case "delete":
	  		include("act_Delete.php");
	 		break;
	 	default:
	  		print _("Для  fuseaction <b>'" . $Fusebox["fuseaction"] . "'</b> не зарегистрирован обработчик!");
	  		break;
	}

?>