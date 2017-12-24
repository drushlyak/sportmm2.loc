<?
	// Проверка доступа
	if (!$auth_in->aclCheck($resourceId, DELETE)) {
		$ACL_ERROR .= _("У вас нет прав на удаление");
		Location(sprintf($_XFA['mainf'], ((strlen(trim($ACL_ERROR)) > 350) ? substr($ACL_ERROR, 0, 350)."..." : trim($ACL_ERROR))), 0);
		die;
	}

	$id = $attributes['id'];
	$did = $attributes['did'];
	$FORM_ERROR = "";

	include_once('functions.php');

	function delElement($id) {
		global $db, $FORM_ERROR;

		if ($id) {

			$dir_struct = getDirStruct(BACKUP_PATH);
			$ids = getIDS($dir_struct);
			$filename = $ids[$id];

			@unlink(BACKUP_PATH . "/" . $filename);

		} else {
			$FORM_ERROR .= _(" Отсутствует запись id=") . $id;
		}
		return true;
	}

	if (is_array($did)) {
		foreach ($did as $delel){
			delElement($delel);
		}
	} elseif($id) {
		delElement($id);
	}

	Location(sprintf($_XFA['mainf'], ((strlen(trim($FORM_ERROR)) > 350) ? substr($FORM_ERROR, 0, 350)."..." : trim($FORM_ERROR))), 0);

?>