<script type="text/javascript" src="js/jquery.synctranslit.js"></script>
<script type="text/javascript" src="js/tag_sag.js"></script>
<script type="text/javascript">
	$(function() {
		$('#a_tags').tagSuggest({tags: ["<?=$tags?>"]});
	});
</script>
<?php
	if ($attributes['type'] == 1) {
?>
<script type="text/javascript">
	$(function() {
		$('#product_name_id').syncTranslit({destination: 'product_chpu', urlSeparator: '_'});
	});
</script>
<?php } ?>
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
			setPagerTitle('', '<?=_('Добавление нового продукта')?>');
			<?php else: ?>
			setPagerTitle('.&nbsp;Редактирование продукта &laquo;<?=prepareForShow($mod_data['name'])?>&raquo;');
			<?php endif; ?>
		</script>
		<style>
			.hgt300 {
				height:300px;
			}
			span.tagMatches {
				padding:5px 0;
				display:block;
			}
			span.tagMatches span {
				color:#fff;
				padding:2px;
				margin-right:5px;
				cursor:pointer;
				background-color:#0000ab;
				font-size:16px;
			}
		</style>
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
		if($type == 1) {
			$fblock->show(TFTEXTFIELD, array(
			'name' => 'i_dat',
			'label' => _('Дата добавления'),
			'value' => date('Y-m-d')
			));
		} else {
			$fblock->show(TFTEXTFIELD, array(
			'name' => 'i_date',
			'label' => _('Дата добавления'),
			'value' => $mod_data['i_date']
			));
		}
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'article',
			'label' => _('Артикул'),
			'value' => $mod_data['article']
		));
		$fblock->show(TFTEXTFIELD, array(
			'id' => 'product_name_id',
			'name' => 'name',
			'label' => _('Наименование продукта'),
			'value' => $mod_data['name'],
			'params' => array('maxlength' => 45),
			'required' => true
		));
		$fblock->show(TFTEXTFIELD, array(
			'id' => 'product_chpu',
			'name' => 'chpu',
			'label' => _('Адрес продукта (раздел URL)'),
			'value' => $mod_data['chpu']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'alternative_title',
			'label' => _('Альтернативный title'),
			'value' => $mod_data['alternative_title']
		));
		$fblock->show(TFTEXTFIELD, array(
			'id' => 'a_tags',
			'name' => 'a_tags',
			'label' => _('Теги'),
			'value' => $mod_data['a_tags']
		));
		$fblock->show(TFHIDDENFIELD, array(
			'name' => 'num_stock',
			'label' => _('Кол-во на складе'),
			'value' => $mod_data['num_stock']
		));
		
		$fblock->show(TFIMAGES, array(
			'label' => 'Фотография',
			'imgs' => array(
				array(
					'img' => $mod_data['main_foto340'],
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
			'name' => 'alt_text',
			'label' => _('Alt текст'),
			'value' => $mod_data['alt_text']
		));
		
		$fblock->show(TFWYSIWYG, array(
			'name' => 'description',
			'label' => _('Описание'),
			'value' => $mod_data['description'],
			'cls' => 'hgt300'
		));
		$fblock->show(TFWYSIWYG, array(
			'name' => 'description_table',
			'label' => _('Описание в таблице'),
			'value' => $mod_data['description_table'],
			'cls' => 'hgt300'
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'num_stock',
			'label' => _('Остаток на складе, шт.'),
			'value' => $mod_data['num_stock']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'cost_excess',
			'label' => _('Цена за штуку, руб.'),
			'value' => $mod_data['cost_excess']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'discount',
			'label' => _('Скидка, %'),
			'value' => $mod_data['discount']
		));

		$fblock->show(TFTEXTFIELD, array(
			'name' => 'producer',
			'label' => _('Производитель'),
			'value' => $mod_data['producer']
		));
		$fblock->show(TFSELECTDATASET, array(
			'name' => 'id_producer',
			'label' => 'Производитель',
			'multiple' => false,
			'empty' => true,
			'dataSet' => $db->get_all("SELECT id,name FROM " . CFG_DBTBL_MOD_BRANDS),
			'params' => array('size' => '1'),
			'selected' => array($mod_data['id_producer'])
		));
		$fblock->show(TFINTERACTIVESELECTITEM, array(
			'name' => 'categories',
			'label_block' => 'Выбор категорий продукта',
			'label' => _('Категории'),
			'label_search' => 'Выбор',
			'without_output_modification' => true,
			'options' => $category_array,
			'value' => $mod_data['categories']
		));


		$fblock->show(TFTEXTFIELD, array(
			'id' => 'ordr',
			'name' => 'ordr',
			'label' => _('Порядковый номер'),
			'value' => $mod_data['ordr']
		));
		$fblock->show(TFCHECKBOX, array(
			'name' => 'is_new',
			'label' => _('Новый товар'),
			'value' => $mod_data['is_new']
		));
		$fblock->show(TFCHECKBOX, array(
			'name' => 'is_promo',
			'label' => _('Акционный'),
			'value' => $mod_data['is_promo']
		));
		$fblock->show(TFCHECKBOX, array(
			'name' => 'is_hit',
			'label' => _('Хит продаж'),
			'value' => $mod_data['is_hit']
		));
		$fblock->show(TFCHECKBOX, array(
			'name' => 'is_view_main',
			'label' => _('Отображать на главной?'),
			'value' => $mod_data['is_view_main']
		));
		$fblock->show(TFCHECKBOX, array(
			'name' => 'is_active',
			'label' => _('Отображать?'),
			'value' => $mod_data['is_active']
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