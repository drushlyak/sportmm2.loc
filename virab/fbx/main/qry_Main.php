<?php

  $rsTop = $db->get_all("
    SELECT 	id, 
			title, 
			url, 
			quick_help 
	FROM " . CFG_DBTBL_NAVIGATION . "
    WHERE (parent_id = '' OR parent_id = '0' OR parent_id IS NULL) 
		AND menu=1 
	ORDER BY ord 
  ");
  
?>