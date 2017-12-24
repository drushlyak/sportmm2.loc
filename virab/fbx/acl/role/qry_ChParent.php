<?php

	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, CHANGE_PARENT)){
		$ACL_ERROR = _("У вас нет прав на смену родительской роли");
		return;
	}

	include_once "qry_Main.php";
?>
