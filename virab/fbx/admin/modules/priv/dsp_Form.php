	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<?php if (!empty($FORM_ERROR)): ?>
		<p class="cerr"><?=$FORM_ERROR?></p>
	<?php endif; ?>
	
	<?php if (!$auth_in->isAllowed()): ?>
		<p class="cerr"><?=$ACL_ERROR?></p>
	<?php return; endif; ?>	
	
	<form name="add_form" method="post" action="<?=$_XFA['store_priv']?>">
		<input type="hidden" name="id_mod" value="<?=$id_mod?>">
		<input type="hidden" name="acl" value="1">
		<br />
		<table> 
			<tr> 
				<td><?=_("Привилегия для добавления:");?></td> 
				<td>
				<?php 
					$resArray = array();
					if (is_array($privSet)) {
						foreach($privSet as $priv) {
							$name = $lng->Gettextlng($priv['name']) . " [" . $priv['var'] . "]";
							$resArray[$priv['id']] = $name;
						}
					}
					html_select($resArray, array(), 'priv_id', false);
				?>			
				</td> 
			</tr> 
			<tr> 
			<td valign="top">&nbsp;</td> 
				<td><input name="btnS" type="submit" id="btnS" value="<?=_("Добавить");?>"></td> 
			</tr> 
		  </table>
	</form>
