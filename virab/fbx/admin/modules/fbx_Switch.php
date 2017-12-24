<?php

	$_XFA['main']			= FuseUrl('admodules.main');
	$_XFA['mainf']			= FuseUrl('admodules.main', array('str_error'=>'%s'));
	$_XFA['form']			= FuseUrl('admodules.form', array('typ'=>'%d', 'id'=>'%d'));
	$_XFA['manualform']		= FuseUrl('admodules.manualform', array('type' => '%d'));
	$_XFA['manualformf']	= FuseUrl('admodules.manualform', array('str_error'=>'%s', 'type' => '%d'));
	$_XFA['manualstore']	= FuseUrl('admodules.manualstore');
	$_XFA['store']			= FuseUrl('admodules.store');
	
	$_XFA['main_priv']		= "index.php?fuseaction=admodules.main_priv&id_mod=%d";
	$_XFA['mainf_priv']		= "index.php?fuseaction=admodules.main_priv&id_mod=%d&str_error=%s";
	$_XFA['form_priv']		= "index.php?fuseaction=admodules.form_priv&id_mod=%d";
	$_XFA['delete_priv']	= "index.php?fuseaction=admodules.delete_priv&id_mod=%d&id=%d";
	$_XFA['store_priv']		= "index.php?fuseaction=admodules.store_priv";
	
	// ACL
	$resourceId = $auth_in->getModuleResource('modules_control');
	$ACL_ERROR = "";
	
	switch($Fusebox["fuseaction"]) {
		case "main":
	 	case "mainf":
	 	case "Fusebox.defaultFuseaction":
	  		include("qry_Main.php");
	  		include("dsp_Main.php");
	 		break;
	 	case "form":
	  		include("qry_Form.php");
	  		include("dsp_Form.php");  
	 		break;
	 	case "manualform": 
	  		include("dsp_ManualForm.php");  
	 		break;
	 	case "manualstore":
	  		include("act_ManualStore.php");  
	 		break;
	 	case "store":
	  		include("act_Store.php");
	 		break;
	 	case "main_priv":
	  		include("priv/qry_Main.php");
	  		include("priv/dsp_Main.php");
	 		break;
		case "form_priv":
	  		include("priv/qry_Form.php");
	  		include("priv/dsp_Form.php");  
	 		break;
	 	case "store_priv":
	  		include("priv/act_Store.php");
	 		break;
	 	case "delete_priv":
	  		include("priv/act_Delete.php");
	 		break;
	 	default:
	  		print _("Для  fuseaction <b>'" . $Fusebox["fuseaction"] . "'</b> не зарегистрирован обработчик!");
	  		break;
	}
	
?>
