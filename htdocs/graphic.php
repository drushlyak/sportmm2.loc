<?php 
	require_once ("../conf/core.cfg.php");
    require_once (LIB_PATH . "/Common.php");
    
    $colors = array(
    	1=>array('r'=>255, 'g'=>0, 'b'=>0),		
		2=>array('r'=>228, 'g'=>0, 'b'=>23),		
		3=>array('r'=>201, 'g'=>0, 'b'=>47),		
		4=>array('r'=>174, 'g'=>0, 'b'=>66),		
		5=>array('r'=>147, 'g'=>0, 'b'=>93),		
		6=>array('r'=>120, 'g'=>0, 'b'=>120),		
		7=>array('r'=>93, 'g'=>0, 'b'=>147),		
		8=>array('r'=>66, 'g'=>0, 'b'=>174),		
		9=>array('r'=>47, 'g'=>0, 'b'=>201),		
		10=>array('r'=>23, 'g'=>0, 'b'=>228),		
		11=>array('r'=>0, 'g'=>0, 'b'=>255)
	);
    
    $img_array = $db->get_all("SELECT number, exist FROM " . CFG_DBTBL_SHUTTER_TEST . " WHERE exist = 1 OR exist = 0");
    $height = "10";
    $width = "1500";
    //print_r();
?>
    <div style="width: <?=$width?>px;">
<?php 
	$i = 1;
	asort($img_array);
	$ii=0;
	$num_color = 1;
    foreach($img_array as $img) {
    	$ii++;
    	if ($img['exist'] == 1) {
    		$num_color+=10;
    	}
    	if ($ii == 1) {
  			echo '<div cifer="' . $num_color . '" style="height: ' . $height . 'px; width: 1px; background: rgb(' . $colors[$num_color]['r'] . ', ' . $colors[$num_color]['g'] . ', ' . $colors[$num_color]['b'] . '); float: left;" title="' . $img['number']  . '"></div>';
    		$ii= 0;
    		$num_color = 1;
    		$i++;
	    	if ($i % $width == 0) {
	    		echo '<div style="clear: both; height: 3px;"></div>';
	    	}
    	}
    }
?>
		
	</div>