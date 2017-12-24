<?php

	$_XFA['main']				= "index.php?fuseaction=backup.main";
	$_XFA['mainf']				= "index.php?fuseaction=backup.main&str_error=%s";
	$_XFA['mainmessage']		= "index.php?fuseaction=backup.main&str_message=%s";

	$_XFA['store']				= "index.php?fuseaction=backup.store";
	$_XFA['restore']			= "index.php?fuseaction=backup.restore&id=%d";
	$_XFA['delete']				= "index.php?fuseaction=backup.delete&id=%d";
	$_XFA['loadform']			= "index.php?fuseaction=backup.loadform";
	$_XFA['loaddump']			= "index.php?fuseaction=backup.loaddump";

	// ACL
	$resTree = $auth_in->store->getResourceTree();
	$resourceId = $auth_in->getModuleResource('backup');
	$ACL_ERROR = "";

	switch($Fusebox["fuseaction"]){
		case "main":
		case "mainf":
		case "mainmessage":
		case "Fusebox.defaultFuseaction":
			include ("qry_Main.php");
			include ("dsp_Main.php");
			break;
		case "store":
			include ("act_Store.php");
			break;
		case "restore":
			include ("act_Restore.php");
			break;
		case "delete":
	  		include("act_Delete.php");
	 		break;
	 	case "loadform":
	  		include("dsp_Form.php");
	 		break;
	 	case "loaddump":
	 		include("act_FileStore.php");
	 		break;
		default:
			print _("Для  fuseaction <b>'" . $Fusebox["fuseaction"] . "'</b> не зарегистрирован обработчик!");
			break;
	}

?>