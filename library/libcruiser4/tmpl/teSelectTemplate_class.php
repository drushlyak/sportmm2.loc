<?php
//======================================================
class teSelectTemplate extends teTemplate
{
//-----------------------------------------
	function __construct()
	{
		$args = func_get_args();
		$this->ERROR = "";
		parent::__construct($args[0], $args[1], $args[2]);
		return true;
	}
//-----------------------------------------
	public function getCode()
	{
		global $db, $cntTree;
		$this->code = "";
// Получаем информацию о текущем комбинированном шаблоне 
		$sql = sql_placeholder(" 
			SELECT 
				ct.* 
			FROM ".CFG_DBTBL_TE_CONTDATA." cd
			JOIN ".CFG_DBTBL_TE_CONTTREE." AS ct
				ON (ct.data_id = cd.id)
			WHERE cd.id_te_value=?",
			$this->teValue->getTeValueId()
		);
		$contanerSet = $db->get_all($sql);
		if(is_array($contanerSet)){
			foreach($contanerSet as $contaner){
				$sql = sql_placeholder("
					SELECT * 
					FROM ".CFG_DBTBL_TE_SELECTIVE_TMPL."
					WHERE map_id = ?
						AND selective_id = ?",
					$this->c_this['page']['id'], $contaner['id']
				);
				$result = $db->get_row($sql);
				if(is_array($result)){
					break;
				}
			}
		}
		if(is_array($result)){
			$sql = sql_placeholder(" 
				SELECT te.name 
				FROM ".CFG_DBTBL_TE_VALUE." AS te
				JOIN ".CFG_DBTBL_TE_CONTTREE." AS ct
					ON (ct.id = ?)
				JOIN ".CFG_DBTBL_TE_CONTDATA." AS cd
					ON (ct.data_id = cd.id)
				WHERE cd.id_te_value = te.id",
				$result['contaner_id']
			);
			$te_name =  $db->get_one($sql);
			if($te_name){
				$this->code = "{".$te_name."}";	
			}
		}
// Определим номер текущей страницы
		return $this->code;
	}
//----------------------------------------------------------------------
}
?>