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
			setPagerTitle('', '<?=_('Добавление нового типа доставки')?>');
			<?php else: ?>
			setPagerTitle('.&nbsp;Редактирование типа доставки &laquo;<?=prepareForShow($dict_data['name'])?>&raquo;');
			<?php endif; ?>
		</script>
	<?php

	$dsp_helper = new DspHelper();
	$fc = $dsp_helper->getFormContainer(array(
		'type' => TFFORM,
		'name' => 'data_form',
		'method' => 'post',
		'action' => $_XFA['type_delivery_store'],
		'has_lng' => false
	));
	$fc->begin();
		$fblock = $fc->getFieldBlock();
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'name',
			'label' => _('Наименование типа'),
			'value' => $dict_data['name']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'start_time',
			'label' => _('Начало доставки'),
			'value' => $dict_data['start_time']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'end_time',
			'label' => _('Окончание доставки'),
			'value' => $dict_data['end_time']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'start_for_today',
			'label' => _('Минимальное время'),
			'value' => $dict_data['start_for_today']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'interval_hours',
			'label' => _('Интервал доставки'),
			'value' => $dict_data['interval_hours']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'price',
			'label' => _('Стоимость доставки'),
			'value' => $dict_data['price']
		));
		$fblock->show(TFTEXTAREA, array(
			'name' => 'description',
			'label' => _('Окписание'),
			'value' => $dict_data['description']
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
			'name' => 'id_city',
			'value' => $id_city
		));
	$fc->end();

?>