<?php 
	/**
	 * @copyright Alex Cruiser (cruiser.com.ua)
	 */
//exit;

	require_once ("../conf/core.cfg.php");
	require_once (LIB_PATH . "/Common.php");

	$dir = UPLOAD_PATH . '/';
	$files_array = scandir($dir);
	$xls_array = array();
	$last = 1;
	foreach($files_array as $files) {
		$path_parts = pathinfo($files);
		if($path_parts['extension'] == 'xls') {
			$xls_array[] = $files;
			
		}

	}
	if(is_array($xls_array)) {
		foreach($xls_array as $xls) {
			$new_last = filemtime(UPLOAD_PATH . '/' . $xls);
			echo $last . '<' . $new_last . '<br>';
			if($last < $new_last) {
				$last = $new_last;
				$xls_files = $xls;
				$i++;
			}
		}
	}
	echo "<a href='http://kare-design.com.ua/upload/" . $xls_files . "'>Скачать прайс</a>";
