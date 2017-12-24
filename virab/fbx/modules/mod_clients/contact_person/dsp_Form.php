<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
	// Ошибки
	if ($attributes['error']) {
		$indata = unserialize($attributes['params']);
		$FORM_ERROR = $indata['str_error'];
		?><p class="cerr"><?=$FORM_ERROR?></p><?php
			// Подстановка введенных значений
			if (!is_array($form_data)) {
				$form_data = array();
			}
			if (is_array($indata)) {
				foreach ($indata as $key => $value) {
					$form_data[$key] = $value;
				}
			}
	}
	// Доступ
	if (!$auth_in->isAllowed()) {
		?><p class="cerr"><?=$ACL_ERROR?></p><?php
		return;
	}

	$dsp_helper = new DspHelper();
	$fc = $dsp_helper->getFormContainer(array(
		'type' => TFFORM,
		'name' => 'data_form',
		'method' => 'post',
		'action' => $_XFA['contact_person_store'],
		'has_lng' => false
	));
	$fc->begin();
		$fblock = $fc->getFieldBlock();

		$fblock->show(TFTEXTFIELD, array(
			'name' => 'fio',
			'label' => _('ФИО'),
			'value' => $mod_data['fio']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'email',
			'label' => _('Email'),
			'value' => $mod_data['email']
		));

		$fblock->show(TFBUTTON, array(
			'type' => 'submit',
			'value' => _('Сохранить')
		));

		// скрытые поля
		$fblock->show(TFHIDDENFIELD, array(
			'name' => 'type',
			'value' => $attributes['type']
		));
		$fblock->show(TFHIDDENFIELD, array(
			'name' => 'id',
			'value' => $attributes['id']
		));
		$fblock->show(TFHIDDENFIELD, array(
			'name' => 'id_client',
			'value' => $id_client
		));
	$fc->end();

?>