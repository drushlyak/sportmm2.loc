<?php 
	$brand_array = $db->get_all("SELECT * FROM " . CFG_DBTBL_MOD_ANONCE . " WHERE is_active = 1");
	if(is_array($brand_array)) {
?>
<div id="container">
    <div id="slides">
    <?php     	
    	foreach($brand_array as $brand) {
    		echo '<img src="' . $brand['main_foto'] . '" width="630" height="250" alt="' . $brand['name'] . '">';
    	}
    ?>
    </div>
</div>
<?php 
	}
?>