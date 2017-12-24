<?php

$id = intval($attributes['id']);
$contanerSet = array_map("intval", $attributes['contaner_id']);
$FORM_ERROR = "";
if(!$FORM_ERROR){
	if(is_array($contanerSet)){
		foreach($contanerSet as $cnt_id => $cont){
			if($cont == 0){
				$sql = "
					DELETE
					FROM ".CFG_DBTBL_TE_SELECTIVE_TMPL."
					WHERE
						selective_id=?
						AND map_id=?
				";
				$db->query($sql, $cnt_id, $id);
				continue;
			}
			$sql = "
				SELECT id
				FROM ".CFG_DBTBL_TE_SELECTIVE_TMPL."
				WHERE
					selective_id=?
					AND map_id=?
			";
			$exist_id = $db->get_one($sql, $cnt_id, $id);
			if($exist_id){
				$sql = "
					UPDATE ".CFG_DBTBL_TE_SELECTIVE_TMPL."
					SET
						contaner_id = ?
					WHERE id = ?
				";
				$db->query($sql, $cont, $exist_id);
			}else{
				$sql = "
					INSERT
					INTO ".CFG_DBTBL_TE_SELECTIVE_TMPL."
					SET
						contaner_id = ?,
						map_id = ?,
						selective_id = ?
				";
				$db->query($sql, $cont, $id, $cnt_id);
			}
		}
	}
	Location($_XFA['main']);
}else{
	include("qry_Selective.php");
	include("dsp_Selective.php");
}

?>