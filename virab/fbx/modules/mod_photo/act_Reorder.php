<?php
    if(isset($_REQUEST['id_str'])) {
        $pos = 0;
        $id_str = explode('|',$_REQUEST['id_str']);

        while(list($id_key, $id_value) = each($id_str)) {
            $id_value = intval($id_value);
            if($id_value != 0) {
                $pos++;
                $db->query("
                    UPDATE " . CFG_DBTBL_MOD_PHOTO . "
                    SET pos = ?
                    WHERE id = ?
                ", $pos, $id_value );
            }
        }
    }
    
	print "true";

	die();
?>