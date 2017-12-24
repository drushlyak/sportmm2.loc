<?php

	function FuseUrl($fuseAction, $urlPart = array()) {
		$url = 'index.php?fuseaction=' . $fuseAction;
		$queryParts = array();
		foreach ($urlPart as $k => $v) {
			$queryParts[] = $k . '=' . $v;
		}
		
		return $queryParts ? $url . '&' . join('&', $queryParts) : $url;
	}
	
	$Fusebox["circuits"]["home"]			= "home/fbx/main";
	
	// Структура сайта
	$Fusebox["circuits"]["struct_site"]		= "home/fbx/struct_site";
	$Fusebox["circuits"]["struct"]			= "home/fbx/struct_site/struct";
	$Fusebox["circuits"]["template"]		= "home/fbx/struct_site/template";
	$Fusebox["circuits"]["menu"]			= "home/fbx/struct_site/menu";
	
	// Права доступа
	$Fusebox["circuits"]["acl"]				= "home/fbx/acl";
	$Fusebox["circuits"]["role"]			= "home/fbx/acl/role";
	$Fusebox["circuits"]["rule"]			= "home/fbx/acl/rule";
	$Fusebox["circuits"]["privilege"]		= "home/fbx/acl/privilege";
	$Fusebox["circuits"]["users"]			= "home/fbx/acl/users";
	
	// Администрирование
	$Fusebox["circuits"]["admin"]			= "home/fbx/admin";
	$Fusebox["circuits"]["settings"]		= "home/fbx/admin/settings";
	$Fusebox["circuits"]["tevalue"]			= "home/fbx/admin/tevalue";
	$Fusebox["circuits"]["navigation"]		= "home/fbx/admin/navigation";
	$Fusebox["circuits"]["language"]		= "home/fbx/admin/language";
	$Fusebox["circuits"]["admodules"]		= "home/fbx/admin/modules";
	$Fusebox["circuits"]["backup"]			= "home/fbx/admin/backup";
	
	// Модули 
	$Fusebox["circuits"]["modules"]			= "home/fbx/modules";
	
	//Справочники
	$Fusebox["circuits"]["dictionaries"]	= "home/fbx/dictionaries";
	$Fusebox["circuits"]["dict_language"]	= "home/fbx/dictionaries/dict_language";
	
	// Загрузка circuits из БД
	$nodeSet = $db->get_all("SELECT  * FROM ".CFG_DBTBL_CIRCUIT);
	if ($nodeSet) {
		foreach ($nodeSet as $node) {
			$Fusebox["circuits"][$node['name']] = $node['path'];
		}
	}

?>
