<?
	// Проверка доступа
	if (!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _("У вас нет прав на просмотр");
		Location(sprintf($_XFA['mainf'], ((strlen(trim($ACL_ERROR)) > 350) ? substr($ACL_ERROR, 0, 350)."..." : trim($ACL_ERROR))), 0);
		die;
	}

	include_once('functions.php');

	$dir_struct = getDirStruct(BACKUP_PATH);
	$data = getDumpParamSet($dir_struct);

	$nodeSet = $data;
?>