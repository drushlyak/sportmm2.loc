<?php

	$_XFA['main']             = "index.php?fuseaction=struct.main";
	$_XFA['mainf']            = "index.php?fuseaction=struct.main&str_error=%s";
	$_XFA['form']             = "index.php?fuseaction=struct.form&typ=%d&id=%d";
	$_XFA['delete']           = "index.php?fuseaction=struct.delete&id=%d";
	$_XFA['store']            = "index.php?fuseaction=struct.store";
	// Выбор селективных шаблонов
	$_XFA['selective']            = "index.php?fuseaction=struct.selective&id=%d";
	$_XFA['selective_store']            = "index.php?fuseaction=struct.selective_store";
	// Добавление параметров
	$_XFA['variable']            = "index.php?fuseaction=struct.variable&id=%d";
	$_XFA['variable_store']            = "index.php?fuseaction=struct.variable_store";
	
	$_XFA['ch_parent']        = "index.php?fuseaction=struct.ch_parent&id=%d";
	$_XFA['ch_parent_store']  = "index.php?fuseaction=struct.ch_parent_store";
	$_XFA['ch_pos_top']       = "index.php?fuseaction=struct.ch_pos_top&id=%d";
	$_XFA['ch_pos_bottom']    = "index.php?fuseaction=struct.ch_pos_bottom&id=%d";
	$_XFA['ch_type_visual']   = "index.php?fuseaction=struct.ch_type_visual";
	
	$_XFA['mainexec']         = "index.php?fuseaction=struct.mainexec&id1=%d";
	$_XFA['mainexecp']         = "index.php?fuseaction=struct.mainexecp&id1=%d";
	$_XFA['mainexecf']        = "index.php?fuseaction=struct.mainexec&id1=%d&str_error=%s";
	$_XFA['editexeccont']     = "index.php?fuseaction=struct.editexeccont&id1=%d&id=%d&typ=%d";
	$_XFA['storeexeccont']    = "index.php?fuseaction=struct.storeexeccont";
	
	$_XFA['mainbig']          = "index.php?fuseaction=struct.mainbig&id1=%d&id2=%d";
	$_XFA['mainbigf']         = "index.php?fuseaction=struct.mainbig&id1=%d&id2=%d&str_error=%s";
	$_XFA['formbigpage']       = "index.php?fuseaction=struct.formbigpage&typ=%d&id1=%d&id2=%d&idp=%d";
	$_XFA['storebigpage']     = "index.php?fuseaction=struct.storebigpage";
	$_XFA['storenewbigpage']  = "index.php?fuseaction=struct.storenewbigpage";
	$_XFA['deletebigpage']    = "index.php?fuseaction=struct.deletebigpage&id1=%d&id2=%d&id=%d";
	$_XFA['ch_pos_topbig']    = "index.php?fuseaction=struct.ch_pos_topbig&id1=%d&id2=%d&idp=%d";
	$_XFA['ch_pos_bottombig'] = "index.php?fuseaction=struct.ch_pos_bottombig&id1=%d&id2=%d&idp=%d";
	// Информация о модуле
	$resTree = $auth_in->store->getResourceTree();
	$resourceId = $auth_in->getModuleResource('site_struct');
	$ACL_ERROR = "";
	switch($Fusebox["fuseaction"]){
		case "main":
		case "Fusebox.defaultFuseaction":
			if ($_SESSION['type_visual_site_nodes'] === 'list') {
				include ("qry_List_Main.php");
				include ("dsp_List_Main.php");
			} else {
				include ("qry_Tree_Main.php");
				include ("dsp_Tree_Main.php");
			}
			break;
		case "form":
			include ("qry_Form.php");
			include ("dsp_Form.php");  
			break;
		case "store":
			include ("act_Store.php");
			break;
		case "selective":
			include ("qry_Selective.php");
			include ("dsp_Selective.php");
			break;
		case "selective_store":
			include ("act_Store_Selective.php");
			break;
		case "variable":
			include ("qry_Variable.php");
			include ("dsp_Variable.php");
			break;
		case "variable_store":
			include ("act_Store_Variable.php");
			break;
		case "storecont":
			include ("act_StoreCont.php");
			break;
		case "delete":
			include ("act_Delete.php");
			break;
		case "ch_parent":  // Форма выбора нового родителя узлу
			include ("qry_ChParent.php");
			include ("dsp_ChParent.php");  
			break;
		case "ch_parent_store":  // Смена родителя узла
			include ("act_ChParentStore.php");
			break;
		case "ch_pos_top":  // Перемещение узла вверх по дереву
			include ("act_ChPosTop.php");
			break;
		case "ch_pos_bottom":  // Перемещение узла вниз по дереву
			include ("act_ChPosBottom.php");
			break;
		case "ch_type_visual":  // Смена типа отображения списка узлов
			include ("act_Tree_vs_List.php");
			break;
	// Раздел работы с редактирование содержимого страницы
		case "mainexecp":
			include ("edit_content/qry_Main.php");
			include ("edit_content/dsp_Main.php");
			break;
		case "editexeccont":
			include ("edit_content/qry_Edit.php");
			include ("edit_content/dsp_Edit.php");  
			break;
		case "storeexeccont":
			include ("edit_content/act_Store.php");
			break;
	// Раздел работы с много страничными документами
		case "mainbig":
			include ("edit_content/bigpage/qry_Main.php");
			include ("edit_content/bigpage/dsp_Main.php");  
			break;
		case "formbigpage":
			include ("edit_content/bigpage/qry_Form.php");
			include ("edit_content/bigpage/dsp_Form.php");  
			break;
		case "storebigpage":
			include ("edit_content/bigpage/act_Store.php");
			break;
		case "deletebigpage":
			include ("edit_content/bigpage/act_Delete.php");
			break;
		case "ch_pos_topbig":  // Перемещение узла вверх по дереву
			include ("edit_content/bigpage/act_ChPosTop.php");
			break;
		case "ch_pos_bottombig":  // Перемещение узла вниз по дереву
			include ("edit_content/bigpage/act_ChPosBottom.php");
			break;
		default:
			print _("Для  fuseaction <b>'" . $Fusebox["fuseaction"] . "'</b> не зарегистрирован обработчик!");
			break;
	}

?>