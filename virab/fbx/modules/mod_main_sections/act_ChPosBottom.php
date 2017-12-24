<?php

	$id		 = (int) $attributes['id'];
	
	$item = $db->get_row("SELECT * FROM " . CFG_DBTBL_MOD_MAIN_SECTIONS . " WHERE id = ? LIMIT 1", $id);
	$next = $db->get_row("SELECT * FROM " . CFG_DBTBL_MOD_MAIN_SECTIONS . " WHERE ord > ? ORDER BY ord LIMIT 1", $item["ord"]);

	if(is_array($next)){
	 $rsNodes = $db->query("UPDATE " . CFG_DBTBL_MOD_MAIN_SECTIONS . " SET ord = ? WHERE id = ? ", $item["ord"], $next["id"]);
	 $rsNodes = $db->query("UPDATE " . CFG_DBTBL_MOD_MAIN_SECTIONS . " SET ord = ? WHERE id = ?", $next["ord"], $item["id"]);
	}

	Location($_XFA['main'], 0);
?>