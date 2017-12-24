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
			setPagerTitle('', '<?=_('Добавление нового брэнда')?>');
			<?php else: ?>
			setPagerTitle('.&nbsp;Редактирование брэнда &laquo;<?=prepareForShow($mod_data['phone'])?>&raquo;');
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
					'tmb_img' => $mod_data['main_foto'],
					'title' => 'Фотография'
				)
			)
		));
		$fblock->show(TFFILEFIELD, array(
			'name' => 'main_foto',
			'label' => _('Основная фотография')
		));
		$fblock->show(TFIMAGES, array(
			'label' => 'Ч\Б Фотография',
			'imgs' => array(
				array(
					'img' => $mod_data['main_foto_black'],
					'tmb_img' => $mod_data['main_foto_black'],
					'title' => 'Фотография'
				)
			)
		));
		$fblock->show(TFFILEFIELD, array(
			'name' => 'main_foto_black',
			'label' => _('Ч\Б фотография')
		));
		/*$fblock->show(TFTEXTFIELD, array(
			'name' => 'url',
			'label' => _('URL'),
			'value' => $mod_data['url']
		));*/
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'name',
			'label' => _('Title'),
			'value' => $mod_data['name']
		));
		$fblock->show(TFCHECKBOX, array(
			'name' => 'is_active',
			'label' => _('Показывать'),
			'value' => $mod_data['is_active']
		));

		$fblock->show(TFTEXTFIELD, array(  //дополнительное поле в форме вывода в админке
			'name' => 'brand_cpu_url',
			'label' => _('часть ЧПУ в адресе категории'),
			'value' => $mod_data['brand_cpu_url']
		));

		$fblock->show(TFTEXTFIELD, array(  //дополнительное поле в форме вывода в админке
			'name' => 'brand_title',
			'label' => _('title бренда'),
			'value' => $mod_data['brand_title']
		));
		$fblock->show(TFTEXTFIELD, array(  //дополнительное поле в форме вывода в админке
			'name' => 'brand_description',
			'label' => _('description бренда'),
			'value' => $mod_data['brand_description']
		));
		$fblock->show(TFTEXTFIELD, array(  //дополнительное поле в форме вывода в админке
			'name' => 'brand_keywords',
			'label' => _('keywords бренда'),
			'value' => $mod_data['brand_keywords']
		));
		$fblock->show(TFWYSIWYG, array(  //дополнительное поле в форме вывода в админке
			'name' => 'brand_text',
			'label' => _('text бренда'),
			'value' => $mod_data['brand_text']
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