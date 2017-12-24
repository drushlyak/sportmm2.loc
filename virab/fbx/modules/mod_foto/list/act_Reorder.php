<?php
	$pos = (array) $attributes['pos'];
	$item = (int) $attributes['item'];
	$id_fotogr = (int) $attributes['id_fotogr'];

	// преобразуем массив
	$mass = array();
	foreach ($pos as $key => $val) {
		$mass[str_replace('photo_', '', $val)] = (intval($key) + 1);
	}

	// получим pos массив
	$poss = $db->get_hashtable("
		SELECT id, pos
		FROM " . CFG_DBTBL_MOD_EVENT_PHOTO . "
		WHERE id_fotogr = ?
		ORDER BY pos ASC
	", $id_fotogr );

	$old_pos = (int) $poss[$item];
	$new_pos = (int) $mass[$item];

	if ($old_pos > $new_pos) {
		// для всех pos >= new_pos сдвигаем вниз (+1)
		$db->query("
			UPDATE `" . CFG_DBTBL_MOD_EVENT_PHOTO . "`
			SET `pos`=`pos`+1
			WHERE `pos` >= ?
			  AND `pos` < ?
			  AND `id_fotogr` = ?
		", $new_pos
		 , $old_pos
		 , $id_fotogr );
		// записываем новую позицию
		$db->update(CFG_DBTBL_MOD_EVENT_PHOTO, array(
			'pos' => $new_pos
		), array('id' => $item));
	} elseif ($old_pos < $new_pos) {
		// для всех pos <= new_pos сдвигаем вверх (-1)
		$db->query("
			UPDATE `" . CFG_DBTBL_MOD_EVENT_PHOTO . "`
			SET `pos`=`pos`-1
			WHERE `pos` <= ?
			  AND `pos` > ?
			  AND `id_fotogr` = ?
		", $new_pos
		 , $old_pos
		 , $id_fotogr );
		// записываем новую позицию
		$db->update(CFG_DBTBL_MOD_EVENT_PHOTO, array(
			'pos' => $new_pos
		), array('id' => $item));
	}

	// проверим правильность массивов (вариант паралельных изменений и прочих формажеров)
	$poss_check = $db->get_hashtable("
		SELECT id, pos
		FROM " . CFG_DBTBL_MOD_EVENT_PHOTO . "
		WHERE id_fotogr = ?
		ORDER BY pos ASC
	", $id_fotogr );
	$corrr = true;
	foreach ($mass as $k => $v) {
		if ($v !== (int) $poss_check[$k]) {
			$corrr = false;
			break;
		}
	}
	if (!$corrr) {
		// Массив некорректен! Апдейтим втупую
		foreach ($mass as $idt => $pst) {
			// переордер
			$db->update(CFG_DBTBL_MOD_EVENT_PHOTO, array(
				'pos' => $pst
			), array('id' => $idt));
		}
	}

	print "true";

	die();
?>