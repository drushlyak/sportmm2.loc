<?php
	session_start();

	$_SESSION[$_REQUEST['table_id']]['sort_field'] = $_REQUEST['field'];
	$_SESSION[$_REQUEST['table_id']]['sort_dir'] = $_REQUEST['dir'];
?>