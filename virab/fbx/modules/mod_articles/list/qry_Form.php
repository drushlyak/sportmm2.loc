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

	if ($id) {
		$sql = sql_placeholder("
			SELECT *
			FROM " . CFG_DBTBL_MOD_ARTICLES . "
	    	WHERE id = ?
	    ", $id );
		$mod_data = $db->get_row($sql);
		
		$tags_all = $db->get_vector("SELECT name FROM " . CFG_DBTBL_MOD_TAGS);
		if (is_array($tags_all))
			$tags = implode('","', $tags_all);
		
		$tags_arr = $db->get_all("SELECT t.* FROM " . CFG_DBTBL_MOD_ARTICLES_TAGS . " AS at, " . CFG_DBTBL_MOD_TAGS . " AS t WHERE at.id_articles = ? AND t.id = at.id_tag", $id);
			if (is_array($tags_arr)) {
				foreach($tags_arr as $tag) {
					$mod_data['a_tags'] .= (strlen(trim($mod_data['a_tags'])) > 0) ? ' ' . $tag['name'] : $tag['name'];
				}
			}
		
		if(!is_array($mod_data)){
			Location(sprintf($_XFA['articles'], $id_category), 0);
		}
	}
	
	

?>