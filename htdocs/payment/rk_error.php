<?php
	session_start();

	require_once ("../../conf/core.cfg.php");
	require_once (LIB_PATH . "/Common.php");
	require_once (LIB_PATH . "/ajax/JSON.php");
	require_once (LIB_PATH . "/class.phpmailer.php");

	$idClient = $_SESSION['id_site_client'];

	$_SESSION['current_message_header'] = "Ошибка пополнения";
	$_SESSION['current_message'] = "Ошибка пополнения! Повторите, пожалуйста, операцию.";

	header("Location: " . SITE_URL . "/info_page/");

?>