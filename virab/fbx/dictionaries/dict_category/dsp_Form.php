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
			setPagerTitle('.&nbsp;Редактирование категории &laquo;<?=prepareForShow($dict_data['name'])?>&raquo;');
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
			'label' => _('Наименование категории'),
			'value' => $dict_data['name']
		));
		
		$fblock->show(TFSELECTDATASET, array(
			'name' => 'main_category',
			'label' => 'Основная категория',
			'multiple' => false,
			'empty' => false,
			'dataSet' => $db->get_all("SELECT * FROM " . CFG_DBTBL_DICT_MAIN_CATEGORY),
			'params' => array('size' => '1'),
			'selected' => array($dict_data['id_main_category'])
		));

			$fblock->show(TFTEXTFIELD, array(  //дополнительное поле в форме вывода в админке
			'name' => 'subcategory_cpu_url',
			'label' => _('часть ЧПУ в адресе подкатегории'),
			'value' => $dict_data['subcategory_cpu_url']
		));


			$fblock->show(TFTEXTFIELD, array(  //дополнительное поле в форме вывода в админке
			'name' => 'subcategory_title',
			'label' => _('title подкатегории'),
			'value' => $dict_data['subcategory_title']
		));
		
			$fblock->show(TFTEXTFIELD, array(  //дополнительное поле в форме вывода в админке
			'name' => 'subcategory_description',
			'label' => _('description подкатегории'),
			'value' => $dict_data['subcategory_description']
		));
		
			$fblock->show(TFTEXTFIELD, array(  //дополнительное поле в форме вывода в админке
			'name' => 'subcategory_keywords',
			'label' => _('keywords подкатегории'),
			'value' => $dict_data['subcategory_keywords']
		));

		$fblock->show(TFTEXTFIELD, array(  //дополнительное поле в форме вывода в админке
			'name' => 'subcategory_titleh1',
			'label' => _('title H1 для подкатегории'),
			'value' => $dict_data['subcategory_titleh1']
		));

		$fblock->show(TFTEXTFIELD, array(  //дополнительное поле в форме вывода в админке
		'name' => 'subcategory_image_1',
		'label' => _('Картинка для категории:'),
		'value' => $dict_data['subcategory_image_1']
	));

	
		$fblock->show(TFTEXTFIELD, array(  //дополнительное поле в форме вывода в админке
			'name' => 'subcategory_video_1',
			'label' => _('Видео для подкатегории # 1'),
			'value' => $dict_data['subcategory_video_1']
		));

		$fblock->show(TFTEXTFIELD, array(  //дополнительное поле в форме вывода в админке
			'name' => 'subcategory_video_2',
			'label' => _('Видео для подкатегории # 2'),
			'value' => $dict_data['subcategory_video_2']
		));

		$fblock->show(TFTEXTFIELD, array(  //дополнительное поле в форме вывода в админке
			'name' => 'subcategory_video_3',
			'label' => _('Видео для подкатегории # 3'),
			'value' => $dict_data['subcategory_video_3']
		));

		$fblock->show(TFTEXTFIELD, array(  //дополнительное поле в форме вывода в админке
			'name' => 'subcategory_video_4',
			'label' => _('Видео для подкатегории # 4'),
			'value' => $dict_data['subcategory_video_4']
		));

		$fblock->show(TFTEXTFIELD, array(  //дополнительное поле в форме вывода в админке
			'name' => 'subcategory_video_5',
			'label' => _('Видео для подкатегории # 5'),
			'value' => $dict_data['subcategory_video_5']
		));

		$fblock->show(TFTEXTFIELD, array(  //дополнительное поле в форме вывода в админке
			'name' => 'subcategory_video_6',
			'label' => _('Видео для подкатегории # 6'),
			'value' => $dict_data['subcategory_video_6']
		));

		$fblock->show(TFTEXTFIELD, array(  //дополнительное поле в форме вывода в админке
			'name' => 'subcategory_video_7',
			'label' => _('Видео для подкатегории # 7'),
			'value' => $dict_data['subcategory_video_7']
		));

		$fblock->show(TFTEXTFIELD, array(  //дополнительное поле в форме вывода в админке
			'name' => 'subcategory_video_8',
			'label' => _('Видео для подкатегории # 8'),
			'value' => $dict_data['subcategory_video_8']
		));

		$fblock->show(TFTEXTFIELD, array(  //дополнительное поле в форме вывода в админке
			'name' => 'subcategory_video_9',
			'label' => _('Видео для подкатегории # 9'),
			'value' => $dict_data['subcategory_video_9']
		));

		$fblock->show(TFWYSIWYG, array(  //дополнительное поле в форме вывода в админке
				'name' => 'subcategory_text',
				'label' => _('text подкатегории'),
				'value' => $dict_data['subcategory_text']
			));


		
		
	
		/*
		$fblock->show(TFSELECTDATASET, array(
			'name' => 'flag',
			'label' => 'Тип категория',
			'multiple' => false,
			'empty' => true,
			'dataSet' => $dictionaryCategory,
			'selected' => array ( $dict_data['flag'] )
		));
		*/
		$fblock->show(TFCHECKBOX, array(
			'name' => 'is_menu',
			'label' => _('Отображение пункта меню'),
			'value' => $dict_data['is_menu']
		));
        $fblock->show(TFCHECKBOX, array(
            'name' => 'is_menu_mc',
            'label' => _('Отображение основной категории'),
            'value' => $dict_data['is_menu_mc']
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