<?php
	$id_photo = (int) $attributes['id'];

	if ($id_photo) {
		// получим данные по фотографии
		$ph = $db->get_row(sql_placeholder(
			"SELECT * FROM " . CFG_DBTBL_MOD_PHOTO . " WHERE id = ?", $id_photo
		));
		clearImageOperation(array(
			'path_clean' => array($ph['path'], $ph['tmb_path'], $ph['tmb_path89'], $ph['path_orig'])
		));

		$db->query("
			UPDATE `" . CFG_DBTBL_MOD_PHOTO . "`
			SET `pos`=`pos`-1
			WHERE `pos` > ?
			  AND `id_album` = ?
		", $ph['pos']
		 , $ph['id_album'] );

		// удалим фотографию
		$db->delete(CFG_DBTBL_MOD_PHOTO, array(
			'id' => $id_photo
		));
		print 'true';
	} else {
		print 'false';
	}

	die();
?>