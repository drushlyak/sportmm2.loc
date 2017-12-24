<?php

$Fusebox["layoutDir"] = "";

$ajaxFuses = array(
	'rule.backend'
	
);

$specFuses = array(
	'mod_product.photo_alt',
	'mod_product.photo_alt_store',
	'mod_photo.photo_alt',
	'mod_photo.photo_alt_store'
);

if (in_array($Fusebox['targetCircuit'] . '.' . $Fusebox['fuseaction'], $ajaxFuses)) {
	$Fusebox['layoutFile'] = 'AjaxLayout.php';
	return;
}

if (in_array($Fusebox['targetCircuit'] . '.' . $Fusebox['fuseaction'], $specFuses)) {
	$Fusebox["layoutFile"] = "DefaultLayout_WOM.php";
} else {
	$Fusebox["layoutFile"] = "DefaultLayout.php";
}

?>