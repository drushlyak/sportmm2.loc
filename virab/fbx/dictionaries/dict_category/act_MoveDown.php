<?php

	$id = (int) $attributes['id'];

	$moveData = $db->get_row("
		SELECT a.ord AS current,
				COALESCE(
					(
						SELECT ord
							FROM " . CFG_DBTBL_DICT_CATEGORY . "
						WHERE ord > a.ord
							AND id_main_category = a.id_main_category
						ORDER BY ord ASC
						LIMIT 1
					),
					0
				) AS replaced,
				a.id_main_category
			FROM " . CFG_DBTBL_DICT_CATEGORY . " a
		WHERE a.id = ?
	", $id );

	if ((int) $moveData['replaced']) {
		$db->query("
			UPDATE " . CFG_DBTBL_DICT_CATEGORY . "
				SET ord = IF(ord = ?, ?, ?)
			WHERE ord IN (?, ?)
				AND id_main_category = ?
		", $moveData['current']
		 , $moveData['replaced']
		 , $moveData['current']
		 , $moveData['current']
		 , $moveData['replaced']
		 , $moveData['id_main_category'] );
	}

	Location($_XFA['main'], 0);