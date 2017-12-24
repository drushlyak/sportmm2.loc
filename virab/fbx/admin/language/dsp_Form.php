<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
	.languageForm label {
		width: 18em;
	}
</style>
<?php

	// Ошибки
	if ($attributes['error']) {

		$indata = unserialize($attributes['params']);
		$form_data_tmp = $_SESSION['formdata'][$indata['dataHash']];

		$form_data = $form_data_tmp['lng_construct'];

		$FORM_ERROR = (string) $_SESSION['formdata'][$indata['dataHash']]['str_error'];

		?><p class="cerr"><?=$FORM_ERROR?></p><?php
	}

	// Доступ
	if (!$auth_in->isAllowed()) {
		?><p class="cerr"><?=$ACL_ERROR?></p><?php
		return;
	}
	// PagerTitle
	if ($type === 2):
		?>
			<script type="text/javascript">
				setPagerTitle('.&nbsp;<?=_("Редактирование языковой конструкции с хэшем")?>&nbsp;&laquo;<?=prepareForShow($form_data['msgid'])?>&raquo;');
			</script>
		<?php
	endif;

	$hash_field = ($type !== 2) ?
		array(
			'typeField' => TFTEXTFIELD,
			'name' => 'msgid',
			'label' => _('Идентификационный хэш'),
			'value' => $lng->newId(),
			'required' => true,
			'params' => array(
				'readOnly' => 'true'
			)
		)
		:
		array(
			'typeField' => TFSIMPLETEXT,
			'label' => _('Идентификационный хэш'),
			'text' => $form_data['msgid']
		);

	$dsp_helper->writeForm(array(
		'type' => TFFORM,
		'name' => 'data_form',
		'cls' => '',
		'method' => 'post',
		'action' => $_XFA['store'],
		'has_file_field' => false,
		'has_lng' => true,
		'fields' => array(
			$hash_field,
			array(
				'typeField' => TFTEXTAREALNG,
				'name' => 'lng_construct',
				'label' => _('Значение'),
				'rows' => 10,
				'value' => $form_data
			),

			array(
				'typeField' => TFBUTTON,
				'type' => 'submit',
				'value' => _('Сохранить')
			),

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
