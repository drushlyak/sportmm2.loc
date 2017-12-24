<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php

	// Проверка доступа
	if (!$auth_in->aclCheck($resourceId, LOAD)){
		$ACL_ERROR = _("У вас нет прав на загрузку базы");
		Location(sprintf($_XFA['mainf'], ((strlen(trim($ACL_ERROR)) > 350) ? substr($ACL_ERROR, 0, 350)."..." : trim($ACL_ERROR))), 0);
		die;
	}

	$fc = $dsp_helper->getFormContainer(array(
		'type' => TFFORM,
		'name' => 'data_form',
		'method' => 'post',
		'action' => $_XFA['loaddump'],
		'has_file_field' => true,
		'has_lng' => false
	));
	$fc->begin();
		$fblock = $fc->getFieldBlock();
		$fblock->show(TFFILEFIELD, array(
			'name' => 'userfile',
			'label' => _('Файл копии БД <small>(в gz файле)</small>')
		));

		$fblock->show(TFBUTTON, array(
			'type' => 'submit',
			'value' => _('Сохранить')
		));

		// скрытые поля
		$fblock->show(TFHIDDENFIELD, array(
			'name' => 'acl',
			'value' => 1
		));
	$fc->end();

?>
