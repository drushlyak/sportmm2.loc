<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php

	// Ошибки
	if ($attributes['error']) {
		$data_hash = $attributes['params'];
		$datas = $_SESSION['formdata'][$data_hash];

		if (is_array($datas)) {
			$FORM_ERROR = $_SESSION['formdata'][$data_hash]['str_error'];
			$form_data = $_SESSION['formdata'][$data_hash];
		}

		?><p class="cerr"><?=$FORM_ERROR?></p><?php
	}

	// Доступ
	if (!$auth_in->isAllowed()) {
		?><p class="cerr"><?=$ACL_ERROR?></p><?php
		return;
	}

	$dsp_helper->writeForm(array(
		'type' => TFFORM,
		'name' => 'data_form',
		'method' => 'post',
		'action' => $_XFA['store'],
		'has_file_field' => false,
		'has_lng' => false,

		'fields' => array(
			array(
				'typeField' => TFTEXTFIELD,
				'name' => 'config_name',
				'label' => _('Переменная'),
				'value' => $form_data['config_name'],
				'required' => true
			),
			array(
				'typeField' => TFTEXTFIELD,
				'name' => 'config_value',
				'label' => _('Значение переменной'),
				'value' => $form_data['config_value'],
				'required' => true
			),
			array(
				'typeField' => TFTEXTAREA,
				'name' => 'description',
				'label' => _('Описание'),
				'rows' => 10,
				'value' => $form_data['description']
			),

			// кнопка
			array(
				'typeField' => TFBUTTON,
				'type' => 'submit',
				'value' => _('Сохранить')
			),

			// скрытые поля
			array(
				'typeField' => TFHIDDENFIELD,
				'name' => 'id',
				'value' => $attributes['id']
			),
			array(
				'typeField' => TFHIDDENFIELD,
				'name' => 'type',
				'value' => $attributes['type']
			)
		)
	));

?>