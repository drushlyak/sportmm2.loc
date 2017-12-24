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

	// PagerTitle
	if ($type == 2):
		?>
			<script type="text/javascript">
				setPagerTitle('.&nbsp;<?=_("Редактирование пользователя")?> &laquo;<?=prepareForShow($lng->Gettextlng($form_data['login']))?>&raquo;');
			</script>
		<?php
	endif;

	$configTables = $auth_in->store->getConfig();

	$dsp_helper->writeForm(array(
		'type' => TFFORM,
		'name' => 'data_form',
		'method' => 'post',
		'action' => $_XFA['store'],
		'has_file_field' => false,
		'has_lng' => true,

		'fields' => array(
			array(
				'typeField' => TFTEXTFIELD,
				'name' => 'login',
				'label' => _('Логин'),
				'value' => $form_data['login'],
				'required' => true
			),
			array(
				'typeField' => TFTEXTFIELDLNG,
				'name' => 'full_name',
				'label' => _('ФИО пользователя'),
				'value' => $form_data['full_name'],
				'required' => true
			),
			array(
				'typeField' => TFPASSWORDFIELD,
				'name' => 'password',
				'label' => _('Пароль'),
				'required' => true
			),
			array(
				'typeField' => TFTEXTFIELD,
				'name' => 'email',
				'label' => _('Email'),
				'value' => $form_data['email'],
				'required' => true
			),
			array(
				'typeField' => TFSELECTDATASETLNG,
				'name' => 'role_id',
				'label' => _('Роль пользователя'),
				'dataSet' => $db->get_all("SELECT * FROM " . $configTables['roleTable']),
				'selected' => array($form_data['role_id']),
				'empty' => true,
				'required' => true
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
