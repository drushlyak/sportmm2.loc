<?php	

	function createDisplayAcl($nodeSet) {
		global $db, $top_id, $res;
		
		if ($nodeSet) {
			foreach($nodeSet as &$node) {
				if ($node['id'] == $top_id) {
					$node['name'] = $res['name'];
					continue;
				}
				/*
				$sql = sql_placeholder("
					SELECT * FROM " . MODULE . " 
						WHERE res_id=?", $node['data_id']
				);
				$data = $db->get_row($sql);
				if (is_array($data)) {
					$node['name'] = $data['name'];
				}
				*/
			}
		}
		return true;	
	}

?>