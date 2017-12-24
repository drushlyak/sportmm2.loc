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

	// Заголовок страницы
?>
	<script type="text/javascript">
		<?php if ($type === 1):?>
			setPagerTitle('', '<?=_("Добавление нового языка")?>');
		<?php else: ?>
			setPagerTitle('.&nbsp;<?=_("Редактирование языка")?> &laquo;<?=prepareForShow($lng->Gettextlng($form_data['name']))?>&raquo;');
		<?php endif; ?>
	</script>

<?php

	$fc = $dsp_helper->getFormContainer(array(
		'type' => TFFORM,
		'name' => 'data_form',
		'method' => 'post',
		'action' => $_XFA['store'],
		'has_file_field' => false,
		'has_lng' => true
	));
	$fc->begin();
		$fblock = $fc->getFieldBlock();
		$fblock->show(TFTEXTFIELDLNG, array(
			'name' => 'name',
			'label' => _('Название'),
			'value' => $form_data['name'],
			'required' => true
		));

		$fblock->show(TFTEXTFIELD, array(
			'name' => 'ind_name',
			'label' => _('Сигнатура'),
			'value' => $form_data['ind_name'],
			'required' => true
		));

		$fblock->show(TFTEXTFIELD, array(
			'name' => 'locale',
			'label' => _('Локаль языка'),
			'value' => $form_data['locale'],
			'required' => true
		));

		$fblock->show(TFSELECTFLAG, array(
			'name' => 'flag',
			'label' => _('Выбор флага'),
			'value' => $form_data['flag'],
			'required' => true
		));

		$fblock->show(TFCHECKBOX, array(
			'name' => 'deflt',
			'label' => _('Язык по-умолчанию'),
			'value' => $form_data['deflt']
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
	$fc->end();

?>
