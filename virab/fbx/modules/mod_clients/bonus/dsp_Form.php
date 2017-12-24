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
		'action' => $_XFA['bonus_store'],
		'has_lng' => false
	));
	$fc->begin();
		$fblock = $fc->getFieldBlock();

		$fblock->show(TFTEXTFIELD, array(
			'name' => 'bonus',
			'label' => _('Бонус, руб.'),
			'value' => $mod_data['bonus']
		));
		$fblock->show(TFSELECTDATASET, array(
			'name' => 'type_bonus_action',
			'label' => _('Тип'),
			'multiple' => false,
			'empty' => true,
			'dataSet' => $__TYPE_BONUS_ACTION,
			'params' => array('size' => '1'),
			'selected' => array($mod_data['type_bonus_action'])
		));
		$fblock->show(TFAUTOSUGGESTFIELD, array(
			'label' => 'Заказ',
			'name' => 'id_order',
			'backend' => SITE_URL . '/library/libcruiser4/ajax/find_order.php',
			'varname' => 'number',
			'value' => ((int) $mod_data['id_order'] ? array(
				'id' => $mod_data['id_order'],
				'number' => $mod_data['order_number']
			) : "")
		));
		$fblock->show(TFTEXTAREA, array(
			'name' => 'comment',
			'label' => _('Комментарий'),
			'rows' => 4,
			'value' => $mod_data['comment']
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