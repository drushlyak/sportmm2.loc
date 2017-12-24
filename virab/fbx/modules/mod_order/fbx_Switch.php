<?php
	$module_var = "mod_order";

	$_XFA['main']			= FuseUrl($module_var . '.' . 'main');
	$_XFA['mainf']			= FuseUrl($module_var . '.' . 'main', array('str_error' => '%s'));
	$_XFA['form']			= FuseUrl($module_var . '.' . 'form', array('type' => '%d', 'id' => '%d'));
	$_XFA['formf']			= FuseUrl($module_var . '.' . 'form', array('str_error' => '%s', 'type' => '%d', 'id' => '%d'));
	$_XFA['delete']			= FuseUrl($module_var . '.' . 'delete', array('id' => '%d'));
	$_XFA['store']			= FuseUrl($module_var . '.' . 'store');

	$_XFA['print_order_pay'] = FuseUrl($module_var . '.' . 'print_order_pay', array('id' => '%d'));
	$_XFA['print_order']	= FuseUrl($module_var . '.' . 'print_order', array('id' => '%d'));
	$_XFA['print_order_f']	= FuseUrl($module_var . '.' . 'print_order_f', array('id' => '%d', 'id_worker' => '%s'));
	$_XFA['print_order_v']	= FuseUrl($module_var . '.' . 'print_order_v', array('id' => '%d', 'id_worker' => '%s'));

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
		case "form":
		case "formf":
			include("qry_Form.php");
			include("dsp_Form.php");
			break;
		case "store":
			include("act_Store.php");
			break;
		case "delete":
			include("act_Delete.php");
			break;
			
		case "print_order_pay":
			include("qry_PrintO.php");
			include("dsp_PrintOrderPay.php");
			break;

		case "print_order":
			include("qry_PrintO.php");
			include("dsp_PrintOrder.php");
			break;

		case "print_order_f":
			include("qry_PrintOrder.php");
			include("dsp_PrintOrderF.php");
			break;

		case "print_order_v":
			include("qry_PrintOrder.php");
			include("dsp_PrintOrderV.php");
			break;

	 	default:
	  		print "I received a fuseaction called <b>'" . $Fusebox["fuseaction"] . "'</b> that circuit <b>'" . $Fusebox["circuit"] . "'</b> does not have a handler for.";
	  		break;
	}

?>
