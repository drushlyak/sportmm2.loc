<?php
//======================================================
class teFotoTemplate extends teTemplate
{
	
//-----------------------------------------
	function __construct()
	{
		$args = func_get_args();
		parent::__construct($args[0], $args[1]);
		return true;
	}	
//-----------------------------------------
	public function getCode()
	{
		$fotoTree = new NSTree(
			CFG_DBTBL_MOD_FOTO_GRTREE,
			CFG_DBTBL_MOD_FOTO_GRDATA, 
			array(
				'id'      => TREE_STRUCT_ID,
				'data_id' => TREE_STRUCT_DATA_ID,
				'left'    => TREE_STRUCT_LEFT,
				'right'   => TREE_STRUCT_RIGHT,
				'level'   => TREE_STRUCT_LEVEL
			)
		);
		$this->code = '';
		
		// Определим номер текущей страницы
		$pgnm = ($this->c_this['page'][$this->param_te_value['id']]['pagenum']) ? $this->c_this['page'][$this->param_te_value['id']]['pagenum'] : 1;
		
		// Выберем по шаблонной переменной группу фотографий
		$sql = sql_placeholder("
			SELECT fs.id, fg.count_per_page, fg.name, fg.description, tcd.code AS 'code' 
			FROM ".CFG_DBTBL_MOD_FOTO_GRDATA." AS fg, ".CFG_DBTBL_MOD_FOTO_GRTREE." as fs, ".CFG_DBTBL_TE_CONTTREE." AS tcs, ".CFG_DBTBL_TE_CONTDATA." AS tcd 
			WHERE fg.id_te_value=? AND tcs.id=fg.code AND tcd.id=tcs.data_id AND fs.data_id=fg.id
			", $this->teValue->getTeValueId()
		);
		$rsFotoGr = $this->_db->get_row($sql);
		
		// Разберем подкатегории если есть и найдем нужную
		if (is_array($this->c_this['value'])){
			$fotogr_id = $rsFotoGr['id'];
			$src_id_te_value = 0;
			foreach ($this->c_this['value'] as $elem){
				$childFotoGr = $fotoTree->select($fotogr_id,
					array(
						'name', 
						'id_te_value', 
						'description', 
						'res_id'
					), NSTREE_AXIS_CHILD
				);
				foreach($childFotoGr as $child) {
					if (getTeValueName($child['id_te_value']) == $elem) {
						$src_id_te_value = $child['id_te_value'];
						break;
					}
				}
			}
		}
		if ($src_id_te_value) {
			// Выберем по шаблонной переменной группу фотографий для текущего адреса страницы
			$sql = sql_placeholder("
				SELECT fs.id, fg.count_per_page, fg.name, fg.description, tcd.code AS 'code' 
				FROM ".CFG_DBTBL_MOD_FOTO_GRDATA." AS fg, ".CFG_DBTBL_MOD_FOTO_GRTREE." as fs, ".CFG_DBTBL_TE_CONTTREE." AS tcs, ".CFG_DBTBL_TE_CONTDATA." AS tcd 
				WHERE fg.id_te_value=? AND tcs.id=fg.code AND tcd.id=tcs.data_id AND fs.data_id=fg.id
				", $src_id_te_value
			);
			$rsFotoGr = $this->_db->get_row($sql);
		}
		fb($rsFotoGr);
		
		if (is_array($rsFotoGr)) {
			// Построим список дочерних групп только если первая страница
			if ($pgnm == 1) {
				$childFotoGr = $fotoTree->select($rsFotoGr['id'],  
					array(
						'name', 
						'id_te_value', 
						'description', 
						'res_id'
					), NSTREE_AXIS_CHILD
				);
 			$i = 0;
				foreach ($childFotoGr as $fotogr) {
					$i++;
					$sql = sql_placeholder("SELECT count(id) FROM " . CFG_DBTBL_MOD_EVENT_PHOTO . " WHERE id_fotogr = ?",$fotogr['id']);
					$count_foto = $this->_db->get_one($sql);
					if ($count_foto) {
						$sql = sql_placeholder("SELECT tmb_path FROM " . CFG_DBTBL_MOD_EVENT_PHOTO . " WHERE id_fotogr = ? ORDER BY RAND() LIMIT 1",$fotogr['id']);
						$tmb = $this->_db->get_one($sql);
						$code .= '<div class="photoCell-right floatLeft">
									 <div class="photoCell-img"><a href="/media/'.getTeValueName($fotogr['id_te_value']).'"/><img src="'.$tmb.'" alt=""></a></div>
									 <div class="photoCell-title"><a href="/media/'.getTeValueName($fotogr['id_te_value']).'/">'.$this->c_lng->Gettextlng($fotogr['name']).'</a>&nbsp;<span>('.$count_foto.')</span></div>
								</div>';
					}
					if(!($i%2)){
						$code .= "\n			<div class=\"floatNone fontNull\">&nbsp;</div>\n";
					}
				}
			}
			
			// Построим список изображений
			$sql = sql_placeholder("
				SELECT fd.id, 
					 
					fd.id_fotogr, fd.alt_text, fd.path, fd.tmb_path, fd.path_orig
				FROM ".CFG_DBTBL_MOD_EVENT_PHOTO." AS fd 
				WHERE id_fotogr=? ORDER BY fd.id", $rsFotoGr['id']
			);
			$foto_complet = $this->_db->get_all($sql);
			
			// Если фотографий больше чем заданно разделе то с формируем маршрутизатор страниц
			if($rsFotoGr['count_per_page'] < count($foto_complet)){
			
				// Определим номер, кол-во страниц и построим маршрутизатор
				$count_pg = ceil((count($foto_complet) - $rsFotoGr['count_per_page'])/$rsFotoGr['count_per_page'])+1;
				$str_router = routerPageStr(getAdrPage($this->c_this['page']['addr']), $pgnm, $count_pg, $this->c_this['arg'], "fl".$this->param_te_value['id']."_id0");
			}else{ 
				$str_router = "";
			}
			
			//$code_in .= "<div class=\"photoLine\">\n";
			$i = 0;
			if (is_array($foto_complet)) {
				foreach ($foto_complet as $foto) {
					$i++;
					$code_in .= $this->makeCode($rsFotoGr['code'], $foto, $this->param_te_value['id'], getAdrPage($this->c_this['page']['addr']));

					if(!($i%2)){
						$code_in .= "\n			<div class=\"floatNone fontNull\">&nbsp;</div>\n";
					}
				}
			}
//		fb($code_in);
			//$code_in .= "<div class=\"floatNone fontNull\">&nbsp;</div>\n</div>\n";			
		} else {
			$sql = sql_placeholder("
				SELECT fg.id AS group_id, fg.count_per_page, fg.name AS group_name, fg.description, tcd.code AS 'code'
				     , f.id, DATE_FORMAT(f.idate, '%d-%m-%Y"._("г.")."') AS idate 
					 , f.id_fotogr, f.name, f.title, f.large_img, f.small_img  
				FROM ".CFG_DBTBL_MOD_FOTO_GRDATA." AS fg
				   , ".CFG_DBTBL_TE_CONTTREE." AS tcs
				   , ".CFG_DBTBL_TE_CONTDATA." AS tcd
				   , ".CFG_DBTBL_MOD_EVENT_PHOTO." AS f
				WHERE f.id_te_value = ?
				  AND f.id_fotogr = fg.id
				  AND tcs.id=fg.code
				  AND tcd.id=tcs.data_id 
				", $this->teValue->getTeValueId()
			);
			$rsFoto = $this->_db->query($sql);
			fb($sql . ' - ' . $rsFoto);
			if($rsFoto->num_rows){
				$foto = $rsFoto->fetch_assoc();
				$code_in .= "\n\n	".$this->makeCode($foto['code'], $foto, $this->param_te_value['id'], getAdrPage($this->c_this['page']['addr']));
			}
		}
		$this->code = $code.'<div class="floatNone"></div>'.$code_in.$str_router;
		return $this->code;
	}
//--------------------------------------------------------------------
	function makeCode($text, $foto, $ptv, $addr)
	{
		$name = $this->c_lng->Gettextlng($foto['name']);
		$span_name = ($name) ? "$name" : ""; 
		$text = preg_replace("/{fototitle}/i", $span_name, $text);
		$text = preg_replace("/{fotodate}/i", $foto['idate'], $text);
		$text = preg_replace("/{fototime}/i", "", $text);
		$text = preg_replace("/{fotodescr}/i", $this->c_lng->Gettextlng($foto['description']), $text);
		$text = preg_replace("/{fotoimage}/i", $foto['img'], $text);
		$text = preg_replace("/{fototmb}/i", $foto['tmb'], $text);
		$text = preg_replace("/{fotoid}/i", $foto['id'], $text);
		$img  = BASE_PATH.$foto['url']."_o.".$foto['exten'];
		if(file_exists($img)){
			$pictu_info = getimagesize($img);
			clearstatcache();
			$width  = $pictu_info[0];
			$height = $pictu_info[1];
 			$text   = preg_replace("/{fotolink}/i", "<a href=\"{$foto['url']}_n.{$foto['exten']}\"  return false;\" style=\"text-decoration:none;\">", $text);
 			$text   = preg_replace("/{_fotolink}/i", "</a>", $text);
		}else{
// 			$text = preg_replace("/{fotoimage}/i", "", $text);
		}		
		return $text;
	}
//---------------------------------------------------------------------
}

?>