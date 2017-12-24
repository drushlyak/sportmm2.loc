<?php

	$var = $attributes['var'];
	$type = $attributes['type']; // 1 - модуль, 2 - словарь
	$is_module = ($attributes['type'] == 1) ? true : false;
	
	$defl_name = trim($attributes['name'][$lng->deflt_lng]);
	if (!$defl_name) {
		$FORM_ERROR = _("Необходимо указать название модуля для языка по-умолчанию");
	}

	if (!$FORM_ERROR) {
		$name = $lng->SetTextlng($attributes['name']);
		$mod_var = "mod_" . $var;
		$dict_var = "dict_" . $var;
		
		// Создадим директорию
		$dir_path = $is_module ? "../../modules/" . $mod_var : "../../dictionaries/" . $dict_var; 
		@mkdir($dir_path);
		@chmod($dir_path, 0777);
		
		// Скопируем подготовленные файлы
		$files = array(
			'dsp_Acl.php',
			'dsp_Main.php',
			'fbx_Switch.php',
			'qry_Main.php'
		);
		foreach ($files as $file) {
			@copy("man_create/" . $file, $dir_path . "/" . $file);
			@chmod($dir_path . "/" . $file, 0766);
		}
		// Переименовываем dsp_Acl
		$dspfile = "dsp_Acl" . ucfirst($var) . ".php";
		@rename($dir_path . "/dsp_Acl.php", $dir_path . "/" . $dspfile);
		
		// Редактируем переменную модуля в fbx_Switch.php
		$fc = file_get_contents($dir_path . "/fbx_Switch.php");
		$fc = str_replace("\$module_var = \"\";", "\$module_var = \"" . ($is_module ? $mod_var : $dict_var) . "\";", $fc);
		file_put_contents($dir_path . "/fbx_Switch.php", $fc);
		
		// Записываем данные в таблицу модулей
		$name = $lng->SetTextlng($attributes['name']);
		$sql = sql_placeholder("
			INSERT 
			INTO " . CFG_DBTBL_MODULE . " 
			SET 
				var = ?, 
				name = ?, 
				description=?,
				sys = 0, 
				enabled = 1,
				version = 0.01, 
				creation_date = now(), 
				mod_type = ?
		", $is_module ? $mod_var : $dict_var
		 , $name
		 , $name
		 , ($is_module ? 0 : 1) );
		$db->query($sql);
		$mod_id = $db->insert_id;	// ID модуля
		
		// Пишем circuit
		$mod_path = $is_module ? "home/fbx/modules/" . $mod_var . "/" : "home/fbx/dictionaries/" . $dict_var . "/";
		$sql = sql_placeholder("
			INSERT 
			INTO " . CFG_DBTBL_CIRCUIT . " 
			SET name = ?, 
				path = ?
		", ($is_module ? $mod_var : $dict_var)
		 , $mod_path);
		$db->query($sql);

		$configTable = $auth_in->store->getConfig();
		// Записываем права доступа для модуля (для начала только одно - VIEW)
		$VIEW_ID = $db->get_one("SELECT id FROM " . $configTable['privilegeTable'] . " WHERE var = 'view'");
		
		$sql = sql_placeholder("
			INSERT 
			INTO " . CFG_DBTBL_ACL_MOD_PRIV . "
				SET	module_id = ?
				  ,	privilege_id = ?
		", $mod_id, $VIEW_ID);
		$db->query($sql);					
		
		// Сохраняем информацию о dsp_Acl... файле
		$sql = sql_placeholder(" 
			INSERT INTO " . CFG_DBTBL_DSP_ACL . "
				SET file = ?
				  , mod_id = ?
		", $dspfile
		 , $mod_id); 
		$db->query($sql);		
		
		// Определение parent_id для STree navigation
		$parentId = $db->get_one("
			SELECT id 
				FROM " . CFG_DBTBL_NAVIGATION . " 
			WHERE var = '" . ($is_module ? NAVIGATION_MODULE : NAVIGATION_DICT) . "'
		");

		// Установка в дерево навигации
		$sTree->appendChild($parentId, array(
			'title'      => $name,
			'url'        => ($is_module ? $mod_var . ".main" : $dict_var . ".main"),
			'menu'       => 1,
			'edt'        => 0
		), 0);		
		
		// Прописывание в дерево ресурсов
		$resTree = $auth_in->store->getResourceTree();
		$res_id = $auth_in->store->newResourceId();
		$top_id = $resTree->appendChild(1, array(), $res_id);

		// Установка top_id в таблицу module
		$sql = sql_placeholder("
			UPDATE " . CFG_DBTBL_MODULE . " 
				SET	top_id = ? 
			WHERE id = ?
		", $top_id
		 , $mod_id);
		$db->query($sql);

		Location($_XFA['main'], 0);
	} else {
		Location(sprintf($_XFA['manualformf'], $FORM_ERROR), 0);
	}
?>