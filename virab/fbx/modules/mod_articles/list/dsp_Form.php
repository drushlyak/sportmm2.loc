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
		$('#article_name_id').syncTranslit({destination: 'url_chpu', urlSeparator: '_'});
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
			setPagerTitle('', '<?=_('Добавление новой статьи')?>');
			<?php else: ?>
			setPagerTitle('.&nbsp;Редактирование статьи &laquo;<?=prepareForShow($mod_data['name'])?>&raquo;');
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
		'action' => $_XFA['articles_store'],
		'has_lng' => false,
		'has_file_field' => true,
		'addInOnSubmit' => 'return false;',
		'cls' => 'dataForm'
	));
	$fc->begin();
		$fblock = $fc->getFieldBlock();
		if($attributes['type'] == 2) {
			$fblock->show(TFSELECTDATETIME, array(
				'label' => _('Дата'),
				'value' => $mod_data['i_date']
			));
			$fblock->show(TFSELECTDATASET, array(
				'name' => 'id_category',
				'label' => 'Категория',
				'multiple' => false,
				'empty' => false,
				'dataSet' => $db->get_all("SELECT * FROM " . CFG_DBTBL_MOD_CATEGORY_ARTICLES),
				'selected' => array($mod_data['id_category'])
			));
		} else {			
			$fblock->show(TFSELECTDATETIME, array(
				'label' => _('Дата'),
				'value' => date("Y-m-d H:i:s")
			));
			$fblock->show(TFSELECTDATASET, array(
				'name' => 'id_category',
				'label' => 'Категория',
				'multiple' => false,
				'empty' => false,
				'dataSet' => $db->get_all("SELECT * FROM " . CFG_DBTBL_MOD_CATEGORY_ARTICLES),
				'selected' => array($attributes['id_category'])
			));
		}
		$fblock->show(TFTEXTFIELD, array(
			'id' => 'article_name_id',
			'name' => 'name',
			'label' => _('Название статьи'),
			'value' => $mod_data['name']
		));
		$fblock->show(TFTEXTFIELD, array(
			'id' => 'url_chpu',
			'name' => 'chpu',
			'label' => _('Адрес статьи (ЧПУ)'),
			'value' => $mod_data['chpu']
		));
		$fblock->show(TFTEXTFIELD, array(
			'name' => 'title',
			'label' => _('Альтернативный title'),
			'value' => $mod_data['title']
		));
		$fblock->show(TFTEXTFIELD, array(
			'id' => 'a_tags',
			'name' => 'a_tags',
			'label' => _('Теги'),
			'value' => $mod_data['a_tags']
		));
		$fblock->show(TFTEXTAREA, array(
			'name' => 'descr',
			'label' => _('Описание(description)'),
			'value' => $mod_data['descr']
		));
		$fblock->show(TFIMAGES, array(
			'label' => 'Фотография',
			'imgs' => array(
				array(
					'img' => $mod_data['main_foto_orig'],
					'tmb_img' => $mod_data['main_foto'],
					'title' => 'Фотография'
				)
			)
		));
		$fblock->show(TFFILEFIELD, array(
			'name' => 'main_foto',
			'label' => _('Основная фотография')
		));
		$fblock->show(TFTEXTAREA, array(
			'name' => 'anonce_text',
			'label' => _('Краткий анонс'),
			'value' => $mod_data['anonce_text']
		));
		$fblock->show(TFWYSIWYG, array(
			'name' => 'text',
			'label' => _('Содержание'),
			'value' => $mod_data['text']
		));
		
		
		//кнопка

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