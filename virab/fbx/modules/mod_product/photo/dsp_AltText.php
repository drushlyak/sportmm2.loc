<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php

	// Ошибки
	$FORM_ERROR = $attributes['str_error'];
	if ($FORM_ERROR) {
		?><p class="cerr"><?=$FORM_ERROR?></p><?php

		$indata = unserialize($attributes['params']);

		// Подстановка введенных значений
		if (!is_array($mod_data)) {
			$mod_data = array();
		}
		if (is_array($indata)) {
			foreach ($indata as $key => $value) {
				$mod_data[$key] = $value;
			}
		}
	}
	// Доступ
	if (!$auth_in->isAllowed()) {
		echo "<p class=\"cerr\">".$ACL_ERROR."</p>";
		return;
	}

	// Pagertitle
	?>
	
<?php
	$fields = array(
		array(
			'typeField' => TFTEXTFIELDLNG,
			'name' => 'new_text',
			'label' => _('Alt-тег'),
			'value' => $mod_data['alt_text']
		),
		// Кнопка
		array(
			'typeField' => TFBUTTON,
			'type' => 'button',
			'value' => _('Сохранить'),
			'params' => array(
				'onClick' => "$(\"form.altForm\").get(0).submit();"
			)
		),
		// Скрытые поля данных по записи
		array(
			'typeField' => TFHIDDENFIELD,
			'name' => 'id_photo',
			'value' => $id_photo
		)
	);

	$dsp_helper->writeForm(array(
		'type' => TFFORM,
		'name' => 'data_form',
		'cls' => 'altForm',
		'method' => 'post',
		'action' => $_XFA['photo_alt_store'],
		'has_lng' => true,
		'has_file_field' => true,
		'addInOnSubmit' => 'return false;',
		'fields' => $fields
	));
