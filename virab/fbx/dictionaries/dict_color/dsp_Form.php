<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php

	// Ошибки
	$FORM_ERROR = $attributes['str_error'];
	if ($FORM_ERROR) {
		?><p class="cerr"><?=$FORM_ERROR?></p><?php
	}
	// Доступ
	if (!$auth_in->isAllowed()) {
		echo "<p class=\"cerr\">".$ACL_ERROR."</p>";
		return;
	}
	// Pagertitle
	?>
		<script type="text/javascript">
			<?php if ($type == 1):?>
			setPagerTitle('', '<?=_('Добавление нового цвета')?>');
			<?php else: ?>
			setPagerTitle('.&nbsp;Редактирование цвета &laquo;<?=prepareForShow($dict_data['name'])?>&raquo;');
			<?php endif; ?>
		</script>
	<?php

	$dsp_helper = new DspHelper();
	$fc = $dsp_helper->getFormContainer(array(
		'type' => TFFORM,
		'name' => 'data_form',
		'method' => 'post',
		'action' => $_XFA['store'],
		'has_lng' => false
	));
	$fc->begin();
		$fblock = $fc->getFieldBlock();
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'name',
			'label' => _('Наименование цвета'),
			'value' => $dict_data['name']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'name_many',
			'label' => _('Наименование цвета, множественное число'),
			'value' => $dict_data['name_many']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'color',
			'label' => _('Код цвета'),
			'value' => $dict_data['color']
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