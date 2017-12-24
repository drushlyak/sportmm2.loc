<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
	if(!$auth_in->aclCheck($resourceId, CREATE)){
		$ACL_ERROR = _("У вас нет прав на создание");
		return;
	}
	// Ошибки
	$FORM_ERROR = $attributes['str_error'];
	if ($FORM_ERROR) {
		?><p class="cerr"><?=$FORM_ERROR?></p><?php
	}
	// Доступ
	if (!$auth_in->isAllowed()) {
		echo "<p class=\"cerr\">".$ACL_ERROR."</p>";
		return;
	}

	if ($attributes['type'] == 1) {
		$namem = _('Название модуля');
		$varbegin = 'mod_';
	} else {
		$namem = _('Название словаря');
		$varbegin = 'dict_';
	}

	$fc = $dsp_helper->getFormContainer(array(
		'type' => TFFORM,
		'name' => 'form',
		'method' => 'post',
		'action' => $_XFA['manualstore'],
		'has_lng' => true
	));
	$fc->begin();
		$fblock = $fc->getFieldBlock();
		$fblock->show(TFTEXTFIELDLNG, array(
			'name' => 'name',
			'label' => $namem,
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'var',
			'label' => _("Переменная"),
			'width' => 464,
			'textblock' => array('begin' => $varbegin)
		));
		$fblock->show(TFBUTTON, array(
			'type' => 'submit',
			'value' => _("Создать")
		));
		$fblock->show(TFHIDDENFIELD, array(
			'name' => 'type',
			'value' => $attributes['type']
		));
	$fc->end();

?>
