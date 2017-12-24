<?php

$treeGrid = array(
	'backendUrl' => $_XFA['backend'],
	'cookieName' => 'rights_tree_'.$attr['resource_id']
);

$cookieName = $treeGrid['cookieName'];

if ($_COOKIE[$cookieName]) {
	$id = split(',', $_COOKIE[$cookieName]);
	$axis = 'child-or-self';
}

include ('qry_Select.php');

?>
