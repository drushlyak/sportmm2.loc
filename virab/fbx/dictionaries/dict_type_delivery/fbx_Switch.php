<?php
	$module_var = "dict_type_delivery";
	
	$_XFA['main']			= FuseUrl($module_var . '.' . 'main');
	$_XFA['mainf']			= FuseUrl($module_var . '.' . 'main', array('str_error'=>'%s'));
	// Типы доставок
	$_XFA['type_delivery_form']		= FuseUrl($module_var . '.' . 'type_delivery_form', array('type' => '%d', 'id' => '%d'));
	$_XFA['type_delivery_formf']	= FuseUrl($module_var . '.' . 'type_delivery_form', array('str_error' => '%s', 'type' => '%d', 'id' => '%d', 'params' => '%s'));
	$_XFA['type_delivery_delete']	= FuseUrl($module_var . '.' . 'type_delivery_delete', array('id' => '%d'));
	$_XFA['type_delivery_store']	= FuseUrl($module_var . '.' . 'type_delivery_store');
	
	// ACL
	$resourceId = $auth_in->getModuleResource($module_var);
	$ACL_ERROR = "";
	
	switch($Fusebox["fuseaction"]) {
		case "main":
	 	case "mainf":
	 	case "Fusebox.defaultFuseaction":
	  		include("qry_Main.php");
	  		include("dsp_Main.php");
	 		break;
	 	// Типы доставок
		case "type_delivery_form":
		case "type_delivery_formf":
			include("qry_Form.php");
			include("dsp_Form.php");
			break;
		case "type_delivery_store":
			include("act_Store.php");
			break;
		case "type_delivery_delete":
			include("act_Delete.php");
			break;
	 	default:
	  		print _("Для  fuseaction <b>'" . $Fusebox["fuseaction"] . "'</b> не зарегистрирован обработчик!");
	  		break;
	}
	
?>

