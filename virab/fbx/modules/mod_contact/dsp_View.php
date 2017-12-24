<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
if($FORM_ERROR){
	?><p class="cerr"><?=$FORM_ERROR?></p><?php
}
// Проверка доступа
if(!$auth_in->isAllowed()){
	echo "<p class=\"cerr\">".$ACL_ERROR."</p>";
	return;
}
?>
<table width="100%"  border="0" cellpadding="0" cellspacing="4">
    <tr>
      <td width="20%"><?=_("Автор");?></td>
      <td>
      	<?=$category['fio']?>
      </td>
    </tr>
    <tr>
      <td width="20%"><?=_("Телефон");?></td>
      <td>
      	<?=$category['phone']?>
      </td>
    </tr>
    <tr>
      <td width="20%"><?=_("E-mail");?></td>
      <td>
      	<?=$category['email']?>
      </td>
    </tr>

    <tr>
      <td width="20%"><?=_("Текст вопроса");?></td>
      <td>
      	<div style='width:100%;min-height:300px;border:1px solid red;'>
      	<?=$category['question']?>
      	</div>
      </td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
      <td><input name="btnS" type="button" id="btnS" value="<?=_("Назад");?>" onClick="window.location.href='<?=$_XFA['main']?>'"></td>
    </tr>
  </table>
</form>