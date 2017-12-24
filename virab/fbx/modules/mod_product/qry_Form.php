<?php
	$id  = (int)$attributes['id'];
	$type = (int) $attributes['type'];

	// Проверка доступа
	if ($id && ($type == 2)) {
		if (!$auth_in->aclCheck($resourceId, EDIT)) {
			$ACL_ERROR = _("У вас нет прав на редактирование");
			return;
		}
	} else {
		if (!$auth_in->aclCheck($resourceId, CREATE)) {
			$ACL_ERROR = _("У вас нет прав на создание");
			return;
		}
	}

	$main_cat_arr = $db->get_all("SELECT id, name FROM " . CFG_DBTBL_DICT_MAIN_CATEGORY);
	if (is_array($main_cat_arr)) {
		$cat_array = array();
		foreach ($main_cat_arr as $mca) {
			//$cat_array[(int) $mca['id'] + 1000] = $mca['name'];
			$category_array = $db->get_hashtable("SELECT id, name FROM " . CFG_DBTBL_DICT_CATEGORY . " WHERE id_main_category = ?", $mca['id']);
			if (is_array($category_array)) {
				foreach ($category_array as $key => $val) {
					$cat_array = $cat_array + array((int) $key => $mca['name'] . '&nbsp;->&nbsp;' . $val);
				}
			}
		}
	}
	$category_array = $cat_array;
	//print_r($category_array);
	$tags_all = $db->get_vector("SELECT name FROM " . CFG_DBTBL_MOD_TAGS);
	if (is_array($tags_all))
		$tags = implode('","', $tags_all);

	if ($id) {
		$sql = sql_placeholder("
			SELECT *
			FROM " . CFG_DBTBL_MOD_PRODUCT . "
	    	WHERE id = ?
	    ", $id );
		$mod_data = $db->get_row($sql);

		$mod_data['items'] = $db->get_all("
			SELECT mu.id
				 , mu.name
				 , mu.article
				 , mup.count
				 , mup.views AS viewOption
				FROM " . CFG_DBTBL_MOD_UNIT_PRODUCT . " AS mup
				   , " . CFG_DBTBL_MOD_UNIT . " AS mu
				WHERE mu.id = mup.id_units
				  AND mup.id_product = ?
		", $id);

		$mod_data['categories'] = $db->get_all("
			SELECT mc.id
				 , CONCAT(dmc.name,' -> ', mc.name) AS name
				FROM " . CFG_DBTBL_MOD_CATEGORY_PRODUCT . " AS mcp
				   , " . CFG_DBTBL_DICT_CATEGORY . " AS mc
				   , " . CFG_DBTBL_DICT_MAIN_CATEGORY . " AS dmc
				WHERE mc.id = mcp.id_category
				  AND mc.id_main_category = dmc.id
				  AND mcp.id_product = ?
		", $id);

		$mod_data['collection'] = $db->get_all("
			SELECT mc.id
				 , mc.name AS name
				FROM " . CFG_DBTBL_MOD_COLLECTION_PRODUCT . " AS mcp
				   , " . CFG_DBTBL_DICT_COLLECTIONS . " AS mc
				WHERE mc.id = mcp.id_collection
				  AND mcp.id_product = ?
		", $id);

		$mod_data['is_new'] = ($db->get_one("SELECT id FROM " . CFG_DBTBL_MOD_TYPE_VIEW_PRODUCT . " WHERE id_product = ? AND id_type_view = 2", $id) > 0) ? 1 : 0;
		$mod_data['is_hit'] = ($db->get_one("SELECT id FROM " . CFG_DBTBL_MOD_TYPE_VIEW_PRODUCT . " WHERE id_product = ? AND id_type_view = 3", $id) > 0) ? 1 : 0;
		$mod_data['is_promo'] = ($db->get_one("SELECT id FROM " . CFG_DBTBL_MOD_TYPE_VIEW_PRODUCT . " WHERE id_product = ? AND id_type_view = 4", $id) > 0) ? 1 : 0;
		
		$tags_arr = $db->get_all("SELECT t.* FROM " . CFG_DBTBL_MOD_PRODUCT_TAGS . " AS pt, " . CFG_DBTBL_MOD_TAGS . " AS t WHERE pt.id_product = ? AND t.id = pt.id_tag", $id);
		if (is_array($tags_arr)) {
			foreach($tags_arr as $tag) {
				$mod_data['a_tags'] .= (strlen(trim($mod_data['a_tags'])) > 0) ? ' ' . $tag['name'] : $tag['name'];
			}
		}

		if(!is_array($mod_data)){
			Location($_XFA['main'], 0);
		}
	}

?>