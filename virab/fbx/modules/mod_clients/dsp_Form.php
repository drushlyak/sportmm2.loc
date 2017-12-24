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
			setPagerTitle('', '<?=_('Добавление нового пользователя')?>');
			<?php else: ?>
			setPagerTitle('.&nbsp;Редактирование пользователя &laquo;<?=prepareForShow($mod_data['phone'])?>&raquo;');
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
		$fblock->show(TFTEXT, array(
			
			'label' => _('Дата регистрации'),
			'value' => $mod_data['date_reg']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'f_name',
			'label' => _('Фамилия'),
			'value' => $mod_data['f_name']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'i_name',
			'label' => _('Имя'),
			'value' => $mod_data['i_name']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'phone',
			'label' => _('Телефон <small>+7 (915) 1234567</small>'),
			'value' => $mod_data['phone']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'email',
			'label' => _('Email'),
			'value' => $mod_data['email']
		));
		$fblock->show(TFPASSWORDFIELD, array(
			'name' => 'password',
			'label' => _('Пароль'),
			'required' => true
		));
		$fblock->show(TFCHECKBOX, array(
			'name' => 'receive_mail',
			'label' => _('Получать сообщения'),
			'value' => $mod_data['receive_mail']
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