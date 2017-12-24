<?php

header('Content-type: text/javascript; charset=utf-8');

$libPath = "./virab";


function build ($path)
{
	static $stack = array();
		
	if (!is_dir($path)) return;
	
	$name = basename($path);
	print "new function () {\n";
	array_push($stack, $name);
	
	$classpath = array_merge(array("window"), $stack);
	print file_get_contents($path."/__package__.js") . "\n\n";
	print join(".",$classpath) . " = " . $name . ";\n";		

	foreach (glob($path."/*.js") as $file) {
		if ($file == $path."/__package__.js" || $file == $path."/__package_c.js") continue;
		$objName = preg_replace("/\.js$/", "", basename($file));
		print file_get_contents($file) . "\n\n";
		print sprintf("%s.%s = %s;\n", join(".", $classpath), $objName, $objName);
	}
	
	foreach (glob($path."/*", GLOB_ONLYDIR) as $dir) {
		build($dir);
	}
	
	array_pop($stack);
	print "}\n\n";
}

function compress ($code)
{
	$code = preg_replace("/\s+/", " ", $code);
	$code = preg_replace("/\/\*\*[^\*\/]+\*\//i", "", $code);
	return $code;
}

build($libPath);
?>