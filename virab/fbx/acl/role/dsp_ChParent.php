<style style="text/css">
	.displayNotVisible {
		display: none;
	}
</style>

<script type="text/javascript">
	function showHideParentsBlock(field) {
		var match_clss = field.className.match(/(checkbox_[\w]{3})/im);
		if (match_clss != null) {
			var val = $("#" + match_clss[1]).val();
		}
		$(".grrolesParents")[(val == 1) ? "removeClass" : "addClass"]('displayNotVisible')
	}
</script>
<?php

	$id	= (int) $attributes['id'];
	// сформируем массив значений селектора
	$selector_mass = array(); $tree_txt_array = array();
	$selected_parents = array();
	if (is_array($nodeSet)) {
		foreach ( $nodeSet as $node ) {
			$indent = "";
			for ( $i = 1, $count = $node['level']; $i < $count; $i++ ) {
				$indent .= "&nbsp;&nbsp;&nbsp;";
			}

			if ($node['id'] != $id) {
				$selector_mass[$node['id']] = $lng->Gettextlng($node['name']);
				$tree_txt_array[$node['id']] = "" . $indent . $lng->Gettextlng($node['name']);
			} else {
				$selected_parents[] = $node['parent_id'];
				$parent_flag = (bool) $node['parent_id'];
				$tree_txt_array[$node['id']] = "<b>" . $indent . $lng->Gettextlng($node['name']) . "</b>&nbsp;<span style='font-size:14px;'>&larr;</span>&nbsp;(текущая роль)";
			}
		}
	}

	// Доступ
	if (!$auth_in->isAllowed()) {
		?><p class="cerr"><?=$ACL_ERROR?></p><?
		return;
	}

	$fc = $dsp_helper->getFormContainer(array(
		'type' => TFFORM,
		'name' => 'data_form',
		'method' => 'post',
		'action' => $_XFA['ch_parent_store'],
		'has_file_field' => false,
		'has_lng' => false
	));
	$fc->begin();
		$fblock = $fc->getFieldBlock();

		$fblock->show(TFSIMPLETEXT, array(
			'label' => _('Дерево ролей'),
			'text' => implode('<br />', $tree_txt_array)
		));

		$fblock->show(TFCHECKBOX, array(
			'name' => 'is_main_role',
			'label' => _('Роль верхнего уровня'),
			'value' => (int) !$parent_flag,
			'params' => array('onClick' => "showHideParentsBlock(this);")
		));

		$feildsetc2 = $dsp_helper->getFormContainer(array(
			'type'	=> TFFIELDSSET,
			'cls'	=> ($parent_flag ? '' : 'displayNotVisible') . " grrolesParents",
			'name'	=> 'grroles',
			'legend'=> _('Выбор родительского роли')
		));
		$feildsetc2->begin();
			$fblockk2 = $feildsetc2->getFieldBlock();

			$fblockk2->show(TFSELECT, array(
				'name' => 'parent_id',
				'label' => _('Родительская роль'),
				'options' => $selector_mass,
				'empty' => false,
				'selected' => $selected_parents
			));
		$feildsetc2->end();

		$fblock->show(TFBUTTON, array(
			'type' => 'submit',
			'value' => _('Сохранить')
		));

		// скрытые поля
		$fblock->show(TFHIDDENFIELD, array(
			'name' => 'id',
			'value' => $attributes['id']
		));
	$fc->end();