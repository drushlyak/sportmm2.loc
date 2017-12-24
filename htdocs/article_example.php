<? 

$sql = sql_placeholder("
	SELECT 
		id, typ, sys, file 
	FROM ".CFG_DBTBL_TE_VALUE." 
	WHERE name=?", 
	$this->teValue->getTeValueName()
);
$param_te_value = $db->get_row($sql);
$pgnm = ($this->c_this['page']['pagenum']) ? $this->c_this['page']['pagenum'] : 1;
$sql = sql_placeholder("
	SELECT 
		ag.id, 
		ag.count_per_page, 
		ag.count_per_list, 
		tcd1.code AS 'code_list', 
		tcd2.code AS 'code_text' 
		FROM ".CFG_DBTBL_MOD_AG_DATA." AS ag, 
			".CFG_DBTBL_TE_CONTTREE." AS tcs1, 
			".CFG_DBTBL_TE_CONTTREE." AS tcs2, 
			".CFG_DBTBL_TE_CONTDATA." AS tcd1, 
			".CFG_DBTBL_TE_CONTDATA." AS tcd2 
		WHERE ag.id_te_value=? 
			AND tcs1.id=ag.code_list 
			AND tcd1.id=tcs1.data_id 
			AND tcs2.id=ag.code_text 
			AND tcd2.id=tcs2.data_id
		",824
);
$rsArticleGr = $db->query($sql);
if($rsArticleGr->num_rows){
	$artcgr = $rsArticleGr->fetch_assoc();
	$sql = sql_placeholder(
		"SELECT 
			ad.id, 
			DATE_FORMAT(ad.idate, '%d-%m-%Y %H:%i') AS idate, 
			ad.id_article_group, 
			ad.anounce, 
			ad.name, 
			ad.text, 
			ad.source, 
			ad.image_s, 
			ad.image_x 
		FROM ".CFG_DBTBL_MOD_ARTICLE." AS ad 
		WHERE ad.id_article_group=3 
		ORDER BY ord DESC
	");
	$rsArticle = $db->query($sql);
// Построим список статей для текущего языка
	for($i = 1; $i <= $rsArticle->num_rows; $i++){
		if($artc = $rsArticle->fetch_assoc()){
			if($lng->Gettextlng($artc['name'])){
				$artc_complet[] = $artc;
			}else{ 
				continue;
			}
		}
	}
// Если статей больше чем заданно разделе то с формируем маршрутизатор страниц
	if($artcgr['count_per_page'] < count($artc_complet)){
// Определим номер, кол-во страниц и построим маршрутизатор
		$count_pg = ceil((count($artc_complet) - $artcgr['count_per_page'])/$artcgr['count_per_page'])+1;
	}else{ 
		$str_router = "";
	}
	$sql = sql_placeholder("
			SELECT 
				tv.typ as typ, 
				tv.sys as sys, 
				tv.file as file,
				ti.class as class_name,
				ti.file as inc_file, tt.id as typ_id,
				ti.sys_var as sys_var ,
				module.var AS mod_var
			FROM ".CFG_DBTBL_TE_VALUE." as tv
			JOIN  ".CFG_DBTBL_TE_TYPE." as tt
				ON (tv.typ = tt.te_value)
			JOIN ".CFG_DBTBL_TE_INCLUDES." as ti 
				ON (ti.typ_id = tt.id)
			LEFT JOIN ".CFG_DBTBL_MODULE." AS module
				ON (ti.mod_id = module.id)
			WHERE tv.id=?", 824
	);
	$data = $this->_db->get_row($sql);
	
	require_once(MODULE_PATH."/".$data['mod_var']."/".$data['inc_file']);
	$teData = new $data['class_name']($this->teValue->getTeValueName(), $data);
	for($i=1; $i <= $artcgr['count_per_list']; $i++){
		if($artc_complet[$i-1]['text']){
			if($i > ($pgnm-1)*$artcgr['count_per_page'] && $i <= (($pgnm-1)*$artcgr['count_per_page']+$artcgr['count_per_page'])){
				$code_in .= "<li>".$artcgr['code_list']."</li>";
				$fragment = $lng->Gettextlng($artc_complet[$i-1]['text']);
				$code_in = $teData->makeCode($code_in, $artc_complet[$i-1], 824, $fragment, "/news/"); 
			}
		}
	}
}
$code = "<ul>".$code_in."</ul>";
echo $code;

?>