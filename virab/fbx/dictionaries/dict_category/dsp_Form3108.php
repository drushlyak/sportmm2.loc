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