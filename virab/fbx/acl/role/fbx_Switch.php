<?php

	$module_var = "role";

	$_XFA['main']				= FuseUrl($module_var . '.' . 'main');
	$_XFA['mainf']				= FuseUrl($module_var . '.' . 'main', array('str_error'=>'%s'));

	$_XFA['form']				= FuseUrl($module_var . '.' . 'form', array('type' => '%d', 'parent_id' => '%d', 'id' => '%d'));
	$_XFA['formf']				= FuseUrl($module_var . '.' . 'form', array('error' => '%d', 'type' => '%d', 'parent_id' => '%d', 'id' => '%d', 'params' => '%s'));
	$_XFA['store']				= FuseUrl($module_var . '.' . 'store');
	$_XFA['delete']				= FuseUrl($module_var . '.' . 'delete', array('id' => '%d'));
	$_XFA['ch_parent']			= FuseUrl($module_var . '.' . 'ch_parent', array('id' => '%d'));
	$_XFA['ch_parent_store']	= FuseUrl($module_var . '.' . 'ch_parent_store');

	// ACL
	$resTree = $auth_in->store->getResourceTree();
	$resourceId = $auth_in->getModuleResource($module_var);
	$ACL_ERROR = "";

	switch($Fusebox["fuseaction"]) {
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

		case "ch_parent":
			include ("qry_ChParent.php");
			include ("dsp_ChParent.php");
			break;

		case "ch_parent_store":
			include ("act_ChParentStore.php");
			break;

	 	default:
	  		print _("Для  fuseaction <b>'" . $Fusebox["fuseaction"] . "'</b> не зарегистрирован обработчик!");
	  		break;
	}

?>

