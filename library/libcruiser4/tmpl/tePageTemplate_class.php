<?php
//======================================================
class tePageTemplate extends teTemplate
{
	
//-----------------------------------------
	function __construct()
	{
		$args = func_get_args();
		parent::__construct($args[0], $args[1], $args[2]);
		return true;
	}	
//-----------------------------------------
	public function getCode()
	{
		$this->code = false;
		$sql = sql_placeholder("
			SELECT code, printable FROM ".CFG_DBTBL_TE_CONTDATA." 
			WHERE id_te_value=?", $this->teValue->getTeValueId()
		);
		$contaner_data = $this->_db->get_row($sql);
		if($contaner_data){
			$contaner_vis = (($contaner_data['printable'] == TE_PRINT_HTML && ! $this->c_this['printable']) || ($contaner_data['printable'] == TE_PRINT_PRINTABLE && $this->c_this['printable']) || $contaner_data['printable'] == TE_PRINT_ALL) ?  1 : 0;
			if(!$contaner_vis){
// Если данный контейнер выводится на экран не должен то оставим в нем только шаблонные переменные
				preg_match_all("/(?<={)([a-zA-Z0-9_-]+)(?=})/i", $contaner_data['code'], $matches);
				foreach($matches[0] as $match){
					$this->code .= "{".$match."}";
				}
			}else{
				$this->code = $contaner_data['code'];
			}
		}else{
			return false;
		}
		return $this->code;
	}
//----------------------------------------
}

?>