	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script type="text/javascript" language="JavaScript">
	  var elem_array = Array();
	  elem_array[1] = 'd1';
	  elem_array[2] = 'd2';
	<?php include "../../../js/language.js.php"; ?>
	</script>

	<?php
	$FORM_ERROR = $attributes['str_error'];
	if($FORM_ERROR){
		?><p class="cerr"><?=$FORM_ERROR?></p><?php
	}
	// Проверка доступа
	if(!$auth_in->isAllowed()){
		echo "<p class=\"cerr\">".$ACL_ERROR."</p>";
		return;
	}

	?>
	<form name="form1" method="post" action="<?=$_XFA['store']?>">
	<center><br><b><?=_("Перевод:")?>&nbsp;&nbsp;<span id="Translater"></span></b><br><br></center>
	<script language="javascript">
	  chngLng(lng_now);
	</script>

	<input type="hidden" name="typ" value="<?=$typ?>">
	<input type="hidden" name="id" value="<?=$id?>">
	  <table width="100%"  border="0" cellpadding="0" cellspacing="4">
	    <tr>
	      <td width="20%"><?=_("Название привилегии доступа:");?></td>
	      <td>
	      	<?php $lng->textField(1, 'name', $privilege['name'], array('size' => 50)); ?>
	      </td>
	    </tr>
	    <tr>
	      <td><?=_("Наименование константы:");?></td>
	      <td>
			<input type="text" value="<?=$privilege['var']?>" size="50" name="var" />
	      </td>
	    </tr>
	    <tr>
	      <td valign="top">&nbsp;</td>
	      <td><input name="btnS" type="submit" id="btnS" value="<?=_("Сохранить");?>"></td>
	    </tr>
	  </table>
	</form>