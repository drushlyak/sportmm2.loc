<?php

	// Проверка доступа
	if(!$auth_in->aclCheck($resourceId, VIEW)){
		$ACL_ERROR = _("У вас нет прав на просмотр");
		return;
	}

	$sql = "
		SELECT 	*,
		        (CASE
		            WHEN deflt = 1
		                THEN 'FFFFD6'
		            ELSE (IF (deflt_msgid_gettext = 1, 'EEEEEE', ''))
		         END
		        ) AS color
		FROM " . CFG_DBTBL_DICT_LANGUAGE . "
	";

	$count_records = $db->query_total($sql);
	$dataSet = $db->get_all($sql);

?>