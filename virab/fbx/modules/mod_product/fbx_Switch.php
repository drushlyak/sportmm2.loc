<?php
	$module_var = "mod_product";

	$_XFA['main']			= FuseUrl($module_var . '.' . 'main');
	$_XFA['mainf']			= FuseUrl($module_var . '.' . 'main', array('str_error' => '%s'));
	$_XFA['form']			= FuseUrl($module_var . '.' . 'form', array('type' => '%d', 'id' => '%d'));
	$_XFA['formf']			= FuseUrl($module_var . '.' . 'form', array('str_error' => '%s', 'type' => '%d', 'id' => '%d'));
	$_XFA['delete']			= FuseUrl($module_var . '.' . 'delete', array('id' => '%d'));
	$_XFA['store']			= FuseUrl($module_var . '.' . 'store');
	
	$_XFA['record_active']	= FuseUrl($module_var . '.' . 'record_active', array('id' => '%d', 'operation' => '%d'));

	// Фотографии продукта
	$_XFA['photo']			= FuseUrl($module_var . '.' . 'photo', array('id_product' => '%d'));
	$_XFA['photof']			= FuseUrl($module_var . '.' . 'photo', array('str_error' => '%d', 'id_product' => '%d', 'params' => '%s'));
	$_XFA['photo_store']	= FuseUrl($module_var . '.' . 'photo_store');
	$_XFA['photo_delete']	= FuseUrl($module_var . '.' . 'photo_delete');
	$_XFA['photo_toprivate']= FuseUrl($module_var . '.' . 'photo_toprivate');
	$_XFA['photo_topublic']	= FuseUrl($module_var . '.' . 'photo_topublic');
	$_XFA['photo_reorder']	= FuseUrl($module_var . '.' . 'photo_reorder');
	$_XFA['photo_alt']		= FuseUrl($module_var . '.' . 'photo_alt', array('id_photo' => '%d'));
	$_XFA['photo_alt_store']= FuseUrl($module_var . '.' . 'photo_alt_store');

	// Рекомендуемые продукты
	$_XFA['recommended']		= FuseUrl($module_var . '.' . 'recommended', array('id_product' => '%d'));
	$_XFA['recommendedf']		= FuseUrl($module_var . '.' . 'recommended', array('str_error' => '%d', 'id_product' => '%d', 'params' => '%s'));
	$_XFA['recommended_form']	= FuseUrl($module_var . '.' . 'recommended_form', array('type' => '%d', 'id' => '%d', 'id_product' => '%d'));
	$_XFA['recommended_formf']	= FuseUrl($module_var . '.' . 'recommended_form', array('str_error' => '%d', 'type' => '%d', 'id' => '%d', 'id_product' => '%d', 'params' => '%s'));
	$_XFA['recommended_store']	= FuseUrl($module_var . '.' . 'recommended_store');
	$_XFA['recommended_delete']	= FuseUrl($module_var . '.' . 'recommended_delete', array('id' => '%d', 'id_product' => '%d'));

	
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
			
		case "record_active":
			include("act_ActiveRecord.php");
			break;

		// Фотографии продукта
		case "photo":
		case "photof":
			include("photo/qry_Main.php");
			include("photo/dsp_Main.php");
			break;
		case "photo_store":
			include("photo/act_Store.php");
			break;
		case "photo_delete":
			include("photo/act_Delete.php");
			break;
		case "photo_reorder":
			include("photo/act_Reorder.php");
			break;
		case "photo_alt":
			include("photo/qry_AltText.php");
			include("photo/dsp_AltText.php");
			break;
		case "photo_alt_store":
			include("photo/act_StoreAlt.php");
			break;

		// Рекомендуемые продукты
		case "recommended":
		case "recommendedf":
			include("recommended/qry_Main.php");
			include("recommended/dsp_Main.php");
			break;
		case "recommended_form":
		case "recommended_formf":
			include("recommended/qry_Form.php");
			include("recommended/dsp_Form.php");
			break;
		case "recommended_store":
			include("recommended/act_Store.php");
			break;
		case "recommended_delete":
			include("recommended/act_Delete.php");
			break;

		
		default:
	  		print "I received a fuseaction called <b>'" . $Fusebox["fuseaction"] . "'</b> that circuit <b>'" . $Fusebox["circuit"] . "'</b> does not have a handler for.";
	  		break;
	}

?>
