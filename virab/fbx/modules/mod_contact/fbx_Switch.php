<?php
	$module_var = "mod_contact";

	$_XFA['main']			= FuseUrl($module_var . '.' . 'main');
	$_XFA['mainf']			= FuseUrl($module_var . '.' . 'main', array('str_error'=>'%s'));
	$_XFA['view']			= FuseUrl($module_var . '.' . 'view', array('id'=>'%d'));
	$_XFA['delete']			= FuseUrl($module_var . '.' . 'delete', array('id'=>'%d'));

	// ACL
	$resourceId = $auth_in->getModuleResource($module_var);
	$ACL_ERROR = "";

	switch($Fusebox["fuseaction"]){
		case "main":
		case "mainf":
		case "Fusebox.defaultFuseaction":
			include("qry_Main.php");
			include("dsp_Main.php");
			break;
		case "view":
			include("qry_View.php");
			include("dsp_View.php");
			break;
		case "delete":
			include("act_Delete.php");
			break;
		default:
			print "I received a fuseaction called <b>'" . $Fusebox["fuseaction"] . "'</b> that circuit <b>'" . $Fusebox["circuit"] . "'</b> does not have a handler for.";
			break;
	}
?>