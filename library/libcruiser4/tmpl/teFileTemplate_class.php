<?php
//======================================================
class teFileTemplate extends teTemplate
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
		global $db, $lng;
		ob_start("EmptyFunc");
		include($this->param_te_value['file']);
		$this->code = ob_get_contents();
	    ob_end_clean();
		return $this->code;
	}
//----------------------------------------
}

?>