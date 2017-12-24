<table id="xv_ug_datagrid" width="100%">
	  <tr>
	<th width="75%"><?=_("Группы / Пользователи")?></th>
	  </tr>
	<tr x-primary="0" x-has-children="1" style="display: none">
		<td width="80%" style="padding-left:0px"><img src="images/tree_folder_closed.gif" />
		<?=_("Группы и пользователи")?>
		</td>
		</tr>
<?php

if ($roleSet) {
	foreach($roleSet as $role){
?>
		<tr x-primary="<?=$role['id']?>"
		x-type = "<?=$role['has_children']?>">
			<td width="80%" style="padding-left:<?=16*($role['level']+1)?>px"><img src="
		<?php
		    	if ($role['has_children'] == 1) {
		    		print 'images/group.gif';
		    	} else {
		    		print 'images/group_empty.gif';
		    }
		   ?>" />
				<?=$lng->Gettextlng($role['name'])?>
		</td>
		</tr>
<?php
	}
} else {
?>
	<tr>
	  <td> <?=_("... пусто")?> </td>
	</tr>
<?php
}

?>
</table>