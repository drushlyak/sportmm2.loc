<?php
	function getmicrotime() {
		list($usec, $sec) = explode(" ",microtime());
    		return ((float)$usec + (float)$sec);
	}
	$time_start = getmicrotime();
?>

<table id="xv_datagrid" width="100%">
	  <tr>
	    	<th class="checker">&nbsp;</th>
		<th width="60%"><?=_("Название")?></th>
		<th width="100%"><?=_("Права доступа")?>
		<div id="rightsline" align='left'>

			<?php
			if($privSet){
				foreach ($privSet as $privilege) {
					print "<span id='".$privilege['id']."'>[".$privilege['var']."]";
				}
			}
			?>
		</div>
		</th>
		<th width="20%"><?=_("Действия")?>&nbsp;</th>
	  </tr>
		<?php
		include ('dsp_Select.php');
	  	?>
</table>

<?php
	$time_end = getmicrotime();
	$time = $time_end - $time_start;
	print "<!-- time execute: $time sec. -->";
 ?>