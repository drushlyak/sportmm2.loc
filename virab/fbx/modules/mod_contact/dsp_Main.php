<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<form name="form1" method="post" action="<?=$_XFA['delete']?>">
<script language="JavaScript" src="js/list.js"></script>
<?php
if($attributes['str_error']){
	echo "<p class=\"cerr\">".$attributes['str_error']."</p>";
}
// Проверка доступа
if(!$auth_in->isAllowed()){
	echo "<p class=\"cerr\">".$ACL_ERROR."</p>";
	return;
}
?>
<input type="hidden" name="id1" value="<?=$id1?>">
<input type="hidden" name="id2" value="<?=$id2?>">
  <table width=100% border=0 cellpadding=0 cellspacing=0>
				<tr>
					<td colspan=5 width=100% style="padding: 7px;">
						<table width=100% border=0 cellpadding=0 cellspacing=0>
							<tr>
								<td style="text-align: left;"><?=leftPageStr()?></td>
								<td style="text-align: center;"><?=centerPageStr($_XFA['main'])?></td>
								<td style="text-align: right;"><?=rightPageStr($_XFA['main'])?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan=5 bgcolor=#E6E6E6 width=100%><img src="images/spacer.gif" width=1 height=2 border=0></td>
				</tr>
				<tr>
					<td width=0% height=20 bgcolor=#F9F9F9 style="text-align: center;" valign=middle title="<?=_("Отметить / снять отметку со всех элементов");?>"><input type="checkbox" name="allbox" value="1" onclick="CheckAll('form1');" /></td>
					<td width=40% height=20 bgcolor=#F9F9F9 style="text-align: center;" valign=middle class="headTable"><?=_("Автор");?></td>
					<td width=20% height=20 bgcolor=#F9F9F9 style="text-align: center;" valign=middle class="headTable"><?=_("Телефон");?></td>
					<td width=20% height=20 bgcolor=#F9F9F9 style="text-align: center;" valign=middle class="headTable"><?=_("Дата");?></td>
					<td width=20% height=20 bgcolor=#F9F9F9 style="text-align: center;" valign=middle class="headTable"><?=_("Действия");?></td>
				</tr>
				<tr>
					<td colspan=5 bgcolor=#FF0000 width=100%><img src="images/spacer.gif" width=1 height=2 border=0></td>
				</tr>
				<?php
						if (is_array($nodeSet)) {
								$currentLevel = 1;
								$lvl[1][0] = 0;
								$lvl[1][1] = 1;

								foreach ($nodeSet as $node) {
				?>
				<tr id="d<?=$lvl[$node['level']][0]?>_<?=$lvl[$node['level']][1]?>" onmouseout=delite(this); onmouseover=hilite(this); rw="<?=$lvl[$node['level']][0]?>_<?=$lvl[$node['level']][1]?>"<? if ($lvl[$node['level']][0]) { echo " style=\"display: None;\"";} ?>>
						<td valign="middle" width="20"><input type="checkbox" name="did[]" value="<?=$node['id']?>"></td>
						<td height=19 style="text-align: center;" valign=middle><?=$node['fio']?></td>
						<td height=19 style="text-align: center;" valign=middle><?=$node['phone']?></td>
						<td height=19 style="text-align: center;" valign=middle><?=$node['idate']?></td>
						<td style="text-align: center;">
						<?php if($auth_in->aclCheck($resourceId, VIEW)): ?>
							<a href="<?=sprintf($_XFA['view'], $node['id'])?>"><img src="images/but/ed.gif" width=17 height=16 border=0 title="<?=_("Просмотреть сообщение");?>"></a> &nbsp;
						<?php endif ?>
						<?php if($auth_in->aclCheck($resourceId, DELETE)): ?>
							<a href="<?=sprintf($_XFA['delete'], $node['id'])?>" onClick="return confirm('<?=_("Вы уверены, что хотите удалить данное сообщение?");?>')"><img src="images/but/del.gif" width=17 height=16 border=0 title="<?=_("Удалить сообщение");?>"></a> &nbsp;
    	  				<?php endif ?>
						</td>
				</tr>
				<tr id="f<?=$lvl[$node['level']][0]?>_<?=$lvl[$node['level']][1]?>"<? if ($lvl[$node['level']][0]) { echo " style=\"display: None;\"";} ?>>
						<td colspan=5 bgcolor=#E6E6E6 width=100%><img src="images/spacer.gif" width=1 height=2 border=0></td>
				</tr>
		<?php
										$lvl[$node['level']][1]++;
								}
						}
		?>
				<tr>
					<td colspan=5 width=100% style="padding: 7px;">
						<table width=100% border=0 cellpadding=0 cellspacing=0>
							<tr>
								<td style="text-align: left;"><?=leftPageStr()?></td>
								<td style="text-align: center;"><?=centerPageStr($_XFA['main'])?></td>
								<td style="text-align: right;"><?=rightPageStr($_XFA['main'])?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
						<td colspan=5 width=100% height=23 valign=bottom style="padding-bottom: 2px; padding-left: 20px;"><span style="color: #FF0000; text-align: left;"><b>Действия</b></span></td>
				</tr>
				<tr>
						<td colspan=5 bgcolor=#FF0000 width=100%><img src="images/spacer.gif" width=1 height=2 border=0></td>
				</tr>
				<tr>
						<td colspan=5 width=100% height=20 valign=middle style="padding: 10px;">
							<table border=0 cellpadding=0 cellspacing=0 width=100%>
								<tr>
									<td width=100%>
										<?php if($auth_in->aclCheck($resourceId, DELETE)): ?>
										<table border=0 cellpadding=0 cellspacing=0 width=100%>
											<tr>
												<td style="padding: 5px;"><img src="images/dwn_but.gif" border=0 alt=""></td>
												<td bgcolor=#737373><img src="images/spacer.gif" width=1 height=100% border=0 alt=""></td>
												<td width=100% style="padding-left: 10px;"><a href="#" onClick="if (confirm('<?=_("Вы уверены, что хотите удалить выбранные сообщения?");?>')) {form1.submit();} else {return false;}"><?=_("Удалить выбранные сообщения");?></a></td>
											</tr>
										</table>
										<?php endif ?>
									</td>
								</tr>
							</table>
						</td>
				</tr>
  </table>
</form>