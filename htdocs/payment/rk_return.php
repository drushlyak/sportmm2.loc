<?php
	session_start();

	require_once ("../../conf/core.cfg.php");
	require_once (LIB_PATH . "/Common.php");
	require_once (LIB_PATH . "/ajax/JSON.php");
	require_once (LIB_PATH . "/class.phpmailer.php");

	$idClient = $_SESSION['id_site_client'];

	$mrh_pass2 = "secure_obra*doval_pass8743";

	$out_summ = $_REQUEST["OutSum"];
	$id_order = $_REQUEST["InvId"];
	$crc = $_REQUEST["SignatureValue"];

	$crc = strtoupper($crc); // force uppercase

	$my_crc = strtoupper(md5($out_summ . ':' . $id_order . ':' . $mrh_pass2));
	if (strtoupper($my_crc) == strtoupper($crc)) {
		// check order
		$db->query("UPDATE " . CFG_DBTBL_MOD_PAY_ORDER . " SET is_pay = 1 WHERE order_number = ?", $id_order);
	}
?>