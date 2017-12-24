<?php 
	require_once ("../conf/core.cfg.php");
	require_once (LIB_PATH . "/Common.php");
	
	$dir = UPLOAD_PATH . '/2013_01/';
	$files_array = scandir($dir);
	$xls_array = array();
	foreach($files_array as $files) {
		$path_parts = pathinfo($files);
		if($path_parts['extension'] == 'jpg' && filesize($dir.$files) > 1000000) {
			
		$xls_array[] = $path_parts['basename'];
			
		}
	
	}

	print_r($xls_array);