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
			setPagerTitle('', '<?=_('Добавление новой категории')?>');
			<?php else: ?>
			setPagerTitle('.&nbsp;Редактирование категории &laquo;<?=prepareForShow($mod_data['title'])?>&raquo;');
			<?php endif; ?>
		</script>
	<?php

	$dsp_helper = new DspHelper();
	$fc = $dsp_helper->getFormContainer(array(
		'type' => TFFORM,
		'name' => 'data_form',
		'method' => 'post',
		'action' => $_XFA['store'],
		'has_lng' => false,
		'addInOnSubmit' => 'return false;',
		'cls' => 'dataForm'
	));
	$fc->begin();
		$fblock = $fc->getFieldBlock();
		
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'name',
			'label' => _('Название категории'),
			'value' => $mod_data['name']
		));
		
		
		
		//кнопка

		$fblock->show(TFBUTTON, array(
			'type' => 'button',
			'value' => _('Сохранить'),
			'params' => array(
				'onClick' => "$(\"form.dataForm\").get(0).submit();"
			)
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