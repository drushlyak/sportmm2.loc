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
			setPagerTitle('', '<?=_('Добавление новой номенклатурной единицы')?>');
			<?php else: ?>
			setPagerTitle('.&nbsp;Редактирование номенклатурной единицы &laquo;<?=prepareForShow($mod_data['name'])?>&raquo;');
			<?php endif; ?>
		</script>
	<?php

	$dsp_helper = new DspHelper();
	$fc = $dsp_helper->getFormContainer(array(
		'type' => TFFORM,
		'name' => 'data_form',
		'method' => 'post',
		'action' => $_XFA['store'],
		'has_file_field' => true,
		'has_lng' => false,
		'addInOnSubmit' => 'return false;',
		'cls' => 'dataForm'
	));
	$fc->begin();
		$fblock = $fc->getFieldBlock();
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'name',
			'label' => _('ФИО'),
			'value' => $mod_data['name']
		));
		$fblock->show(TFINTERACTIVEAUTOSUGGESTSELECTITEM, array(
			'name' => 'products',
			'label_block' => 'Выбор продуктов',
			'label' => _('Продукт (отображается только последний продукт)'),
			'label_search' => 'Поиск по названию',
			'searchvarname' => 'name',
			'backend' => SITE_URL . '/library/libcruiser4/ajax/find_product.php',
			'defDictTable' => CFG_DBTBL_MOD_PRODUCT,
			'value' => $mod_data['products']
		));
		$fblock->show(TFIMAGES, array(
			'label' => 'Фотография',
			'imgs' => array(
				array(
					'img' => $mod_data['photo'],
					'tmb_img' => $mod_data['tmb_photo'],
					'title' => 'Фотография'
				)
			)
		));
		$fblock->show(TFFILEFIELD, array(
			'name' => 'photo',
			'label' => _('Фотография вручения')
		));
		$fblock->show(TFSELECTDATE, array(
			'name' => 'add_date',
			'label' => _('Дата вручения'),
			'value' => $mod_data['add_date']
		));
		$fblock->show(TFCHECKBOX, array(
			'name' => 'is_view',
			'label' => _('Отображать?'),
			'value' => $mod_data['is_view']
		));

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