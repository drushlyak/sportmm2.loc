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
			setPagerTitle('.&nbsp;Редактирование анонса &laquo;<?=prepareForShow($mod_data['name'])?>&raquo;');
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
			'name' => 'url',
			'label' => _('URL'),
			'value' => $mod_data['url']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'text',
			'label' => _('Название раздела'),
			'value' => $mod_data['text']
		));
		
		$fblock->show(TFSELECTPRODUCTWITHEDIT, array(
			'name' => 'products',
			'label_block' => 'Выбор состава заказа',
			'label' => _('Состав'),
			'label_search' => 'Поиск по названию',
			'searchvarname' => 'name',
			'backend' => SITE_URL . '/library/libcruiser4/ajax/find_product.php',
			'value' => $mod_data['products']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'page',
			'label' => _('Продуктов на странице'),
			'value' => $mod_data['page']
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