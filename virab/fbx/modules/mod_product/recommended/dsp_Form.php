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

	$dsp_helper = new DspHelper();
	$fc = $dsp_helper->getFormContainer(array(
		'type' => TFFORM,
		'name' => 'data_form',
		'method' => 'post',
		'action' => $_XFA['recommended_store'],
		'has_lng' => false
	));
	$fc->begin();
		$fblock = $fc->getFieldBlock();

		$fblock->show(TFINTERACTIVEAUTOSUGGESTSELECTITEM, array(
			'name' => 'products',
			'label_block' => 'Выбор продукта',
			'label' => _('Продукт'),
			'label_search' => 'Поиск по названию',
			'searchvarname' => 'name',
			'backend' => SITE_URL . '/library/libcruiser4/ajax/find_product.php',
			'defDictTable' => CFG_DBTBL_MOD_PRODUCT,
			'value' => array()
		));

		$fblock->show(TFBUTTON, array(
			'type' => 'submit',
			'value' => _('Сохранить')
		));

		// скрытые поля
		$fblock->show(TFHIDDENFIELD, array(
			'name' => 'id_product',
			'value' => $id_product
		));
	$fc->end();

?>