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
			setPagerTitle('', '<?=_('Добавление новой записи')?>');
			<?php else: ?>
			setPagerTitle('.&nbsp;Редактирование записи &laquo;<?=prepareForShow($mod_data['name'])?>&raquo;');
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
		'has_file_field' => false,
		'has_lng' => false
	));
	$fc->begin();
		$fblock = $fc->getFieldBlock();
        if($mod_data['id_te_value']){
            $fblock->show(TFTEXT, array(
                'label' => _('Переменная'),
                'value' => getTeValueName($mod_data['id_te_value']),
            ));
        } else {
            $fblock->show(TFTEXTFIELD, array(
                'name' => 'value',
                'label' => _('Переменная'),
                'value' => 'foto' . substr($lng->NewId(), 0, 5),
                'required' => true
            ));
        }

		$fblock->show(TFTEXTFIELD, array(
			'name' => 'name',
			'label' => _('Наименование'),
			'value' => $mod_data['name'],
			'required' => true
		));
        $fblock->show(TFTEXTFIELD, array(
            'name' => 'count_per_page',
            'label' => _('Кол-во на страницу'),
            'value' => $mod_data['count_per_page']
        ));

			$feildsetc_c = $dsp_helper->getFormContainer(array(
				'type' => TFFIELDSSET,
				'legend' => 'Фотографии'
			));
			$feildsetc_c->begin();
				$fblock_c = $feildsetc_c->getFieldBlock();

                if ($id && ($type == 2)) {
    				$fblock_c->show(TFMUIMAGES, array(
                        'photo_reorder' => $_XFA['photo_reorder'],
                        'photo_delete' => $_XFA['photo_delete'],
                        'photo_toprivate' => $_XFA['photo_toprivate'],
                        'photo_topublic' => $_XFA['photo_topublic'],
                        'photo_alt_store' => $_XFA['photo_alt_store'],
    					'id_album' => $attributes['id']
    				));
                } else {
                    $fblock->show(TFTEXT, array(
                        'label' => '',
                        'value' => 'Сохраните и зайдите в раздел еще раз для добавления фото!'
                    ));
                }
			$feildsetc_c->end();

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
        if($mod_data['id_te_value']){
            $fblock->show(TFHIDDENFIELD, array(
                'name' => 'value',
                'value' => getTeValueName($mod_data['id_te_value'])
            )); 
        }
	$fc->end();
