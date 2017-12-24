<?php
	
	// AJAX
	$_XFA['backend'] = FuseUrl('rule.backend');
	$_XFA['main']          = "index.php?fuseaction=rule.main";
	$_XFA['mainf']         = "index.php?fuseaction=rule.main&str_error=%s";
	$_XFA['form']          = "index.php?fuseaction=rule.form";
	$_XFA['store']         = "index.php?fuseaction=rule.store";

	$resTree = $auth_in->store->getResourceTree();
	$resourceId = $auth_in->getModuleResource('rule');
	$ACL_ERROR = "";
	
	switch($Fusebox["fuseaction"]){
		case "main":
			include("qry_Main.php");
			include("dsp_Main.php");
			break;
		case "form":
			include("qry_Form.php");
			include("dsp_Form.php");  
			break;
		case "store":
			include ("act_Store.php");
			break;
	// ajax
		case 'backend':
			switch($attributes['action']){
				case 'select':
					include ('qry_Select.php');
					include ('dsp_Select.php');
					break;
				case 'loadingrights':
					include ('qry_Rights.php');
					include ('dsp_Rights.php');
					break;
				case 'updaterights':
					include ('act_UpdateRights.php');
					break;
				default:
					include ('qry_Rights.php');
					include ('dsp_Rights.php');
					break;
			}
			break;	
		default:
			print _("Для  fuseaction <b>'" . $Fusebox["fuseaction"] . "'</b> не зарегистрирован обработчик!");
			break;
	}

?>