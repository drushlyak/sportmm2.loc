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
		'action' => $_XFA['cio_address_store'],
		'has_lng' => false
	));
	$fc->begin();
		$fblock = $fc->getFieldBlock();

		$fblock->show(TFSELECTDATASET, array(
			'name' => 'type_of_address',
			'label' => 'Тип адреса',
			'multiple' => false,
			'empty' => false,
			'dataSet' => array(array('id' => 0, 'name' => 'Не выбран тип адреса'), array('id' => 1, 'name' => 'Рабочий'), array('id' => 2, 'name' => 'Домашний'), array('id' => 3, 'name' => 'Частный дом'), array('id' => 4, 'name' => 'Гостиница'), array('id' => 4, 'name' => 'Больница')),
			'params' => array('size' => '1'),
			'selected' => array($mod_data['type_of_address'])
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'company',
			'label' => _('Компания'),
			'value' => $mod_data['company']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'institution_name',
			'label' => _('Название'),
			'value' => $mod_data['institution_name']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'department',
			'label' => _('Отделение'),
			'value' => $mod_data['department']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'city',
			'label' => _('Город'),
			'value' => $mod_data['city']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'street',
			'label' => _('Улица'),
			'value' => $mod_data['street']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'house',
			'label' => _('Дом'),
			'value' => $mod_data['house']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'building',
			'label' => _('Строение'),
			'value' => $mod_data['building']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'entrance',
			'label' => _('Корпус'),
			'value' => $mod_data['entrance']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'porch',
			'label' => _('Подъезд'),
			'value' => $mod_data['porch']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'hotel_room',
			'label' => _('Номер комнаты'),
			'value' => $mod_data['hotel_room']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'ward',
			'label' => _('Палата'),
			'value' => $mod_data['ward']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'flat',
			'label' => _('Квартира'),
			'value' => $mod_data['flat']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'office',
			'label' => _('Офис'),
			'value' => $mod_data['office']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'doorphone',
			'label' => _('Домофон'),
			'value' => $mod_data['doorphone']
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
		$fblock->show(TFHIDDENFIELD, array(
			'name' => 'id_contact',
			'value' => $id_contact
		));
	$fc->end();

?>