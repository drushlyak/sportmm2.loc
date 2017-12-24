<?php
	$id_photo = (int) $attributes['id_photo'];
	$new_text = $attributes['new_text'];

	if ($id_photo) {
		$new_text = $lng->Settextlng($new_text);

		// установим новый Alt-текст
		$db->query("
			UPDATE " . CFG_DBTBL_MOD_PRODUCT_PHOTO . "
			SET alt_text = ?
			WHERE id = ?
		", $new_text, $id_photo );
	} else {
		print 'false';
	}

	$mod_data = $db->get_row("SELECT * FROM " . CFG_DBTBL_MOD_PRODUCT_PHOTO . " WHERE id = ?", $id_photo );

	$fields = array(
		array(
			'typeField' => TFTEXTFIELDLNG,
			'name' => 'new_text',
			'label' => _('Alt-тег'),
			'value' => $mod_data['alt_text']
		),
		// Кнопка
		array(
			'typeField' => TFBUTTON,
			'type' => 'button',
			'value' => _('Сохранить'),
			'params' => array(
				'onClick' => "$(\"form.altForm\").get(0).submit();"
			)
		),
		// Скрытые поля данных по записи
		array(
			'typeField' => TFHIDDENFIELD,
			'name' => 'id_photo',
			'value' => $id_photo
		)
	);

	$dsp_helper->writeForm(array(
		'type' => TFFORM,
		'name' => 'data_form',
		'cls' => 'altForm',
		'method' => 'post',
		'action' => $_XFA['photo_alt_store'],
		'has_lng' => true,
		'has_file_field' => true,
		'addInOnSubmit' => 'return false;',
		'fields' => $fields
	));


?>
<script type="text/javascript">
	this.parent.$("#frm<?=$id_photo?>").css("visibility", "hidden");
</script>
