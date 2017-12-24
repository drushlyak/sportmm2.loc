<table id="xv_obj_datagrid" width=100%>
	  <tr>
		<th width="75%"><?=_("Объекты доступа")?></th>
	  </tr>

<?php

if (is_array($moduleSet)) {
		foreach ($moduleSet as $mod) {
			?>
			<tr x-primary="<?=$mod['id']?>">
			<td width="80%" style="padding-left:16px"><img style="cursor:pointer;" src="images/tree_folder_closed.gif" />
			<?=$lng->Gettextlng($mod['name'])?>
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