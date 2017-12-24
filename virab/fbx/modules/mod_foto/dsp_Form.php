<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
.displayVisible {
	display:none;
}
</style>

<script>
	function searchElem(type, name) {
		var allInputs = $(":input");
		for (key in allInputs) {
			var value = allInputs[key];
			if (value.type == type && !value.name.indexOf(name, 0)) {
				return value;
			}
		}
	}
	
	function resizableImg() {
	
		var resize = searchElem('checkbox', 'resize');
		var auto_tmb = searchElem('checkbox', 'auto_tmb');
		
		if ($("input[name='resize']").attr("value") == 0) {
			$(auto_tmb).attr("disabled","disabled");
			($("fieldset[name='gr1']")).toggleClass("displayVisible");
			(($("fieldset[name='gr2']")).hasClass("displayVisible")) ? ($("fieldset[name='gr2']")).toggleClass("displayVisible") : null;
		} else {
			$(auto_tmb).removeAttr("disabled");
			($("fieldset[name='gr1']")).toggleClass("displayVisible");
			($("input[name='auto_tmb']").attr("value") == 1) ? ($("fieldset[name='gr1']")).toggleClass("displayVisible") : null;
		}
	}
	
	function auto_tmbImg() {
		($("input[name='orig_img']").attr("value") == 1) ? ($("fieldset[name='gr1']")).removeClass("displayVisible") : ($("fieldset[name='gr1']")).addClass("displayVisible");
	}
</script>
<?php 

	// Ошибки
	if ($attributes['error']) {
		$indata = unserialize($attributes['params']);
		$FORM_ERROR = $indata['str_error'];
		?><p class="cerr"><?=$FORM_ERROR?></p><?
		
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
		?><p class="cerr"><?=$ACL_ERROR?></p><?
		return;
	}
	
	// Заголовок страницы
?>
	<script type="text/javascript">
		<?php if ($type == 1):?>
			<?php if ($parent[0]['name']):?>
				setPagerTitle('', '<?=_('Добавление раздела дочернего к').' "'.$lng->Gettextlng($parent[0]['name']).'"'?>');
			<?php else: ?>
				setPagerTitle('', '<?=_('Добавление нового корневого раздела')?>');
			<?php endif; ?>
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
				'value' => getTeValueName($form_data['id_te_value']),
			));
		} else {
			$fblock->show(TFTEXTFIELD, array(
				'name' => 'value',
				'label' => _('Переменная'),
				'value' => 'foto' . substr($lng->NewId(), 0, 5),
				'required' => true
			));
		}
		$fblock->show(TFTEXTFIELDLNG, array(
			'name' => 'name',
			'label' => _('Название'),
			'value' => $form_data['name'],
			'required' => true
		));
		$fblock->show(TFTEXTAREALNG, array(
			'name' => 'description',
			'label' => _('Описание'),
			'value' => $form_data['description']
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
		
		
		// Параметры миниатюры
		$feildsetc2 = $dsp_helper->getFormContainer(array(
			'type'	=> TFFIELDSSET,
			
			'name'	=> 'gr2',
			'legend'=> 'Миниатюра'
		));		
		$feildsetc2->begin();
			$fblockk2 = $feildsetc2->getFieldBlock();
			
			$fblockk2->show(TFCHECKBOX, array(
				'name' => 'crop_tmb',
				'label' => _('Обрезать'),
				'value' => $form_data['crop_tmb']
			));
			$fblockk2->show(TFTEXTFIELD, array(
				'name' => 'width_tmb',
				'label' => _('Ширина'),
				'value' => $form_data['width_tmb'],
				'required' => true
			));
			$fblockk2->show(TFTEXTFIELD, array(
				'name' => 'height_tmb',
				'label' => _('Высота'),
				'value' => $form_data['height_tmb'],
				'required' => true
			));
			$fblockk2->show(TFTEXTFIELD, array(
				'name' => 'quality_tmb',
				'label' => _('Качество'),
				'value' => $form_data['quality_tmb'],
				'required' => true
			));
		$feildsetc2->end();
		
		// Параметры изменяемого изображения
		$feildsetc1 = $dsp_helper->getFormContainer(array(
			'type'	=> TFFIELDSSET,			
			'legend'=> 'Изображение'
		));		
		$feildsetc1->begin();
			$fblockk1 = $feildsetc1->getFieldBlock();
			
			$fblockk1->show(TFCHECKBOX, array(
				'name' => 'orig_img',
				'label' => _('Оригинал'),
				'value' => $form_data['orig_img'],
				'params' => array('onClick' => "auto_tmbImg();")
			));
			
			
			$feildsetc3 = $dsp_helper->getFormContainer(array(
			'type'	=> TFFIELDSSET,
			'name'	=> 'gr1',
			'legend'=> 'Параметры изображения'
			
			));		
			$feildsetc3->begin();
			
				$fblockk1->show(TFCHECKBOX, array(
					'name' => 'crop_img',
					'label' => _('Обрезать'),
					'value' => $form_data['crop_img']
				));
				$fblockk1->show(TFTEXTFIELD, array(
					'name' => 'width_img',
					'label' => _('Ширина'),
					'value' => $form_data['width_img'],
					'required' => true
				));
				$fblockk1->show(TFTEXTFIELD, array(
					'name' => 'height_img',
					'label' => _('Высота'),
					'value' => $form_data['height_img'],
					'required' => true
				));
				$fblockk1->show(TFTEXTFIELD, array(
					'name' => 'quality_img',
					'label' => _('Качество'),
					'value' => $form_data['quality_img'],
					'required' => true
				));
				
			$feildsetc3->end();
		$feildsetc1->end();
		
		

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