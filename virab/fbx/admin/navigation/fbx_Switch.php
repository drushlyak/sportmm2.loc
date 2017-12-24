<?php

	$_XFA['main']            = "index.php?fuseaction=navigation.main";
	$_XFA['mainf']           = "index.php?fuseaction=navigation.main&str_error=%s";
	$_XFA['form']            = "index.php?fuseaction=navigation.form&typ=%d&id=%d";
	$_XFA['delete']          = "index.php?fuseaction=navigation.delete&id=%d";
	$_XFA['store']           = "index.php?fuseaction=navigation.store";
	$_XFA['ch_parent']       = "index.php?fuseaction=navigation.ch_parent&id=%d";
	$_XFA['ch_parent_store'] = "index.php?fuseaction=navigation.ch_parent_store";
	$_XFA['ch_pos_top']      = "index.php?fuseaction=navigation.ch_pos_top&id=%d";
	$_XFA['ch_pos_bottom']   = "index.php?fuseaction=navigation.ch_pos_bottom&id=%d";
	
	$resourceId = $auth_in->getModuleResource('navigation');
	$ACL_ERROR = "";
	switch($Fusebox["fuseaction"]) {
	 case "main":
	 case "Fusebox.defaultFuseaction":
	  include ("qry_Main.php");
	  include ("dsp_Main.php");
	 break;
	 
	 case "form":
	  include ("qry_Form.php");
	  include ("dsp_Form.php");  
	 break;
	
	 case "store":
	  include ("act_Store.php");
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
	  
	 case "ch_pos_bottom":  //Перемещение узла вниз по дереву
	  include ("act_ChPosBottom.php");
	 break;
	  
	 default:
	  print _("Для  fuseaction <b>'" . $Fusebox["fuseaction"] . "'</b> не зарегистрирован обработчик!");
	  break;
	}
?>