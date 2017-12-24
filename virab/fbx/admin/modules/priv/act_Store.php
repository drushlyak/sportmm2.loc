<?php

	$priv_id = intval($attributes['priv_id']);
	$id_mod = intval($attributes['id_mod']);

	if (!$FORM_ERROR) {
		// добавим правило доступа для модуля
		$sql = sql_placeholder("
			INSERT INTO " . CFG_DBTBL_ACL_MOD_PRIV . "
				SET module_id = ?
				  , privilege_id = ?
		", $id_mod, $priv_id);
		$res = $db->query($sql);

		Location(sprintf($_XFA['main_priv'], $id_mod), 0);
	}else{
		include("qry_Form.php");
		include("dsp_Form.php");
	}

?>