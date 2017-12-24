<?php
    $id_photo = (int) $attributes['id_photo'];
    $new_text = stripslashes(trim($attributes['new_text']));

    if($id_photo) {
        $db->query("
            UPDATE " . CFG_DBTBL_MOD_PHOTO . "
                SET alt_text = ?
                WHERE id = ?
            ", $new_text, $id_photo );
        print 'true';
    } else {
        print 'false';
    }
