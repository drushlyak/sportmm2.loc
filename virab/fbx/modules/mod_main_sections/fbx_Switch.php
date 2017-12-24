<?php
	$module_var = "mod_main_sections";
						
	$_XFA['main']			= FuseUrl($module_var . '.' . 'main');
	$_XFA['mainf']			= FuseUrl($module_var . '.' . 'main', array('str_error' => '%s'));
	$_XFA['form']			= FuseUrl($module_var . '.' . 'form', array('type' => '%d', 'id' => '%d'));
	$_XFA['formf']			= FuseUrl($module_var . '.' . 'form', array('str_error' => '%s', 'type' => '%d', 'id' => '%d'));
	$_XFA['delete']			= FuseUrl($module_var . '.' . 'delete', array('id' => '%d'));
	$_XFA['store']			= FuseUrl($module_var . '.' . 'store');
	$_XFA['ch_pos_top']      = FuseUrl($module_var . '.' . 'ch_pos_top', array('id' => '%d'));
	$_XFA['ch_pos_bottom']   = FuseUrl($module_var . '.' . 'ch_pos_bottom', array('id' => '%d'));
	
	
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
		case "ch_pos_top":  // Перемещение узла вверх по дереву
			include ("act_ChPosTop.php");
			break;
		case "ch_pos_bottom":  // Перемещение узла вниз по дереву
			include ("act_ChPosBottom.php");
			break;		
	 		
	 	default:
	  		print "I received a fuseaction called <b>'" . $Fusebox["fuseaction"] . "'</b> that circuit <b>'" . $Fusebox["circuit"] . "'</b> does not have a handler for.";
	  		break;
	}
	
?>
