<? 
$sql = sql_placeholder("
	SELECT 
		fg.id, 
		fg.count_per_page, 
		fg.name, 
		fg.description, 
		tcd.code AS 'code' 
	FROM ".CFG_DBTBL_MOD_FOTO_GRDATA." AS fg, 
		".CFG_DBTBL_TE_CONTTREE." AS tcs, 
		".CFG_DBTBL_TE_CONTDATA." AS tcd 
	WHERE fg.id_te_value=? 
		AND tcs.id=fg.code 
		AND tcd.id=tcs.data_id 
	", 848
);
$rsFotoGr = $db->query($sql);
if($rsFotoGr->num_rows){
	$fotogr = $rsFotoGr->fetch_assoc();
	$sql = sql_placeholder("
		SELECT 
			fd.id, 
			DATE_FORMAT(fd.idate, '%d-%m-%Y"._("г.")."') AS idate, 
			fd.id_fotogr, 
			fd.name, 
			fd.description, 
			fd.url, 
			fd.exten 
		FROM ".CFG_DBTBL_MOD_FOTO." AS fd 
		WHERE id_fotogr=? 
		ORDER BY fd.ord", 
		$fotogr['id']
	);
	$rsFoto = $db->get_all($sql);
// Построим список фотографий
	if(count($rsFoto)<5){
		$count = count($rsFoto); 
	}else{
		$count = 5;
	}
	$res_id = array();
	for($i = 1; $i <= $count; $i++){
		for(;;){
			$id = rand(0, count($rsFoto)-1);
			if(!in_array($id, $res_id)){
				$res_id[] = $id;
				break;	
			}
		}
		if($rsFoto[$id]){
			$foto_complet[] = $rsFoto[$id];
		}
	}
	$sql = sql_placeholder("
		SELECT 
			tv.typ as typ, 
			tv.sys as sys, 
			tv.file as file,
			ti.class as class_name,
			ti.file as inc_file, tt.id as typ_id,
			ti.sys_var as sys_var,
			module.var AS mod_var
		FROM ".CFG_DBTBL_TE_VALUE." as tv
		JOIN  ".CFG_DBTBL_TE_TYPE." as tt
			ON (tv.typ = tt.te_value)
		JOIN ".CFG_DBTBL_TE_INCLUDES." as ti 
			ON (ti.typ_id = tt.id)
		LEFT JOIN ".CFG_DBTBL_MODULE." AS module
			ON (ti.mod_id = module.id)
		WHERE tv.id=?", 848
	);
	$data = $this->_db->get_row($sql);
	require_once(MODULE_PATH."/".$data['mod_var']."/".$data['inc_file']);
	$teData = new $data['class_name']($this->teValue->getTeValueName(), $data);
	$str_router = "";
	for($i=1; $i <= count($foto_complet); $i++){
		$code_in .= "<li>".$fotogr['code']."</li>";
		$code_in = $teData->makeCode($code_in, $foto_complet[$i-1], $param_te_value['id'], getAdrPage($_this['page']['addr']));
	}
}
$code = "<ul>".$code_in."</ul>";
print $code;

?>