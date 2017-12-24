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

			array(
				'typeField' => TFTEXTFIELD,
				'name' => 'category_cpu_url',
				'label' => _('часть ЧПУ в адресе категории'),
				'value' => $form_data['category_cpu_url']
			),

			array(
				'typeField' => TFTEXTFIELD,
				'name' => 'category_title',
				'label' => _('title для категории'),
				'value' => $form_data['category_title']
			),
			array(
				'typeField' => TFTEXTFIELD,
				'name' => 'category_description',
				'label' => _('description для категории'),
				'value' => $form_data['category_description']
			),
				array(
				'typeField' => TFTEXTFIELD,
				'name' => 'category_keywords',
				'label' => _('keywords для категории'),
				'value' => $form_data['category_keywords']
			),

			array(
				'typeField' => TFTEXTFIELD,
				'name' => 'category_titleh1',
				'label' => _('title H1 для категории'),
				'value' => $form_data['category_titleh1']
			),

			array(
				'typeField' => TFTEXTFIELD,
				'name' => 'category_image_1',
				'label' => _('Картинка для категории'),
				'value' => $form_data['category_image_1']
			),

			array(
				'typeField' => TFTEXTFIELD,
				'name' => 'category_video_1',
				'label' => _('Видео для категории # 1'),
				'value' => $form_data['category_video_1']
			),

			array(
				'typeField' => TFTEXTFIELD,
				'name' => 'category_video_2',
				'label' => _('Видео для категории # 2'),
				'value' => $form_data['category_video_2']
			),

			array(
				'typeField' => TFTEXTFIELD,
				'name' => 'category_video_3',
				'label' => _('Видео для категории # 3'),
				'value' => $form_data['category_video_3']
			),

			array(
				'typeField' => TFTEXTFIELD,
				'name' => 'category_video_4',
				'label' => _('Видео для категории # 4'),
				'value' => $form_data['category_video_4']
			),

			array(
				'typeField' => TFTEXTFIELD,
				'name' => 'category_video_5',
				'label' => _('Видео для категории # 5'),
				'value' => $form_data['category_video_5']
			),

			array(
				'typeField' => TFTEXTFIELD,
				'name' => 'category_video_6',
				'label' => _('Видео для категории # 6'),
				'value' => $form_data['category_video_6']
			),

			array(
				'typeField' => TFTEXTFIELD,
				'name' => 'category_video_7',
				'label' => _('Видео для категории # 7'),
				'value' => $form_data['category_video_7']
			),

			array(
				'typeField' => TFTEXTFIELD,
				'name' => 'category_video_8',
				'label' => _('Видео для категории # 8'),
				'value' => $form_data['category_video_8']
			),

			array(
				'typeField' => TFTEXTFIELD,
				'name' => 'category_video_9',
				'label' => _('Видео для категории # 9'),
				'value' => $form_data['category_video_9']
			),


			array(
				'typeField' => TFWYSIWYG,
				'name' => 'category_text',
				'label' => _('text для категории'),
				'value' => $form_data['category_text']
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