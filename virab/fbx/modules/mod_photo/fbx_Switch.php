<?php
	$module_var = "mod_photo";

	$_XFA['main']			= FuseUrl($module_var . '.' . 'main');
	$_XFA['mainf']			= FuseUrl($module_var . '.' . 'main', array('str_error' => '%s'));
	$_XFA['form']			= FuseUrl($module_var . '.' . 'form', array('type' => '%d', 'id' => '%d'));
	$_XFA['formf']			= FuseUrl($module_var . '.' . 'form', array('str_error' => '%s', 'type' => '%d', 'id' => '%d'));
	$_XFA['delete']			= FuseUrl($module_var . '.' . 'delete', array('id' => '%d'));
	$_XFA['store']			= FuseUrl($module_var . '.' . 'store');

	// Фотографии продукта
	$_XFA['photo_delete']	= FuseUrl($module_var . '.' . 'photo_delete');
	$_XFA['photo_toprivate']= FuseUrl($module_var . '.' . 'photo_toprivate');
	$_XFA['photo_topublic'] = FuseUrl($module_var . '.' . 'photo_topublic');
	$_XFA['photo_reorder']  = FuseUrl($module_var . '.' . 'photo_reorder');
	$_XFA['photo_alt_store']= FuseUrl($module_var . '.' . 'photo_alt_store');

	$_XFA['record_active']	= FuseUrl($module_var . '.' . 'record_active', array('id' => '%d', 'id_record' => '%d', 'operation' => '%d'));

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

		// Фотографии продукта
		case "photo_delete":
			include("act_PhotoDelete.php");
			break;
		case "photo_reorder":
			include("act_Reorder.php");
			break;
		case "photo_alt_store":
			include("act_StoreAlt.php");
			break;

		case "record_active":
			include("comment/act_ActiveRecord.php");
			break;


		default:
	  		print "I received a fuseaction called <b>'" . $Fusebox["fuseaction"] . "'</b> that circuit <b>'" . $Fusebox["circuit"] . "'</b> does not have a handler for.";
	  		break;
	}

?>
