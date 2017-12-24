<?php

	$_XFA['add']             = "index.php?fuseaction=template.add&parent_id=%d";
	$_XFA['main']            = "index.php?fuseaction=template.main";
	$_XFA['mainf']           = "index.php?fuseaction=template.main&str_error=%s";
	$_XFA['form']            = "index.php?fuseaction=template.form&typ=%d&id=%d";
	$_XFA['delete']          = "index.php?fuseaction=template.delete&id=%d";
	$_XFA['store']           = "index.php?fuseaction=template.store";
	$_XFA['ch_parent']        = "index.php?fuseaction=template.ch_parent&id=%d";
	$_XFA['ch_parent_store']  = "index.php?fuseaction=template.ch_parent_store";
	
	$_XFA['mainwcode']       = "index.php?fuseaction=template.mainwcode&id1=%d";
	$_XFA['mainwcodef']      = "index.php?fuseaction=template.mainwcode&id1=%d&str_error=%s";
	$_XFA['formwcode']       = "index.php?fuseaction=template.formwcode&typ=%d&id1=%d&id=%d";
	$_XFA['deletewcode']     = "index.php?fuseaction=template.deletewcode&id1=%d&id=%d";
	$_XFA['storewcode']      = "index.php?fuseaction=template.storewcode";
	// Информация о модуле
	$resTree = $auth_in->store->getResourceTree();
	$resourceId = $auth_in->getModuleResource('template');
	$ACL_ERROR = "";
	switch($Fusebox["fuseaction"]){
		case "main":
		case "Fusebox.defaultFuseaction":
			include("qry_Main.php");
			include("dsp_Main.php");
			break;
		case "form":
			include("qry_Form.php");
			include("dsp_Form.php");  
			break;
		case "store":
			include("act_Store.php");
			break;
		case "ch_parent":  // Форма выбора нового родителя узлу
			include ("qry_ChParent.php");
			include ("dsp_ChParent.php");  
			break;
		case "ch_parent_store":  // Смена родителя узла
			include ("act_ChParentStore.php");
			break;
		case "delete":
			include("act_Delete.php");
			break;
			
	//Раздел работы со списком всех WYSIWYG кодов этого исполнителя
		case "mainwcode":
			include("execwcode/qry_Main.php");
			include("execwcode/dsp_Main.php");
			break;
		case "formwcode":
			include("execwcode/qry_Form.php");
			include("execwcode/dsp_Form.php");
			break;
		case "storewcode":
			include ("execwcode/act_Store.php");
			break;
		case "deletewcode":
			include ("execwcode/act_Delete.php");
			break;
		default:
			print _("Для  fuseaction <b>'" . $Fusebox["fuseaction"] . "'</b> не зарегистрирован обработчик!");
			break;
	}

?>