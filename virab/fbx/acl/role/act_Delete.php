<?php

	$id = (int) $attributes['id'];
	$did = $attributes['did'];

	$FORM_ERROR = "";

	// Проверка доступа
	if (!$auth_in->aclCheck($resourceId, DELETE)) {
		$ACL_ERROR .= _("У вас нет прав на удаление");
		Location(sprintf($_XFA['mainf'], ((strlen(trim($ACL_ERROR)) > 350) ? substr($ACL_ERROR, 0, 350)."..." : trim($ACL_ERROR))), 0);
		return;
	}

	function delElement($id) {
		global $db, $lng, $FORM_ERROR, $auth_in;

		$configTable = $auth_in->store->getConfig();
		if ($id) {
			// проверим наличие пользователей и правил для данной роли
			if (
				($db->get_one("SELECT COUNT(*) FROM ( SELECT role_id FROM {$configTable['ruleTable']} WHERE role_id = ? GROUP BY resource_id) AS roles", $id))
				||
				($db->get_one("SELECT COUNT(*) FROM " . CFG_DBTBL_UDATA . " WHERE role_id = ?", $id))
			) {
				$FORM_ERROR .= _("Удалить роль невозможно из-за наличия связанных с нею правил или пользователей");
			}

			clearLngRecords($configTable['roleTable'], array('id' => $id), array('name'), false);
			$auth_in->acl->removeRole($id);
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

	if ($FORM_ERROR) {
		Location(sprintf($_XFA['mainf'], ((strlen(trim($FORM_ERROR)) > 350) ? substr($FORM_ERROR, 0, 350)."..." : trim($FORM_ERROR))), 0);
	} else {
		Location($_XFA['main'], 0);
	}


?>