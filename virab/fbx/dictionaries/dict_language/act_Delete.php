<?php

	$id	= (int) $attributes['id'];
	$did = $attributes['did'];

	$FORM_ERROR = "";

	// Проверка доступа
	if (!$auth_in->aclCheck($resourceId, DELETE)) {
		$ACL_ERROR .= _(" У вас нет прав на удаление");
		Location(sprintf($_XFA['mainf'], ((strlen(trim($ACL_ERROR)) > 350) ? substr($ACL_ERROR, 0, 350)."..." : trim($ACL_ERROR))), 0);
		return;
	}

	function delElement($id) {
		global $db, $lng, $FORM_ERROR;
		if ($id) {
			clearLngRecords(CFG_DBTBL_DICT_LANGUAGE, array('id' => $id), array('name'));
			// удалим все записи из таблицы Language c удаленным языком
			$db->delete(CFG_DBTBL_LANGUAGE, array(
				'id_dict_language' => $id
			));

			// очистим memcache
			if (class_exists('Memcache') && MEMCACHE_USE) {
				$memcache = new Memcache();
				$memcache->connect(MEMCACHE_CONFIG_HOST, MEMCACHE_CONFIG_PORT);
				$memcache->flush();
			}

		} else {
			$FORM_ERROR .= _("Не определен id записи");
		}
		return true;
	}

	if (is_array($did)) {
		foreach ($did as $delel) {
			delElement($delel);
		}
	} elseif ($id) {
		delElement($id);
	}

	Location(sprintf($_XFA['mainf'], ((strlen(trim($FORM_ERROR)) > 350) ? substr($FORM_ERROR, 0, 350)."..." : trim($FORM_ERROR))), 0);

?>