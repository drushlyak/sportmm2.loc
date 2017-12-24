<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php 

	// Ошибки
	if ($attributes['error']) {
		$indata = unserialize($attributes['params']);
		$FORM_ERROR = $indata['str_error'];
		?><p class="cerr"><?=$FORM_ERROR?></p><?php
		// Подстановка введенных значений
		if (!is_array($form_data)) {
			$form_data = array();
		}
		if (is_array($indata)) {
			foreach ($indata as $key => $value) {
				$form_data[$key] = $value;	
			}
		}
	}
	// Доступ
	if (!$auth_in->isAllowed()) {
		?><p class="cerr"><?=$ACL_ERROR?></p><?php
		return;
	}
	// PagerTitle
	?>
		<script type="text/javascript">
			<?php if ($type == 1):?>
			setPagerTitle('', '<?=_('Добавление отзыва')?>');
			<?php else: ?>
			setPagerTitle('.&nbsp;Редактирование отзыва &laquo;<?=prepareForShow($lng->Gettextlng($form_data['name']))?>&raquo;');
			<?php endif; ?>
		</script>

	<?php	
	
	$dsp_helper = new DspHelper();
	$fc = $dsp_helper->getFormContainer(array(
		'type' => TFFORM,
		'name' => 'data_form',
		'method' => 'post',
		'action' => $_XFA['store'],
		'has_file_field' => false,
		'has_lng' => true,
		'cls' => 'dataForm',
		'addInOnSubmit' => 'return false;'
	));
	$fc->begin();
		$fblock = $fc->getFieldBlock();
		$fblock->show(TFTEXTAREALNG, array(
			'name' => 'text',
			'label' => _('Отзыв'),
			'value' => $form_data['text'],
			'required' => true
		));
		$fblock->show(TFTEXTFIELDLNG, array(
			'name' => 'author_name',
			'label' => _('Автор'),
			'value' => $form_data['author_name'],
			'required' => true
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'author_mail',
			'label' => _('Телефон автора'),
			'value' => $form_data['author_mail'],
			'required' => true
		));

		$fblock->show(TFAUTOSUGGESTFIELD, array(
			'label' => 'Продукт',
			'name' => 'id_product',
			'backend' => SITE_URL . '/library/libcruiser4/ajax/find_product.php',
			'varname' => 'name',
			'value' => ((int) $form_data['id_product'] ? array(
				'id' => $form_data['id_product'],
				'name' => $form_data['product_name']
			) : "")
		));

		$fblock->show(TFCHECKBOX, array(
			'name' => 'priz_active',
			'label' => _('Отображение'),
			'value' => $form_data['priz_active']
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
			'name' => 'id_feedback',
			'value' => $attributes['id_feedback']
		));	
		$fblock->show(TFHIDDENFIELD, array(
			'name' => 'id',
			'value' => $attributes['id']
		));	
	$fc->end();

?>