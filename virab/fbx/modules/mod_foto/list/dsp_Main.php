<link rel="stylesheet" type="text/css" href="./css/photo_gallery.css">

<script type="text/javascript">
	BackEndURLS = {
		photo_reorder		: "<?=$_XFA["photo_reorder"]?>",
		photo_delete 		: "<?=$_XFA["photo_delete"]?>",
		photo_toprivate 	: "<?=$_XFA["photo_toprivate"]?>",
		photo_topublic	 	: "<?=$_XFA["photo_topublic"]?>",

		data_to_send		: {
			id_product		: <?=(int) $attributes['id_product']?>
		}
	};
</script>
<script type="text/javascript" src="./js/photo_gallery.js"></script>

	<?php
		// Ошибки
		if ($attributes['error']) {
			$indata = unserialize($attributes['params']);
			$FORM_ERROR = $indata['str_error'];
			?><p class="cerr"><?=$FORM_ERROR?></p><?php
		}

		$dsp_helper = new DspHelper();
		$fc = $dsp_helper->getFormContainer(array(
			'type' => TFFORM,
			'name' => 'data_form',
			'method' => 'post',
			'action' => $_XFA['photo_store'],
			'has_file_field' => true,
			'has_lng' => false
		));
		$fc->begin();
			$fblock = $fc->getFieldBlock();

			
			$fblock->show(TFFILEFIELD, array(
				'name' => 'photo',
				'label' => _('Добавить фотографию')
			));
			$fblock->show(TFTEXTFIELD, array(
				'name' => 'alt_text',
				'label' => _('Alt текст'),
				'value' => $mod_data['alt_text']
			));

			$fblock->show(TFBUTTON, array(
				'type' => 'submit',
				'value' => _('Сохранить'),
				'cls' => 'button_in'
			));

			// скрытые поля
			$fblock->show(TFHIDDENFIELD, array(
				'name' => 'id_fotogr',
				'value' => (int) $attributes['id_fotogr']
			));
		$fc->end();
		
		
		
	?>
	<div class="ttitle">Управление фотографиями</div>

	<div class="ui-title">Фотографии (идут в порядке отображения в галерее):</div>
	<div class="ui-widget ui-clearfix privat">
		<ul id="privat" class="gallery ui-reset ui-clearfix">
			<?php if (is_array($dataSet)):
					foreach ($dataSet as $photo): 
					$photo['path'] = $orig ? $photo['path_orig'] : $photo['path']; ?>
						<li id="photo_<?=$photo['id']?>">
							<h5 class="ui-widget-header">&nbsp;</h5>
							<a href="<?=$photo['path']?>" title="" rel="lightbox-privateGallery">
								<img src="<?=$photo['tmb_path']?>" alt="<?=$photo['alt_text']?>" />
								<a href="#" class="icon del" title="Удалить фотографию" onClick="if (confirm('Удалить фотографию?')) { deletePhoto(<?=$photo['id']?>); return false; } else {return false;}"></a>
							</a>
						</li>
			<?php endforeach;
				endif;
			?>
		</ul>
	</div>
