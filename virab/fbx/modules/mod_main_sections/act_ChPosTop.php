<?php

	$id		 = (int) $attributes['id'];

	$item = $db->get_row("SELECT * FROM " . CFG_DBTBL_MOD_MAIN_SECTIONS . " WHERE id = ? LIMIT 1", $id);
	$prev = $db->get_row("SELECT * FROM " . CFG_DBTBL_MOD_MAIN_SECTIONS . " WHERE ord < ? ORDER BY ord DESC LIMIT 1", $item["ord"]);

	if(is_array($prev)){
	 $rsNodes = $db->query("UPDATE " . CFG_DBTBL_MOD_MAIN_SECTIONS . " SET ord = ? WHERE id = ?", $item["ord"], $prev["id"]);
	 $rsNodes = $db->query("UPDATE " . CFG_DBTBL_MOD_MAIN_SECTIONS . " SET ord = ? WHERE id = ?", $prev["ord"], $item["id"]);
	}

	Location($_XFA['main'], 0);
?>