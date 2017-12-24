<div class="slider">
    <ul>
    <?php 
    	$brand_array = $db->get_all("SELECT * FROM " . CFG_DBTBL_MOD_BRANDS . " WHERE is_active = 1");
    	foreach($brand_array as $brand) {
    	 echo '<li><a href="/catalog/brand:' . $brand['id'] ."-".$brand['brand_cpu_url']. '/"><img src="' . $brand['main_foto'] . '" alt="' . $brand['name'] . '" class="action-img"><img src="' . $brand['main_foto_black'] . '" alt="' . $brand['name'] . '"></a></li>';
    	}
    ?>
    </ul>
</div>
<button class='prev'></button>
<button class='next'></button>