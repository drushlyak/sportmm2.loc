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
			setPagerTitle('', '<?=_('Добавление нового анонса')?>');
			<?php else: ?>
			setPagerTitle('.&nbsp;Редактирование анонса &laquo;<?=prepareForShow($mod_data['title'])?>&raquo;');
			<?php endif; ?>
		</script>
	<?php


	$dsp_helper = new DspHelper();
	$fc = $dsp_helper->getFormContainer(array(
		'type' => TFFORM,
		'name' => 'data_form',
		'method' => 'post',
		'has_file_field' => true,
		'action' => $_XFA['store'],
		'has_lng' => false
	));
	$fc->begin();
		$fblock = $fc->getFieldBlock();

		$fblock->show(TFIMAGES, array(
			'label' => 'Фотография',
			'imgs' => array(
				array(
					'img' => $mod_data['main_foto'],
					'tmb_img' => $mod_data['main_foto80'],
					'title' => 'Фотография'
				)
			)
		));
		$fblock->show(TFFILEFIELD, array(
			'name' => 'main_foto',
			'label' => _('Основная фотография')
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'url',
			'label' => _('URL'),
			'value' => $mod_data['url']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'title',
			'label' => _('Title'),
			'value' => $mod_data['title']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'address',
			'label' => _('Адрес'),
			'value' => $mod_data['address']
		));
		$fblock->show(TFTEXTAREA, array(
			'name' => 'text',
			'label' => _('Текст'),
			'value' => $mod_data['text']
		));
		$fblock->show(TFCHECKBOX, array(
			'name' => 'is_active',
			'label' => _('Показывать'),
			'value' => $mod_data['is_active']
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