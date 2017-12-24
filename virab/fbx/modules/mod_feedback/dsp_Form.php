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
	
	// Заголовок страницы
?>
	<script type="text/javascript">
		<?php if ($type == 1):?>
		setPagerTitle('', '<?=_('Добавление раздела')?>');
		<?php else: ?>
		setPagerTitle('.&nbsp;Редактирование раздела &laquo;<?=prepareForShow($lng->Gettextlng($form_data['name']))?>&raquo;');
		<?php endif; ?>
	</script>
<?php	
	
	$dsp_helper = new DspHelper();
	$fc = $dsp_helper->getFormContainer(array(
		'type' => TFFORM,
		'name' => 'data_form',
		'method' => 'post',
		'action' => $_XFA['cat_store'],
		'has_file_field' => false,
		'has_lng' => true
	));
	$fc->begin();
		$fblock = $fc->getFieldBlock();
		if($form_data['id_te_value']){
			$fblock->show(TFTEXT, array(
				'label' => _('Переменная'),
				'value' => getTeValueName($category['id_te_value']),
			));
		} else {
			$fblock->show(TFTEXTFIELD, array(
				'name' => 'value',
				'label' => _('Переменная'),
				'value' => 'faq' . substr($lng->NewId(), 0, 5),
				'required' => true
			));
		}
		$fblock->show(TFTEXTFIELDLNG, array(
			'name' => 'name',
			'label' => _('Название'),
			'value' => $form_data['name'],
			'required' => true
		));
		$fblock->show(TFSELECTDATASETLNG, array(
			'name' => 'code',
			'label' => 'Код для отображения',
			'dataSet' => $codeSet,
			'selected' => array($form_data['code']),
			'required' => true
		));		
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'count_per_page',
			'label' => _('Кол-во элементов'),
			'value' => $form_data['count_per_page'],
			'required' => true
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
		if($form_data['id_te_value']){
			$fblock->show(TFHIDDENFIELD, array(
				'name' => 'value',
				'value' => getTeValueName($form_data['id_te_value'])
			));	
		}
	$fc->end();

?>