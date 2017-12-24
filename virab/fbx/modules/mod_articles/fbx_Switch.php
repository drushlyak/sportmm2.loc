<?php
	$module_var = "mod_articles";

	$_XFA['main']			= FuseUrl($module_var . '.' . 'main');
	$_XFA['mainf']			= FuseUrl($module_var . '.' . 'main', array('str_error' => '%s'));
	$_XFA['form']			= FuseUrl($module_var . '.' . 'form', array('type' => '%d', 'id' => '%d'));
	$_XFA['formf']			= FuseUrl($module_var . '.' . 'form', array('str_error' => '%s', 'type' => '%d', 'id' => '%d'));
	$_XFA['delete']			= FuseUrl($module_var . '.' . 'delete', array('id' => '%d'));
	$_XFA['store']			= FuseUrl($module_var . '.' . 'store');
	
	//список статей категории
	$_XFA['articles']			= FuseUrl($module_var . '.' . 'articles', array('id_category' => '%d'));
	$_XFA['articlesf']			= FuseUrl($module_var . '.' . 'articles', array('id_category' => '%d', 'str_error' => '%s'));
	$_XFA['articles_form']		= FuseUrl($module_var . '.' . 'articles_form', array('type' => '%d', 'id_category' => '%d', 'id' => '%d'));
	$_XFA['articles_formf']		= FuseUrl($module_var . '.' . 'articles_form', array('str_error' => '%s', 'type' => '%d', 'id_category' => '%d', 'id' => '%d'));
	$_XFA['articles_delete']	= FuseUrl($module_var . '.' . 'articles_delete', array('id' => '%d', 'id_category' => '%d'));
	$_XFA['articles_store']		= FuseUrl($module_var . '.' . 'articles_store');

	

	
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
			
		//список статей категории
		case "articles":
	 	case "articlesf":
	 	case "Fusebox.defaultFuseaction":
	  		include("list/qry_Main.php");
	  		include("list/dsp_Main.php");
	 		break;
		case "articles_form":
		case "articles_formf":
			include("list/qry_Form.php");
			include("list/dsp_Form.php");
			break;
		case "articles_store":
			include("list/act_Store.php");
			break;
		case "articles_delete":
			include("list/act_Delete.php");
			break;
	 	

		default:
	  		print "I received a fuseaction called <b>'" . $Fusebox["fuseaction"] . "'</b> that circuit <b>'" . $Fusebox["circuit"] . "'</b> does not have a handler for.";
	  		break;
	}

?>
