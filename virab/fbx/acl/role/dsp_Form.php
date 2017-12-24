<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php

	// Ошибки
	if ($attributes['error']) {
		$indata = unserialize($attributes['params']);
		$FORM_ERROR = $indata['str_error'];
		?><p class="cerr"><?=$FORM_ERROR?></p><?
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
		?><p class="cerr"><?=$ACL_ERROR?></p><?
		return;
	}
	// PagerTitle
	?>
		<script type="text/javascript">
			<?php if ($type == 1):?>
			setPagerTitle('', '<?=_('Добавление роли')?>');
			<?php else: ?>
			setPagerTitle('.&nbsp;<?=_("Редактирование роли")?> &laquo;<?=prepareForShow($lng->Gettextlng($form_data['name']))?>&raquo;');
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
			'label' => _('Наименование'),
			'value' => $form_data['name']
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